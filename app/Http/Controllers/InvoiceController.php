<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Dealer;
use Illuminate\Http\Request;
// use Yajra\DataTables\DataTables;
use Yajra\DataTables\Facades\DataTables;


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

            return DataTables::eloquent($query)
                ->filter(function ($query) use ($request) {
                    if ($search = $request->input('search.value')) {
                        $query->where(function ($q) use ($search) {
                            $q->where('bill_no', 'like', "%{$search}%")
                                ->orWhere('amount', 'like', "%{$search}%")
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
                // ADD THIS LINE: Format the date column
                ->editColumn('date', function ($invoice) {
                    return $invoice->date ? $invoice->date->format('d/m/Y') : '';
                })
                ->addColumn('action', function ($invoice) {
                    return '
                        <a href="' . route('invoices.edit', $invoice->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-invoice" data-id="' . $invoice->id . '">Delete</button>
                    ';
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        $dealers = Dealer::orderBy('dealer_name')->get();
        return view('invoices.index', compact('dealers'));
    }



    public function create(Request $request)
    {
        $dealerId = $request->get('dealer_id');
        $dealer = Dealer::findOrFail($dealerId);
        return view('invoices.create', compact('dealer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dealer_id' => 'required|exists:dealers,id',
            'bill_no' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'remark' => 'nullable|string|max:500',
        ]);
        Invoice::create($request->all());
        return redirect()->route('dealers.show', $request->dealer_id)->with('success', 'Invoice created successfully.');
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'bill_no' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'remark' => 'nullable|string|max:500',
        ]);
        $invoice->update($request->all());
        return redirect()->route('dealers.show', $invoice->dealer_id)->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return response()->json([
                'success' => true,
                'message' => 'Invoice deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    public function summary(Request $request)
    {
        $query = Invoice::query();

        // Apply dealer filter
        if ($request->filled('dealer_id') && $request->dealer_id != '') {
            $query->where('dealer_id', $request->dealer_id);
        }

        $totalInvoices = $query->count();
        $totalAmount = $query->sum('amount') ?: 0;
        $uniqueDealers = $query->distinct('dealer_id')->count('dealer_id');
        $avgAmount = $totalInvoices > 0 ? ($totalAmount / $totalInvoices) : 0;

        return response()->json([
            'total_invoices' => $totalInvoices,
            'total_amount' => $totalAmount,
            'unique_dealers' => $uniqueDealers,
            'avg_amount' => $avgAmount
        ]);
    }
}
