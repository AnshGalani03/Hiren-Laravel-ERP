<?php

namespace App\Http\Controllers;

use App\Models\SubContractor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\SubContractorBill;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class SubContractorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subContractors = SubContractor::select(['id', 'contractor_name', 'department_name', 'amount_project', 'date', 'time_limit', 'work_order_date']);

            return DataTables::of($subContractors)
                ->addIndexColumn()
                ->editColumn('date', function ($subContractor) {
                    return $subContractor->date ? $subContractor->date->format('d/m/Y') : '';
                })
                ->editColumn('work_order_date', function ($subContractor) {
                    return $subContractor->work_order_date ? $subContractor->work_order_date->format('d/m/Y') : 'N/A';
                })
                ->editColumn('amount_project', function ($subContractor) {
                    return '₹' . number_format($subContractor->amount_project, 2);
                })
                ->addColumn('action', function ($subContractor) {
                    return '
                        <a href="' . route('sub-contractors.show', $subContractor->id) . '" class="btn btn-info btn-sm">View</a>
                        <a href="' . route('sub-contractors.edit', $subContractor->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        <form action="' . route('sub-contractors.destroy', $subContractor->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sub-contractors.index');
    }

    public function create()
    {
        return view('sub-contractors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'contractor_name' => 'required|string|max:255',
            'date' => 'required|date',
            'project_name' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
            'amount_project' => 'required|numeric|min:0',
            'time_limit' => 'required|string|max:255',
        ]);

        SubContractor::create($request->all());
        return redirect()->route('sub-contractors.index')->with('success', 'Sub-contractor created successfully.');
    }

    public function show(SubContractor $subContractor)
    {
        // Get all transactions for this sub-contractor (these are the "bills")
        $transactions = $subContractor->transactions()->with(['incoming', 'outgoing', 'project', 'dealer'])->get();

        return view('sub-contractors.show', compact('subContractor', 'transactions'));
    }

    // DataTable method for transactions (bills)
    public function billsData(Request $request, $subContractor)
    {
        if ($request->ajax()) {
            $subContractor = SubContractor::findOrFail($subContractor);
            $query = $subContractor->transactions()->latest();

            // Apply type filter if provided
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Apply date range filter if provided
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            // Calculate totals for all transactions (without filters for overview)
            $allTransactions = $subContractor->transactions();
            $totalIncoming = (clone $allTransactions)->where('type', 'incoming')->sum('amount');
            $totalOutgoing = (clone $allTransactions)->where('type', 'outgoing')->sum('amount');
            $balance = $totalIncoming - $totalOutgoing;

            // Calculate filtered totals
            $filteredTotal = (clone $query)->sum('amount');
            $filteredCount = (clone $query)->count();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('description', function ($transaction) {
                    return $transaction->description ?: 'N/A';
                })
                ->editColumn('amount', function ($transaction) {
                    $class = $transaction->type == 'incoming' ? 'text-success' : 'text-danger';
                    $sign = $transaction->type == 'incoming' ? '+' : '-';
                    return '<span class="' . $class . '">' . $sign . '₹' . number_format($transaction->amount, 2) . '</span>';
                })
                ->editColumn('date', function ($transaction) {
                    return $transaction->date ? $transaction->date->format('d/m/Y') : 'N/A';
                })
                ->editColumn('type', function ($transaction) {
                    $class = $transaction->type == 'incoming' ? 'success' : 'danger';
                    $icon = $transaction->type == 'incoming' ? 'fa-arrow-up' : 'fa-arrow-down';
                    return '<span class="badge bg-' . $class . '"><i class="fas ' . $icon . '"></i> ' . ucfirst($transaction->type) . '</span>';
                })
                ->addColumn('category', function ($transaction) {
                    if ($transaction->type == 'incoming') {
                        return $transaction->incoming ? $transaction->incoming->name : 'N/A';
                    } else {
                        return $transaction->outgoing ? $transaction->outgoing->name : 'N/A';
                    }
                })
                ->addColumn('action', function ($transaction) {
                    return '
                        <a href="' . route('transactions.edit', $transaction) . '" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger delete-transaction" data-id="' . $transaction->id . '">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    ';
                })
                ->rawColumns(['action', 'type', 'amount'])
                ->with([
                    'total_amount' => $filteredTotal,
                    'total_count' => $filteredCount,
                    'total_incoming' => $totalIncoming,
                    'total_outgoing' => $totalOutgoing,
                    'balance' => $balance
                ])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }



    public function edit(SubContractor $subContractor)
    {
        return view('sub-contractors.edit', compact('subContractor'));
    }

    public function update(Request $request, SubContractor $subContractor)
    {
        $request->validate([
            'contractor_name' => 'required|string|max:255',
            'date' => 'required|date',
            'project_name' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
            'amount_project' => 'required|numeric|min:0',
            'time_limit' => 'required|string|max:255',
        ]);

        $subContractor->update($request->all());
        return redirect()->route('sub-contractors.index')->with('success', 'Sub-contractor updated successfully.');
    }

    public function destroy(SubContractor $subContractor)
    {
        DB::transaction(function () use ($subContractor) {
            // Delete all associated transactions
            Transaction::where('sub_contractor_id', $subContractor->id)->delete();

            // Delete all bills (which will also trigger any related deletions)
            $subContractor->bills()->delete();

            // Delete the sub-contractor
            $subContractor->delete();
        });

        return redirect()->route('sub-contractors.index')->with('success', 'Sub-contractor and all related data deleted successfully.');
    }

    // AJAX delete method for bills
    public function deleteBill($id)
    {
        try {
            $bill = SubContractorBill::findOrFail($id);

            DB::transaction(function () use ($bill) {
                // Delete the associated transaction
                $transaction = Transaction::where('sub_contractor_id', $bill->sub_contractor_id)
                    ->where('description', 'like', '%' . $bill->bill_no . '%')
                    ->first();

                if ($transaction) {
                    $transaction->delete();
                }

                // Delete the bill
                $bill->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Bill and transaction deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting bill: ' . $e->getMessage()
            ], 500);
        }
    }
}
