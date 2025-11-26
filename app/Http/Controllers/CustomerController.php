<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::select(['id', 'name', 'address', 'gst', 'pan_card', 'phone_no', 'created_at']);

            return DataTables::of($customers)
                ->addIndexColumn()
                ->editColumn('created_at', function ($customer) {
                    return $customer->created_at->format('d/m/Y');
                })
                ->editColumn('address', function ($customer) {
                    return strlen($customer->address) > 50 ? substr($customer->address, 0, 50) . '...' : $customer->address;
                })
                ->addColumn('action', function ($customer) {
                    return '
                        <a href="' . route('customers.show', $customer->id) . '" class="btn btn-info btn-sm">View</a>
                        <a href="' . route('customers.edit', $customer->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        <form action="' . route('customers.destroy', $customer->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('customers.index');
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'gst' => 'nullable|string|max:50',
            'pan_card' => 'nullable|string|max:20',
            'phone_no' => 'required|string|max:15',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        // Load transactions relationship
        $customer->loadMissing(['transactions.incoming', 'transactions.outgoing', 'transactions.project']);

        // Get transactions with proper relationships
        $transactions = $customer->transactions()
            ->with(['incoming', 'outgoing', 'project'])
            ->latest()
            ->get();

        // Calculate totals
        $totalIncoming = $transactions->where('type', 'incoming')->sum('amount');
        $totalOutgoing = $transactions->where('type', 'outgoing')->sum('amount');
        $netBalance = $totalIncoming - $totalOutgoing;

        return view('customers.show', compact('customer', 'transactions', 'totalIncoming', 'totalOutgoing', 'netBalance'));
    }


    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'gst' => 'nullable|string|max:50',
            'pan_card' => 'nullable|string|max:20',
            'phone_no' => 'required|string|max:15',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    // 🔥 NEW: DataTable endpoint for customer transactions
    public function transactionsData(Request $request, Customer $customer)
    {
        if ($request->ajax()) {
            $query = $customer->transactions()
                ->with(['project', 'incoming', 'outgoing'])
                ->select(['id', 'type', 'project_id', 'incoming_id', 'outgoing_id', 'description', 'amount', 'date', 'deleted_at']);

            // Apply filters
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('date', [
                    Carbon::parse($request->from_date)->startOfDay(),
                    Carbon::parse($request->to_date)->endOfDay(),
                ]);
            }

            // Calculate summary
            $summaryQuery = clone $query;
            $summaryQuery->getQuery()->columns = null;

            $totalIncoming = (clone $summaryQuery)->where('type', 'incoming')->sum('amount') ?: 0;
            $totalOutgoing = (clone $summaryQuery)->where('type', 'outgoing')->sum('amount') ?: 0;
            $totalRecords = $summaryQuery->count() ?: 0;
            $netBalance = $totalIncoming - $totalOutgoing;

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('type', function ($tx) {
                    $icon = $tx->type === 'incoming' ? 'fa-arrow-down' : 'fa-arrow-up';
                    $class = $tx->type === 'incoming' ? 'incoming' : 'expense';
                    return "<span class='badge bg-{$class}'><i class='fas {$icon}'></i> " . ucfirst($tx->type) . "</span>";
                })
                ->addColumn('category', function ($tx) {
                    return $tx->type === 'incoming'
                        ? ($tx->incoming->name ?? 'N/A')
                        : ($tx->outgoing->name ?? 'N/A');
                })
                ->addColumn('project_name', function ($transaction) {
                    return $transaction->project ? $transaction->project->name : 'N/A';
                })
                ->editColumn('amount', function ($transaction) {
                    $sign = $transaction->type === 'incoming' ? '+' : '-';
                    $class = $transaction->type === 'incoming' ? 'text-success' : 'text-danger';
                    return "<span class=\"{$class}\">" . $sign . "₹" . number_format($transaction->amount, 2) . "</span>";
                })
                ->editColumn('date', function ($transaction) {
                    return $transaction->date ? $transaction->date->format('d/m/Y') : '';
                })
                ->addColumn('action', function ($transaction) {
                    return '
                <div class="btn-wrapper" role="group">
                    <a href="' . route('transactions.edit', $transaction->id) . '" 
                       class="btn btn-warning btn-sm" 
                       title="Edit Transaction">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" 
                            class="btn btn-danger btn-sm delete-customer-transaction" 
                            data-id="' . $transaction->id . '" 
                            title="Delete Transaction">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>';
                })
                ->rawColumns(['type', 'amount', 'action'])
                ->with([
                    'summary' => [
                        'total_incoming' => $totalIncoming,
                        'total_outgoing' => $totalOutgoing,
                        'net_balance' => $netBalance,
                        'total_records' => $totalRecords,
                    ]
                ])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
}
