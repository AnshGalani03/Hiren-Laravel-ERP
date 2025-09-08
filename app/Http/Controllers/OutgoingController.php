<?php

namespace App\Http\Controllers;

use App\Models\Outgoing;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OutgoingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $outgoings = Outgoing::select(['id', 'name', 'created_at']);

            return DataTables::of($outgoings)
                ->addIndexColumn() // This adds serial number
                ->editColumn('created_at', function ($outgoing) {
                    return $outgoing->created_at->format('d/m/Y');
                })
                ->addColumn('action', function ($outgoing) {
                    return '
                        <a href="' . route('outgoings.edit', $outgoing) . '" class="btn btn-sm btn-warning">Edit</a>
                        <form action="' . route('outgoings.destroy', $outgoing) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $outgoings = Outgoing::all();
        return view('outgoings.index', compact('outgoings'));
    }

    public function create()
    {
        return view('outgoings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Outgoing::create($request->all());
        return redirect()->route('outgoings.index')->with('success', 'Outgoing entry created successfully.');
    }

    public function edit(Outgoing $outgoing)
    {
        return view('outgoings.edit', compact('outgoing'));
    }

    public function update(Request $request, Outgoing $outgoing)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $outgoing->update($request->all());
        return redirect()->route('outgoings.index')->with('success', 'Outgoing entry updated successfully.');
    }

    public function destroy(Outgoing $outgoing)
    {
        $outgoing->delete();
        return redirect()->route('outgoings.index')->with('success', 'Outgoing entry deleted successfully.');
    }
}
