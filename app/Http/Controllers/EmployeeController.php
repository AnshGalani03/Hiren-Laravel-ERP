<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Upad;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Transaction;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employee::select(['id', 'name', 'designation', 'mobile_no', 'alt_contact_no', 'pan_no', 'aadhar_no']);

            return DataTables::of($employees)
                ->addColumn('action', function ($employee) {
                    return '
                        <a href="' . route('employees.show', $employee->id) . '" class="btn btn-info btn-sm">View</a>
                        <a href="' . route('employees.edit', $employee->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        <form action="' . route('employees.destroy', $employee->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('employees.index');
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
            'salary' => 'required|numeric|min:0',
            'pf' => 'nullable|string|max:255',        // Changed validation
            'esic' => 'nullable|string|max:255',      // Changed validation
        ]);

        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee, Request $request)
    {
        // Set current month as default if no month is selected
        $selectedMonth = $request->get('month', now()->format('Y-m'));
        $transactionsCount = $employee->transactions()->count();
        // Filter records by selected month only
        $upads = $employee->upads()
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$selectedMonth])
            ->orderBy('date')
            ->get();

        // Get available months for filter dropdown
        $availableMonths = $employee->upads()
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month_key, DATE_FORMAT(date, '%M %Y') as month_name")
            ->groupBy('month_key', 'month_name')
            ->orderBy('month_key', 'desc')
            ->get();

        return view('employees.show', compact('employee', 'upads', 'availableMonths', 'selectedMonth', 'transactionsCount'));
    }


    public function monthlyOverview(Employee $employee)
    {
        $monthlySummary = Upad::getMonthlySummary($employee->id);

        return view('employees.monthly-overview', compact('employee', 'monthlySummary'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // Employee Transactions Data Method
    public function transactionsData(Employee $employee, Request $request)
    {
        if ($request->ajax()) {
            // Get transactions for this employee
            $query = Transaction::where('employee_id', $employee->id)
                ->with(['project', 'dealer', 'subContractor', 'customer', 'incoming', 'outgoing']);

            // Apply filters if provided
            if ($request->filled('type') && $request->type !== '') {
                $query->where('type', $request->type);
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('date', [
                    \Carbon\Carbon::parse($request->from_date)->startOfDay(),
                    \Carbon\Carbon::parse($request->to_date)->endOfDay()
                ]);
            }

            // Calculate summary
            $allTransactions = (clone $query)->get();
            $summary = [
                'total_incoming' => $allTransactions->where('type', 'incoming')->sum('amount'),
                'total_outgoing' => $allTransactions->where('type', 'outgoing')->sum('amount'),
                'net_balance' => $allTransactions->where('type', 'incoming')->sum('amount') -
                    $allTransactions->where('type', 'outgoing')->sum('amount'),
                'total_records' => $allTransactions->count(),
            ];

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date', function ($transaction) {
                    return $transaction->date ? $transaction->date->format('d/m/Y') : '';
                })
                ->editColumn('type', function ($transaction) {
                    $badge = $transaction->type == 'incoming' ? 'bg-success' : 'bg-danger';
                    $icon = $transaction->type == 'incoming' ? 'fa-arrow-down' : 'fa-arrow-up';
                    return '<span class="badge ' . $badge . '"><i class="fas ' . $icon . '"></i> ' . ucfirst($transaction->type) . '</span>';
                })
                ->addColumn('category', function ($transaction) {
                    if ($transaction->type == 'incoming') {
                        return $transaction->incoming ? $transaction->incoming->name : 'N/A';
                    } else {
                        return $transaction->outgoing ? $transaction->outgoing->name : 'N/A';
                    }
                })
                ->addColumn('project_name', function ($transaction) {
                    return $transaction->project ? $transaction->project->name : 'N/A';
                })
                ->editColumn('amount', function ($transaction) {
                    $class = $transaction->type == 'incoming' ? 'text-success' : 'text-danger';
                    $sign = $transaction->type == 'incoming' ? '+' : '-';
                    return '<span class="' . $class . ' fw-bold">' . $sign . '₹' . number_format($transaction->amount, 2) . '</span>';
                })
                ->addColumn('action', function ($transaction) {
                    return '
                    <div class="btn-wrapper" role="group">
                        <a href="' . route('transactions.edit', $transaction->id) . '" class="btn btn-warning btn-sm" title="Edit">
                            Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm delete-emp-transaction" data-id="' . $transaction->id . '" title="Delete">
                            Delete
                        </button>
                    </div>';
                })
                ->with('summary', $summary)
                ->rawColumns(['action', 'amount', 'type'])
                ->make(true);
        }
    }
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
            'salary' => 'required|numeric|min:0',
            'pf' => 'nullable|string|max:255',        // Changed validation
            'esic' => 'nullable|string|max:255',      // Changed validation
        ]);

        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
