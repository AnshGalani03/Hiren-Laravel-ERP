<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TenderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tenders = Tender::select(['id', 'work_name', 'department', 'amount_emd_fdr', 'amount_dd', 'above_below', 'remark', 'return_detail', 'date', 'result']);

            return DataTables::of($tenders)
                ->editColumn('date', function ($tender) {
                    return $tender->date ? $tender->date->format('d/m/Y') : '';
                })
                ->addColumn('action', function ($tender) {
                    return '
                    <a href="' . route('tenders.show', $tender->id) . '" class="btn btn-info btn-sm">View</a>
                    <a href="' . route('tenders.edit', $tender->id) . '" class="btn btn-warning btn-sm">Edit</a>
                    <form action="' . route('tenders.destroy', $tender->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>
                ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('tenders.index');
    }

    public function create()
    {
        return view('tenders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'work_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'amount_emd_fdr' => 'required|numeric|min:0',
            'amount_dd' => 'required|numeric|min:0',
            'above_below' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        Tender::create($request->all());
        return redirect()->route('tenders.index')->with('success', 'Tender created successfully.');
    }

    public function edit(Tender $tender)
    {
        return view('tenders.edit', compact('tender'));
    }

    public function update(Request $request, Tender $tender)
    {
        $request->validate([
            'work_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'amount_emd_fdr' => 'required|numeric|min:0',
            'amount_dd' => 'required|numeric|min:0',
            'above_below' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $tender->update($request->all());
        return redirect()->route('tenders.index')->with('success', 'Tender updated successfully.');
    }

    public function destroy(Tender $tender)
    {
        $tender->delete();
        return redirect()->route('tenders.index')->with('success', 'Tender deleted successfully.');
    }
    public function show(Tender $tender)
    {
        return view('tenders.show', compact('tender'));
    }
}
