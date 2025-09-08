<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


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
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dealers.index');
    }

    public function create()
    {
        return view('dealers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dealer_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        Dealer::create($request->all());
        return redirect()->route('dealers.index')->with('success', 'Dealer created successfully.');
    }

    public function show(Dealer $dealer)
    {
        $invoices = $dealer->invoices()->latest()->get();
        $transactions = $dealer->transactions()->with(['incoming', 'outgoing', 'project'])->latest()->get();

        // Calculate totals
        $totalInvoices = $invoices->sum('amount');
        $totalTransactions = $transactions->sum('amount');
        $grandTotal = $totalInvoices + $totalTransactions;

        return view('dealers.show', compact('dealer', 'invoices', 'transactions', 'totalInvoices', 'totalTransactions', 'grandTotal'));
    }


    public function edit(Dealer $dealer)
    {
        return view('dealers.edit', compact('dealer'));
    }

    public function update(Request $request, Dealer $dealer)
    {
        $request->validate([
            'dealer_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        $dealer->update($request->all());
        return redirect()->route('dealers.index')->with('success', 'Dealer updated successfully.');
    }

    public function destroy(Dealer $dealer)
    {
        $dealer->delete();
        return redirect()->route('dealers.index')->with('success', 'Dealer deleted successfully.');
    }

    public function invoicesData(Request $request, Dealer $dealer)
    {
        if ($request->ajax()) {
            $baseQuery = $dealer->invoices()->select(['id', 'bill_no', 'amount', 'date', 'remark']);

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
            $totalAmount = $summaryQuery->sum('amount') ?: 0;

            // Fix: Calculate unique months properly using raw query
            $uniqueMonths = DB::table('invoices')
                ->select(DB::raw('YEAR(date) as year, MONTH(date) as month'))
                ->where('dealer_id', $dealer->id)
                ->when($request->filled('from_date') && $request->filled('to_date'), function ($query) use ($request) {
                    return $query->whereBetween('date', [
                        Carbon::parse($request->from_date)->startOfDay(),
                        Carbon::parse($request->to_date)->endOfDay(),
                    ]);
                })
                ->groupBy('year', 'month')
                ->get()
                ->count();

            $avgPerMonth = $uniqueMonths > 0 ? ($totalAmount / $uniqueMonths) : 0;

            return DataTables::of($baseQuery)
                ->addIndexColumn()
                ->editColumn('amount', function ($invoice) {
                    return '<span data-amount="' . $invoice->amount . '">₹' . number_format($invoice->amount, 2) . '</span>';
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
                ->rawColumns(['amount', 'action'])
                ->with([
                    'summary' => [
                        'total_invoices' => $totalInvoices,
                        'total_amount' => $totalAmount,
                        'avg_per_month' => $avgPerMonth
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
                    $icon = $tx->type === 'incoming' ? 'fa-arrow-up' : 'fa-arrow-down';
                    $class = $tx->type === 'incoming' ? 'success' : 'danger';
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
