<?php

namespace App\Http\Controllers;

use App\Models\Incoming;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class IncomingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $incomings = Incoming::select(['id', 'name', 'created_at']);

            return DataTables::of($incomings)
                ->addIndexColumn() // This adds serial number
                ->editColumn('created_at', function ($incoming) {
                    return $incoming->created_at->format('d/m/Y');
                })
                ->addColumn('action', function ($incoming) {
                    return '
                        <a href="' . route('incomings.edit', $incoming) . '" class="btn btn-sm btn-warning">Edit</a>
                        <form action="' . route('incomings.destroy', $incoming) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $incomings = Incoming::all();
        return view('incomings.index', compact('incomings'));
    }

    public function create()
    {
        return view('incomings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Incoming::create($request->all());
        return redirect()->route('incomings.index')->with('success', 'Incoming entry created successfully.');
    }

    public function edit(Incoming $incoming)
    {
        return view('incomings.edit', compact('incoming'));
    }

    public function update(Request $request, Incoming $incoming)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $incoming->update($request->all());
        return redirect()->route('incomings.index')->with('success', 'Incoming entry updated successfully.');
    }

    public function destroy(Incoming $incoming)
    {
        $incoming->delete();
        return redirect()->route('incomings.index')->with('success', 'Incoming entry deleted successfully.');
    }
}
