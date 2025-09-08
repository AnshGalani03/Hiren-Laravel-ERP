<?php

namespace App\Http\Controllers;

use App\Models\SubContractor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubContractorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Select only required columns
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
        $bills = $subContractor->bills()->latest()->get();
        return view('sub-contractors.show', compact('subContractor', 'bills'));
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
        $subContractor->delete();
        return redirect()->route('sub-contractors.index')->with('success', 'Sub-contractor deleted successfully.');
    }
}
