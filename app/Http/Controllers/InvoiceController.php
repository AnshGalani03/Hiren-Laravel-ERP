<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dealerId = $request->input('dealer_id');
            $query = Invoice::with('dealer');

            if ($dealerId) {
                $query->where('dealer_id', $dealerId);
            }

            // Apply date filters
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            } elseif ($request->filled('start_date')) {
                $query->whereDate('date', '>=', $request->start_date);
            } elseif ($request->filled('end_date')) {
                $query->whereDate('date', '<=', $request->end_date);
            }

            return DataTables::eloquent($query)
                ->filter(function ($query) use ($request) {
                    if ($search = $request->input('search.value')) {
                        $query->where(function ($q) use ($search) {
                            $q->where('bill_no', 'like', "%{$search}%")
                                ->orWhere('original_amount', 'like', "%{$search}%")
                                ->orWhere('remark', 'like', "%{$search}%")
                                ->orWhereHas('dealer', function ($dq) use ($search) {
                                    $dq->where('dealer_name', 'like', "%{$search}%");
                                });
                        });
                    }
                })
                ->addColumn('dealer_name', function ($invoice) {
                    return $invoice->dealer ? $invoice->dealer->dealer_name : 'N/A';
                })
                ->editColumn('date', function ($invoice) {
                    return $invoice->date ? $invoice->date->format('d/m/Y') : '';
                })
                // Display only GST amount with label
                ->editColumn('amount', function ($invoice) {
                    return '₹ ' . number_format($invoice->original_amount, 2);
                })
                ->editColumn('remark', function ($invoice) {
                    return $invoice->remark ?: 'N/A';
                })
                ->addColumn('action', function ($invoice) {
                    return '
                        <a href="' . route('invoices.edit', $invoice->id) . '" class="btn btn-sm btn-warning">
                            Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm delete-invoice" 
                                data-id="' . $invoice->id . '" 
                                onclick="deleteInvoice(' . $invoice->id . ')" title="Delete">
                            <i class="fas fa-trash"></i> Delete
                        </button>';
                })
                ->addIndexColumn()
                ->rawColumns(['original_amount', 'action'])
                ->make(true);
        }

        $dealers = Dealer::orderBy('dealer_name')->get();
        return view('invoices.index', compact('dealers'));
    }


    // Show Trashed Invoices
    public function trashed(Request $request)
    {
        if ($request->ajax()) {
            try {
                $trashedInvoices = Invoice::onlyTrashed()
                    ->with('dealer')
                    ->select([
                        'id',
                        'dealer_id',
                        'bill_no',
                        'original_amount',
                        'amount',
                        'date',
                        'deleted_at'
                    ]);

                return DataTables::of($trashedInvoices)
                    ->editColumn('date', function ($invoice) {
                        return $invoice->date ? $invoice->date->format('d/m/Y') : '';
                    })
                    ->editColumn('deleted_at', function ($invoice) {
                        return $invoice->deleted_at ? $invoice->deleted_at->format('d/m/Y') : '';
                    })
                    ->addColumn('dealer_name', function ($invoice) {
                        return $invoice->dealer ? $invoice->dealer->dealer_name : 'N/A';
                    })
                    ->editColumn('original_amount', function ($invoice) {
                        // 🔧 FIX: Handle null/empty original_amount properly
                        $amount = $invoice->original_amount ?? 0;
                        return '₹' . number_format((float)$amount, 2);
                    })
                    ->addColumn('action', function ($invoice) {
                        return '
                        <div class="">
                            <button type="button" class="btn btn-success btn-sm" onclick="restoreInvoice(' . $invoice->id . ')" title="Restore">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteInvoicePermanently(' . $invoice->id . ')" title="Delete Forever">
                                <i class="fas fa-trash-alt"></i> Delete Forever
                            </button>
                        </div>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                // Log::error('Trashed DataTable error: ' . $e->getMessage());
                return response()->json(['error' => 'Error loading trashed data: ' . $e->getMessage()], 500);
            }
        }

        return view('invoices.trashed');
    }


    // Restore Deleted Invoice
    public function restore($id): RedirectResponse
    {
        try {
            $invoice = Invoice::onlyTrashed()->findOrFail($id);
            $invoice->restore();

            return redirect()
                ->route('invoices.trashed')
                ->with('success', 'Invoice restored successfully: ' . $invoice->bill_no);
        } catch (\Exception $e) {
            // Log::error('Restore error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Failed to restore Invoice: ' . $e->getMessage()]);
        }
    }

    // Permanently Delete Invoice
    public function forceDelete($id): RedirectResponse
    {
        try {
            $invoice = Invoice::onlyTrashed()->findOrFail($id);
            $billNo = $invoice->bill_no;
            $invoice->forceDelete();

            return redirect()
                ->route('invoices.trashed')
                ->with('success', 'Invoice permanently deleted: ' . $billNo);
        } catch (\Exception $e) {
            // Log::error('Force delete error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Failed to permanently delete Invoice: ' . $e->getMessage()]);
        }
    }


    public function create(Request $request): View
    {
        $dealerId = $request->get('dealer_id');
        $dealer = Dealer::findOrFail($dealerId);
        return view('invoices.create', compact('dealer'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dealer_id' => 'required|exists:dealers,id',
            'bill_no' => 'required|string|max:255',
            'original_amount' => 'required|numeric|min:0|max:999999999.99', // User enters this
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'date' => 'required|date',
            'remark' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Calculate GST amount from original amount
            $gstRate = $validated['gst_rate'] ?? 18.0;
            $originalAmount = $validated['original_amount'];
            $gstAmount = Invoice::calculateGstAmount($originalAmount, $gstRate);

            // Create invoice with calculated GST amount
            Invoice::create([
                'dealer_id' => $validated['dealer_id'],
                'bill_no' => $validated['bill_no'],
                'amount' => $gstAmount, // Store only GST amount
                'original_amount' => $originalAmount,
                'gst_rate' => $gstRate,
                'date' => $validated['date'],
                'remark' => $validated['remark'],
            ]);

            DB::commit();

            return redirect()
                ->route('dealers.show', $validated['dealer_id'])
                ->with('success', 'Invoice created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Failed to create invoice. Please try again.'])
                ->withInput();
        }
    }

    public function edit(Invoice $invoice): View
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'bill_no' => 'required|string|max:255',
            'original_amount' => 'required|numeric|min:0|max:999999999.99',
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'date' => 'required|date',
            'remark' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Calculate new GST amount
            $gstRate = $validated['gst_rate'] ?? 18.0;
            $originalAmount = $validated['original_amount'];
            $gstAmount = Invoice::calculateGstAmount($originalAmount, $gstRate);

            // Update invoice
            $invoice->update([
                'bill_no' => $validated['bill_no'],
                'amount' => $gstAmount, // Store only GST amount
                'original_amount' => $originalAmount,
                'gst_rate' => $gstRate,
                'date' => $validated['date'],
                'remark' => $validated['remark'],
            ]);

            DB::commit();

            return redirect()
                ->route('dealers.show', $invoice->dealer_id)
                ->with('success', 'Invoice updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Failed to update invoice. Please try again.'])
                ->withInput();
        }
    }

    // UPDATED: Now uses soft delete
    public function destroy(Invoice $invoice): JsonResponse
    {
        DB::beginTransaction();
        try {
            $invoice->delete(); // This will soft delete
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice moved to trash successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Invoice delete error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    public function summary(Request $request): JsonResponse
    {
        $query = Invoice::query();

        // Apply dealer filter
        if ($request->filled('dealer_id') && $request->dealer_id != '') {
            $query->where('dealer_id', $request->dealer_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $totalInvoices = $query->count();
        $totalGstAmount = $query->sum('amount') ?: 0; // Sum of GST amounts
        $totalOriginalAmount = $query->sum('original_amount') ?: 0; // Sum of original amounts
        $uniqueDealers = $query->distinct('dealer_id')->count('dealer_id');

        return response()->json([
            'total_invoices' => $totalInvoices,
            'total_gst_amount' => $totalGstAmount,
            'total_original_amount' => $totalOriginalAmount,
            'unique_dealers' => $uniqueDealers,
        ]);
    }
}
