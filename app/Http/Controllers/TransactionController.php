<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Project;
use App\Models\Dealer;
use App\Models\Incoming;
use App\Models\Outgoing;
use App\Models\SubContractor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Base query - start fresh
            $query = Transaction::with(['project', 'dealer', 'subContractor', 'incoming', 'outgoing']);

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
                        $linked[] = '<span class="badge bg-primary">Project: ' . $transaction->project->name . '</span>';
                    }
                    if ($transaction->dealer) {
                        $linked[] = '<span class="badge bg-info">Dealer: ' . $transaction->dealer->dealer_name . '</span>';
                    }
                    if ($transaction->subContractor) {
                        $linked[] = '<span class="badge bg-warning">Sub-Contractor: ' . $transaction->subContractor->contractor_name . '</span>';
                    }
                    return implode(' ', $linked) ?: 'None';
                })
                ->editColumn('type', function ($transaction) {
                    $class = $transaction->type == 'incoming' ? 'success' : 'danger';
                    $icon = $transaction->type == 'incoming' ? 'fa-arrow-up' : 'fa-arrow-down';
                    return '<span class="badge bg-' . $class . '"><i class="fas ' . $icon . '"></i> ' . ucfirst($transaction->type) . '</span>';
                })
                ->addColumn('action', function ($transaction) {
                    return '
                        <a href="' . route('transactions.edit', $transaction) . '" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-transaction" data-id="' . $transaction->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['type', 'linked_to', 'action', 'amount'])
                ->make(true);
        }

        // Load filter data
        $projects = Project::where('active', true)->orderBy('name')->get();
        $dealers = Dealer::orderBy('dealer_name')->get();
        $subContractors = SubContractor::orderBy('contractor_name')->get(); // Add this

        return view('transactions.index', compact('projects', 'dealers', 'subContractors'));
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

        // Type filter
        if ($request->filled('type') && $request->type != '') {
            $query->where('type', $request->type);
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
        $subContractors = SubContractor::orderBy('contractor_name')->get(); // Add this
        $incomings = Incoming::all();
        $outgoings = Outgoing::all();

        return view('transactions.create', compact('type', 'projects', 'dealers', 'subContractors', 'incomings', 'outgoings'));
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
            'sub_contractor_id' => 'nullable|exists:sub_contractors,id', // Add this
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
        $incomings = Incoming::all();
        $outgoings = Outgoing::all();

        return view('transactions.edit', compact('transaction', 'projects', 'dealers', 'subContractors', 'incomings', 'outgoings'));
    }

    public function update(Request $request, Transaction $transaction)
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
            'sub_contractor_id' => 'nullable|exists:sub_contractors,id',
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

    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting transaction: ' . $e->getMessage()
            ], 500);
        }
    }
}
