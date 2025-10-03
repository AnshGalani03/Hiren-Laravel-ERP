<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Models\DealerBankAccount;


class DealerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dealers = Dealer::select(['id', 'dealer_name', 'mobile_no', 'gst', 'address']);

            return DataTables::of($dealers)
                ->addColumn('action', function ($dealer) {
                    return '
                        <a href="' . route('dealers.show', $dealer->id) . '" class="btn btn-info btn-sm">View</a>
                        <a href="' . route('dealers.edit', $dealer->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        <form action="' . route('dealers.destroy', $dealer->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure? This will move to trash.\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dealers.index');
    }

    // NEW: Show Trashed (Deleted) Dealers
    public function trashed(Request $request)
    {
        if ($request->ajax()) {
            try {
                $trashedDealers = Dealer::onlyTrashed() // Only show soft deleted records
                    ->select([
                        'id',
                        'dealer_name',
                        'mobile_no',
                        'gst',
                        'address',
                        'deleted_at'
                    ]);

                return DataTables::of($trashedDealers)
                    ->editColumn('deleted_at', function ($dealer) {
                        return $dealer->deleted_at ? $dealer->deleted_at->format('d/m/Y') : '';
                    })
                    ->addColumn('action', function ($dealer) {
                        return '
                            <div class="">
                                <form action="' . route('dealers.restore', $dealer->id) . '" method="POST" style="display:inline;">
                                    ' . csrf_field() . '
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm(\'Are you sure you want to restore this Dealer?\')" title="Restore">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                                <form action="' . route('dealers.force-delete', $dealer->id) . '" method="POST" style="display:inline;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to permanently delete this Dealer? This cannot be undone!\')" title="Permanent Delete">
                                        <i class="fas fa-trash-alt"></i> Delete Forever
                                    </button>
                                </form>
                            </div>
                            ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                // Log::error('Trashed DataTable error: ' . $e->getMessage());
                return response()->json(['error' => 'Error loading trashed data: ' . $e->getMessage()], 500);
            }
        }

        return view('dealers.trashed');
    }

    // NEW: Restore Deleted Dealer
    public function restore($id): RedirectResponse
    {
        try {
            $dealer = Dealer::onlyTrashed()->findOrFail($id);
            $dealer->restore();

            return redirect()
                ->route('dealers.trashed')
                ->with('success', 'Dealer restored successfully: ' . $dealer->dealer_name);
        } catch (\Exception $e) {
            // Log::error('Restore error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Failed to restore Dealer: ' . $e->getMessage()]);
        }
    }

    // NEW: Permanently Delete Dealer
    public function forceDelete($id): RedirectResponse
    {
        try {
            $dealer = Dealer::onlyTrashed()->findOrFail($id);
            $dealerName = $dealer->dealer_name;
            $dealer->forceDelete(); // Permanently delete

            return redirect()
                ->route('dealers.trashed')
                ->with('success', 'Dealer permanently deleted: ' . $dealerName);
        } catch (\Exception $e) {
            // Log::error('Force delete error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Failed to permanently delete Dealer: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('dealers.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'dealer_name' => 'required|string|max:255',
                'mobile_no' => 'required|string|max:15',
                'address' => 'required|string',
                'gst' => 'nullable|string',

                // Simplified bank account validation
                'bank_accounts' => 'required|array|min:1',
                'bank_accounts.*.account_name' => 'required|string|max:255',
                'bank_accounts.*.account_no' => 'required|string',
                'bank_accounts.*.bank_name' => 'required|string|max:255',
                'bank_accounts.*.ifsc' => 'required|string',
                'bank_accounts.*.notes' => 'nullable|string|max:500',

            ]);

            DB::beginTransaction();

            // Create dealer
            $dealer = Dealer::create([
                'dealer_name' => $request->dealer_name,
                'mobile_no' => $request->mobile_no,
                'address' => $request->address,
                'gst' => $request->gst,
            ]);

            // Create bank accounts
            foreach ($request->bank_accounts as $bankAccount) {
                DealerBankAccount::create([
                    'dealer_id' => $dealer->id,
                    'account_name' => $bankAccount['account_name'],
                    'account_no' => $bankAccount['account_no'],
                    'bank_name' => $bankAccount['bank_name'],
                    'ifsc' => $bankAccount['ifsc'],
                    'notes' => $bankAccount['notes'],
                ]);
            }

            DB::commit();
            return redirect()->route('dealers.index')->with('success', 'Dealer created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating dealer: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Dealer $dealer)
    {
        $invoices = $dealer->invoices()->latest()->get();
        $transactions = $dealer->transactions()->with(['incoming', 'outgoing', 'project'])->latest()->get();

        // Calculate totals
        $totalInvoices = $invoices->sum('original_amount');
        $totalTransactions = $transactions->sum('amount');
        $pendingAmount = $totalInvoices - $totalTransactions;

        return view('dealers.show', compact('dealer', 'invoices', 'transactions', 'totalInvoices', 'totalTransactions', 'pendingAmount'));
    }


    public function edit(Dealer $dealer)
    {
        try {
            $dealer->load('bankAccounts');
            return view('dealers.edit', compact('dealer'));
        } catch (\Exception $e) {
            return redirect()->route('dealers.index')
                ->with('error', 'Error loading dealer for editing: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Dealer $dealer)
    {
        try {
            $validated = $request->validate([
                'dealer_name' => 'required|string|max:255',
                'mobile_no' => 'required|string|max:15',
                'gst' => 'nullable|string|max:15',
                'address' => 'required|string',

                // Simplified bank account validation
                'bank_accounts' => 'required|array|min:1',
                'bank_accounts.*.id' => 'nullable|exists:dealer_bank_accounts,id',
                'bank_accounts.*.account_name' => 'required|string|max:255',
                'bank_accounts.*.account_no' => 'required|string|max:20',
                'bank_accounts.*.bank_name' => 'required|string|max:255',
                'bank_accounts.*.ifsc' => 'required|string|max:11',
                'bank_accounts.*.notes' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            // Update dealer basic info
            $dealer->update([
                'dealer_name' => $validated['dealer_name'],
                'mobile_no' => $validated['mobile_no'],
                'address' => $validated['address'],
                'gst' => $validated['gst'],
            ]);

            // Get existing bank account IDs
            $existingIds = collect($validated['bank_accounts'])
                ->pluck('id')
                ->filter()
                ->toArray();

            // Delete bank accounts that are not in the submitted data
            $dealer->bankAccounts()->whereNotIn('id', $existingIds)->delete();

            // Update or create bank accounts
            foreach ($validated['bank_accounts'] as $bankAccountData) {
                if (isset($bankAccountData['id']) && $bankAccountData['id']) {
                    // Update existing account
                    $dealer->bankAccounts()->where('id', $bankAccountData['id'])->update([
                        'account_name' => $bankAccountData['account_name'],
                        'account_no' => $bankAccountData['account_no'],
                        'bank_name' => $bankAccountData['bank_name'],
                        'ifsc' => $bankAccountData['ifsc'],
                        'notes' => $bankAccountData['notes'],
                    ]);
                } else {
                    // Create new account
                    $dealer->bankAccounts()->create([
                        'account_name' => $bankAccountData['account_name'],
                        'account_no' => $bankAccountData['account_no'],
                        'bank_name' => $bankAccountData['bank_name'],
                        'ifsc' => $bankAccountData['ifsc'],
                        'notes' => $bankAccountData['notes'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('dealers.index')
                ->with('success', 'Dealer updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating dealer: ' . $e->getMessage())
                ->withInput();
        }
    }

    //  UPDATED: Now uses soft delete
    public function destroy(Dealer $dealer)
    {
        try {
            // Check if dealer has related records
            if ($dealer->invoices()->count() > 0 || $dealer->transactions()->count() > 0) {
                return redirect()->route('dealers.index')
                    ->with('error', 'Cannot delete dealer. It has related invoices or transactions.');
            }

            $dealer->delete(); // This will also delete bank accounts due to cascade

            return redirect()->route('dealers.index')
                ->with('success', 'Dealer deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dealers.index')
                ->with('error', 'Error deleting dealer: ' . $e->getMessage());
        }
    }

    public function invoicesData(Request $request, Dealer $dealer)
    {
        if ($request->ajax()) {
            $baseQuery = $dealer->invoices()->select(['id', 'bill_no', 'amount', 'original_amount', 'gst_rate', 'date', 'remark']);

            // Apply filters
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $baseQuery->whereBetween('date', [
                    Carbon::parse($request->from_date)->startOfDay(),
                    Carbon::parse($request->to_date)->endOfDay(),
                ]);
            }

            // Calculate summary with correct queries
            $summaryQuery = $dealer->invoices();
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $summaryQuery->whereBetween('date', [
                    Carbon::parse($request->from_date)->startOfDay(),
                    Carbon::parse($request->to_date)->endOfDay(),
                ]);
            }

            $totalInvoices = $summaryQuery->count() ?: 0;
            $totalOriginalAmount = $summaryQuery->sum('original_amount') ?: 0;
            $totalGstAmount = $summaryQuery->sum('amount') ?: 0; // Sum of GST amounts

            // REMOVED: Average calculation

            return DataTables::of($baseQuery)
                ->addIndexColumn()
                ->editColumn('amount', function ($invoice) {
                    return '₹ ' . number_format($invoice->original_amount, 2);
                })
                ->editColumn('date', function ($invoice) {
                    return $invoice->date ? $invoice->date->format('d/m/Y') : '';
                })
                ->editColumn('remark', function ($invoice) {
                    return $invoice->remark ?: 'N/A';
                })

                ->addColumn('action', function ($invoice) {
                    return '
                        <a href="' . route('invoices.edit', $invoice->id) . '" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm delete-invoice" data-id="' . $invoice->id . '">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    ';
                })
                ->rawColumns(['amount', 'original_amount', 'action'])
                ->with([
                    'summary' => [
                        'total_invoices' => $totalInvoices,
                        'total_original_amount' => $totalOriginalAmount,
                        'total_gst_amount' => $totalGstAmount,
                        // REMOVED: avg_per_month
                    ]
                ])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }


    public function transactionsData(Request $request, Dealer $dealer)
    {
        if ($request->ajax()) {
            $baseQuery = $dealer->transactions()->with(['project', 'incoming', 'outgoing'])
                ->select(['id', 'type', 'project_id', 'incoming_id', 'outgoing_id', 'description', 'amount', 'date']);

            // Apply filters
            if ($request->filled('type')) {
                $baseQuery->where('type', $request->type);
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $baseQuery->whereBetween('date', [
                    Carbon::parse($request->from_date)->startOfDay(),
                    Carbon::parse($request->to_date)->endOfDay(),
                ]);
            }

            // Calculate summary with separate queries
            $summaryQuery = $dealer->transactions();
            if ($request->filled('type')) {
                $summaryQuery->where('type', $request->type);
            }
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $summaryQuery->whereBetween('date', [
                    Carbon::parse($request->from_date)->startOfDay(),
                    Carbon::parse($request->to_date)->endOfDay(),
                ]);
            }

            $totalIncoming = (clone $summaryQuery)->where('type', 'incoming')->sum('amount') ?: 0;
            $totalOutgoing = (clone $summaryQuery)->where('type', 'outgoing')->sum('amount') ?: 0;
            $totalRecords = $summaryQuery->count() ?: 0;
            $netBalance = $totalIncoming - $totalOutgoing;

            return DataTables::of($baseQuery)
                ->addIndexColumn()
                ->editColumn('amount', function ($tx) {
                    $sign = $tx->type === 'incoming' ? '+' : '-';
                    $class = $tx->type === 'incoming' ? 'text-success' : 'text-danger';
                    return "<span class='{$class}' data-amount='{$tx->amount}' data-type='{$tx->type}'>" . $sign . "₹" . number_format($tx->amount, 2) . "</span>";
                })
                ->editColumn('date', function ($tx) {
                    return $tx->date ? $tx->date->format('d/m/Y') : '';
                })
                ->addColumn('category', function ($tx) {
                    return $tx->type === 'incoming' ?
                        ($tx->incoming->name ?? 'N/A') : ($tx->outgoing->name ?? 'N/A');
                })
                ->addColumn('project_name', function ($tx) {
                    return $tx->project ? $tx->project->name : 'N/A';
                })
                ->editColumn('type', function ($tx) {
                    $icon = $tx->type === 'incoming' ? 'fa-arrow-down' : 'fa-arrow-up';
                    $class = $tx->type === 'incoming' ? 'incoming' : 'expense';
                    return "<span class='badge bg-{$class}'><i class='fas {$icon}'></i> " . ucfirst($tx->type) . "</span>";
                })
                ->addColumn('action', function ($tx) {
                    return '
                        <a href="' . route('transactions.edit', $tx->id) . '" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm delete-transaction" data-id="' . $tx->id . '">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    ';
                })
                ->rawColumns(['amount', 'type', 'action'])
                ->with([
                    'summary' => [
                        'total_incoming' => $totalIncoming,
                        'total_outgoing' => $totalOutgoing,
                        'net_balance' => $netBalance,
                        'total_records' => $totalRecords
                    ]
                ])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
}
