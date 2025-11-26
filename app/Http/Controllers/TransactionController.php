<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Project;
use App\Models\Dealer;
use App\Models\SubContractor;
use App\Models\Incoming;
use App\Models\Customer;
use App\Models\Outgoing;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;


class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Base query - start fresh
            $query = Transaction::with(['project', 'dealer', 'subContractor', 'customer', 'employee', 'incoming', 'outgoing']);

            // Apply filters consistently
            $query = $this->applyFilters($query, $request);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date', function ($transaction) {
                    return $transaction->date ? $transaction->date->format('d/m/Y') : '';
                })
                ->editColumn('amount', function ($transaction) {
                    $class = $transaction->type == 'incoming' ? 'text-success' : 'text-danger';
                    $sign = $transaction->type == 'incoming' ? '+' : '-';
                    return '<span class="' . $class . '">' . $sign . '₹' . number_format($transaction->amount, 2) . '</span>';
                })
                ->addColumn('category', function ($transaction) {
                    if ($transaction->type == 'incoming') {
                        return $transaction->incoming ? $transaction->incoming->name : 'N/A';
                    } else {
                        return $transaction->outgoing ? $transaction->outgoing->name : 'N/A';
                    }
                })
                ->addColumn('linked_to', function ($transaction) {
                    $linked = [];
                    if ($transaction->project) {
                        $linked[] = '<span class="badge bg-primary link-title">Project: ' . $transaction->project->name . '</span>';
                    }
                    if ($transaction->dealer) {
                        $linked[] = '<span class="badge bg-info link-title">Dealer: ' . $transaction->dealer->dealer_name . '</span>';
                    }
                    if ($transaction->subContractor) {
                        $linked[] = '<span class="badge bg-warning link-title">Sub-Con: ' . $transaction->subContractor->contractor_name . '</span>';
                    }
                    if ($transaction->customer) {
                        $linked[] = '<span class="badge bg-success link-title">Customer: ' . $transaction->customer->name . '</span>';
                    }
                    if ($transaction->employee) {
                        $linked[] = '<span class="badge bg-secondary link-title">Employee: ' . $transaction->employee->name . '</span>';
                    }
                    return implode(' ', $linked) ?: 'None';
                })
                ->editColumn('type', function ($transaction) {
                    $class = $transaction->type == 'incoming' ? 'incoming' : 'expense';
                    $icon = $transaction->type == 'incoming' ? 'fa-arrow-down' : 'fa-arrow-up';
                    return '<span class="badge bg-' . $class . '"><i class="fas ' . $icon . '"></i> ' . ucfirst($transaction->type) . '</span>';
                })
                ->addColumn('action', function ($transaction) {
                    return '
                        <a href="' . route('transactions.edit', $transaction) . '" class="btn btn-warning btn-sm">Edit</a>
                        <form action="' . route('transactions.destroy', $transaction->id) . '" method="POST" style="display:inline;" class="delete-form">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure? This will move to trash.\')" title="Delete">
                                Delete
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['type', 'linked_to', 'action', 'amount'])
                ->make(true);
        }

        // Load filter data
        $projects = Project::where('active', true)->orderBy('name')->get();
        $dealers = Dealer::orderBy('dealer_name')->get();
        $subContractors = SubContractor::orderBy('contractor_name')->get();
        $employees = Employee::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('transactions.index', compact('projects', 'dealers', 'subContractors', 'employees', 'customers'));
    }

    // Show Trashed (Deleted) Transactions
    public function trashed(Request $request)
    {
        if ($request->ajax()) {
            try {
                $trashedTransactions = Transaction::onlyTrashed() // Only show soft deleted records
                    ->with(['project', 'dealer', 'subContractor', 'customer', 'incoming', 'outgoing', 'employee'])
                    ->select([
                        'id',
                        'type',
                        'amount',
                        'description',
                        'date',
                        'project_id',
                        'dealer_id',
                        'sub_contractor_id',
                        'customer_id',
                        'incoming_id',
                        'employee_id',
                        'outgoing_id',
                        'deleted_at'
                    ]);

                return DataTables::of($trashedTransactions)
                    ->addIndexColumn() // Add index column
                    ->editColumn('date', function ($transaction) {
                        return $transaction->date ? $transaction->date->format('d/m/Y') : '';
                    })
                    ->editColumn('deleted_at', function ($transaction) {
                        return $transaction->deleted_at ? $transaction->deleted_at->format('d/m/Y') : '';
                    })
                    ->editColumn('amount', function ($transaction) {
                        $class = $transaction->type == 'incoming' ? 'text-success' : 'text-danger';
                        $sign = $transaction->type == 'incoming' ? '+' : '-';
                        return '<span class="' . $class . '">' . $sign . '₹' . number_format($transaction->amount, 2) . '</span>';
                    })
                    ->addColumn('category', function ($transaction) {
                        if ($transaction->type == 'incoming') {
                            return $transaction->incoming ? $transaction->incoming->name : 'N/A';
                        } else {
                            return $transaction->outgoing ? $transaction->outgoing->name : 'N/A';
                        }
                    })
                    ->addColumn('linked_to', function ($transaction) {
                        $linked = [];
                        if ($transaction->project) {
                            $linked[] = 'Project: ' . $transaction->project->name;
                        }
                        if ($transaction->dealer) {
                            $linked[] = 'Dealer: ' . $transaction->dealer->dealer_name;
                        }
                        if ($transaction->subContractor) {
                            $linked[] = 'Sub-Contractor: ' . $transaction->subContractor->contractor_name;
                        }
                        if ($transaction->customer) {
                            $linked[] = 'Customer: ' . $transaction->customer->name;
                        }
                        if ($transaction->employee) {
                            $linked[] = 'Employee: ' . $transaction->employee->name;
                        }
                        return implode(', ', $linked) ?: 'None';
                    })
                    ->editColumn('type', function ($transaction) {
                        return ucfirst($transaction->type);
                    })
                    ->addColumn('action', function ($transaction) {
                        return '
                        <div class="">
                            <form action="' . route('transactions.restore', $transaction->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm(\'Are you sure you want to restore this Transaction?\')" title="Restore">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                            </form>
                            <form action="' . route('transactions.force-delete', $transaction->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to permanently delete this Transaction? This cannot be undone!\')" title="Permanent Delete">
                                    <i class="fas fa-trash-alt"></i> Delete Forever
                                </button>
                            </form>
                        </div>
                        ';
                    })
                    ->rawColumns(['action', 'amount', 'linked_to', 'type'])
                    ->make(true);
            } catch (\Exception $e) {
                // Log::error('Trashed DataTable error: ' . $e->getMessage());
                return response()->json(['error' => 'Error loading trashed data: ' . $e->getMessage()], 500);
            }
        }

        return view('transactions.trashed');
    }

    // Restore Deleted Transaction
    public function restore($id): RedirectResponse
    {
        try {
            $transaction = Transaction::onlyTrashed()->findOrFail($id);
            $transaction->restore();

            return redirect()
                ->route('transactions.trashed')
                ->with('success', 'Transaction restored successfully');
        } catch (\Exception $e) {
            // Log::error('Restore error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Failed to restore Transaction: ' . $e->getMessage()]);
        }
    }

    // Permanently Delete Transaction
    public function forceDelete($id): RedirectResponse
    {
        try {
            $transaction = Transaction::onlyTrashed()->findOrFail($id);
            $description = $transaction->description;
            $transaction->forceDelete(); // Permanently delete

            return redirect()
                ->route('transactions.trashed')
                ->with('success', 'Transaction permanently deleted: ' . $description);
        } catch (\Exception $e) {
            // Log::error('Force delete error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Failed to permanently delete Transaction: ' . $e->getMessage()]);
        }
    }


    // NEW: Separate method to apply filters consistently
    private function applyFilters($query, $request)
    {
        // Project filter
        if ($request->filled('project_id') && $request->project_id != '') {
            $query->where('project_id', $request->project_id);
        }

        // Dealer filter
        if ($request->filled('dealer_id') && $request->dealer_id != '') {
            $query->where('dealer_id', $request->dealer_id);
        }

        // Sub-Contractor filter - ADD THIS
        if ($request->filled('sub_contractor_id') && $request->sub_contractor_id != '') {
            $query->where('sub_contractor_id', $request->sub_contractor_id);
        }

        // Customer filter
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Type filter
        if ($request->filled('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Employee Filter
        if ($request->filled('employee_id') && $request->employee_id !== '' && $request->employee_id !== 'all') {
            $query->where('employee_id', $request->employee_id);
        }

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            try {
                $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
                $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereBetween('date', [$fromDate, $toDate]);
            } catch (\Exception $e) {
                // If date parsing fails, ignore the date filter
            }
        }

        return $query;
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'incoming');
        $projects = Project::where('active', true)->orderBy('name')->get();
        $dealers = Dealer::orderBy('dealer_name')->get();
        $subContractors = SubContractor::orderBy('contractor_name')->get();
        $employees = Employee::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        $incomings = Incoming::all();
        $outgoings = Outgoing::all();

        return view('transactions.create', compact('type', 'projects', 'dealers', 'subContractors', 'employees', 'customers', 'incomings', 'outgoings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:incoming,outgoing',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'incoming_id' => 'required_if:type,incoming|exists:incomings,id',
            'outgoing_id' => 'required_if:type,outgoing|exists:outgoings,id',
            'project_id' => 'nullable|exists:projects,id',
            'dealer_id' => 'nullable|exists:dealers,id',
            'employee_id' => 'nullable|exists:employees,id',
            'sub_contractor_id' => 'nullable|exists:sub_contractors,id',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        // Additional validation: if project is selected, ensure it's active
        if ($request->filled('project_id')) {
            $project = Project::find($request->project_id);
            if (!$project || !$project->active) {
                return back()->withErrors(['project_id' => 'Selected project is not active.'])->withInput();
            }
        }

        Transaction::create($request->all());
        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    public function edit(Transaction $transaction)
    {
        $projects = Project::where('active', true)->orderBy('name')->get();
        $dealers = Dealer::orderBy('dealer_name')->get();
        $subContractors = SubContractor::orderBy('contractor_name')->get();
        $customers = Customer::orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        $incomings = Incoming::all();
        $outgoings = Outgoing::all();

        return view('transactions.edit', compact('transaction', 'projects', 'dealers', 'subContractors', 'employees', 'customers', 'incomings', 'outgoings'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'type' => 'required|in:incoming,outgoing',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            // 'incoming_id' => 'required_if:type,incoming|exists:incomings,id',
            // 'outgoing_id' => 'required_if:type,outgoing|exists:outgoings,id',
            'project_id' => 'nullable|exists:projects,id',
            'dealer_id' => 'nullable|exists:dealers,id',
            'employee_id' => 'nullable|exists:employees,id',
            'sub_contractor_id' => 'nullable|exists:sub_contractors,id',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        // Additional validation: if project is selected, ensure it's active
        if ($request->filled('project_id')) {
            $project = Project::find($request->project_id);
            if (!$project || !$project->active) {
                return back()->withErrors(['project_id' => 'Selected project is not active.'])->withInput();
            }
        }

        $transaction->update($request->all());
        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function summary(Request $request)
    {
        // Use the same filtering method for consistency
        $baseQuery = Transaction::query();
        $query = $this->applyFilters($baseQuery, $request);

        // Clone query for different calculations to avoid conflicts
        $totalIncoming = (clone $query)->where('type', 'incoming')->sum('amount') ?: 0;
        $totalOutgoing = (clone $query)->where('type', 'outgoing')->sum('amount') ?: 0;
        $totalRecords = (clone $query)->count() ?: 0;
        $netBalance = $totalIncoming - $totalOutgoing;

        return response()->json([
            'total_incoming' => $totalIncoming,
            'total_outgoing' => $totalOutgoing,
            'net_balance' => $netBalance,
            'total_records' => $totalRecords
        ]);
    }

    // Now uses soft delete and redirects with flash message
    public function destroy(Transaction $transaction)
    {
        try {
            DB::beginTransaction();
            $transactionDescription = $transaction->description; // Store description before deletion
            $transaction->delete(); // This will soft delete
            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Transaction moved to trash successfully!"
                ]);
            }

            // For non-AJAX requests
            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction moved to trash You can restore it from the trash.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Delete error: ' . $e->getMessage());

            // For AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete transaction: ' . $e->getMessage()
                ], 500);
            }

            // For non-AJAX requests
            return redirect()
                ->route('transactions.index')
                ->withErrors(['error' => 'Failed to delete Transaction: ' . $e->getMessage()]);
        }
    }
}
