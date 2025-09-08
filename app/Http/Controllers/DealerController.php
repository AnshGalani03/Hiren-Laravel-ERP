<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
}
