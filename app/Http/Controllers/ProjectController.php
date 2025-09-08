<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use App\Models\Outgoing;
use App\Models\Incoming;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $projects = Project::select(['id', 'name', 'date', 'department_name', 'amount_project', 'time_limit', 'emd_fdr_detail', 'expenses', 'work_order_date', 'remark', 'active']);

            // Filter by active status if requested
            if ($request->filled('status') && $request->status != '') {
                if ($request->status === 'active') {
                    $projects = $projects->where('active', true);
                } elseif ($request->status === 'inactive') {
                    $projects = $projects->where('active', false);
                }
            }

            return DataTables::of($projects)
                ->editColumn('date', function ($project) {
                    return $project->date ? $project->date->format('d/m/Y') : '';
                })
                ->editColumn('work_order_date', function ($project) {
                    return $project->work_order_date ? $project->work_order_date->format('d/m/Y') : 'N/A';
                })
                ->editColumn('amount_project', function ($project) {
                    return '₹' . number_format($project->amount_project, 2);
                })
                ->addColumn('status', function ($project) {
                    if ($project->active) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-secondary">Inactive</span>';
                    }
                })
                ->addColumn('action', function ($project) {
                    $statusAction = $project->active
                        ? '<a href="' . route('projects.toggle-status', $project->id) . '" class="btn btn-sm btn-secondary" onclick="return confirm(\'Mark as inactive?\')">Mark Inactive</a>'
                        : '<a href="' . route('projects.toggle-status', $project->id) . '" class="btn btn-sm btn-success" onclick="return confirm(\'Mark as active?\')">Mark Active</a>';

                    return '
                        <a href="' . route('projects.show', $project->id) . '" class="btn btn-info btn-sm">View</a>
                        <a href="' . route('projects.edit', $project->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        ' . $statusAction . '
                        <form action="' . route('projects.destroy', $project->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('projects.index');
    }

    public function create()
    {
        return view('projects.create');
    }

    // Update validation in store method
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'department_name' => 'required|string|max:255',
            'amount_project' => 'required|numeric|min:0',
            'time_limit' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        Project::create($request->all());
        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        // Load all related data
        $expenses = $project->expenses()->with('outgoing')->latest()->get();
        $incomes = $project->incomes()->with('incoming')->latest()->get();
        $employees = Employee::all();
        $assignedEmployees = $project->employees;
        $outgoings = Outgoing::all();
        $incomings = Incoming::all();

        // Get all transactions linked to this project
        $transactions = $project->transactions()->with(['incoming', 'outgoing', 'dealer'])->latest()->get();

        // Calculate totals
        $totalProjectExpenses = $expenses->sum('amount');
        $totalProjectIncomes = $incomes->sum('amount');
        $totalTransactionExpenses = $transactions->where('type', 'outgoing')->sum('amount');
        $totalTransactionIncomes = $transactions->where('type', 'incoming')->sum('amount');

        $totalExpenses = $totalProjectExpenses + $totalTransactionExpenses;
        $totalIncomes = $totalProjectIncomes + $totalTransactionIncomes;
        $netProfit = $totalIncomes - $totalExpenses;

        return view('projects.show', compact(
            'project',
            'expenses',
            'incomes',
            'employees',
            'assignedEmployees',
            'outgoings',
            'incomings',
            'transactions',
            'totalExpenses',
            'totalIncomes',
            'netProfit',
            'totalProjectExpenses',
            'totalProjectIncomes',
            'totalTransactionExpenses',
            'totalTransactionIncomes'
        ));
    }



    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    // Update validation in update method
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'department_name' => 'required|string|max:255',
            'amount_project' => 'required|numeric|min:0',
            'time_limit' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        $project->update($request->all());
        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function assignEmployee(Request $request, Project $project)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        $project->employees()->sync($request->employee_ids);
        return redirect()->route('projects.show', $project)->with('success', 'Employees assigned successfully.');
    }

    // Add this new method to remove employee
    public function removeEmployee(Request $request, Project $project)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $project->employees()->detach($request->employee_id);
        return redirect()->route('projects.show', $project)->with('success', 'Employee removed from project successfully.');
    }

    // Add this new method to toggle project status
    public function toggleStatus(Project $project)
    {
        $project->active = !$project->active;
        $project->save();

        $status = $project->active ? 'activated' : 'deactivated';
        return redirect()->route('projects.index')->with('success', "Project has been {$status} successfully.");
    }
}
