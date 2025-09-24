<?php

namespace App\Http\Controllers;

use App\Models\RABill;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class RABillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $raBills = RABill::with(['customer', 'project'])
                    ->select([
                        'id',
                        'bill_no',
                        'date',
                        'customer_id',
                        'project_id',
                        'ra_bill_amount',
                        'sgst_9_percent',
                        'cgst_9_percent',
                        'total_deductions',
                        'net_amount',
                        'created_at'
                    ]);

                return DataTables::of($raBills)
                    ->editColumn('date', function ($bill) {
                        return $bill->date ? $bill->date->format('d/m/Y') : '';
                    })
                    ->addColumn('customer_name', function ($bill) {
                        return $bill->customer ? $bill->customer->name : 'N/A';
                    })
                    ->addColumn('project_name', function ($bill) {
                        return $bill->project ? $bill->project->name : 'N/A';
                    })
                    ->editColumn('ra_bill_amount', function ($bill) {
                        return '₹' . number_format($bill->ra_bill_amount, 0);
                    })

                    ->editColumn('net_amount', function ($bill) {
                        return '₹' . number_format($bill->net_amount, 0);
                    })
                    ->addColumn('action', function ($bill) {
                        return '
                        <div class="">
                            <a href="' . route('ra-bills.show', $bill->id) . '" class="btn btn-info btn-sm">View</a>
                            <a href="' . route('ra-bills.edit', $bill->id) . '" class="btn btn-warning btn-sm">Edit</a>
                            <form action="' . route('ra-bills.destroy', $bill->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')" title="Delete">
                                Delete
                                </button>
                            </form>
                        </div>
                    ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('DataTable error: ' . $e->getMessage());
                return response()->json(['error' => 'Error loading data: ' . $e->getMessage()], 500);
            }
        }

        return view('ra-bills.index');
    }

    public function create(): View
    {
        try {
            $customers = Customer::orderBy('name')->get();
        } catch (\Exception $e) {
            $customers = collect();
            Log::warning('Customer table not found: ' . $e->getMessage());
        }

        try {
            $projects = Project::where('active', 1)->orderBy('name')->get();
        } catch (\Exception $e) {
            $projects = collect();
            Log::warning('Project table not found: ' . $e->getMessage());
        }

        $nextBillNo = RABill::generateBillNo();

        return view('ra-bills.create', compact('customers', 'projects', 'nextBillNo'));
    }

    public function store(Request $request): RedirectResponse
    {
        Log::info('Store method called with data:', $request->all());

        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'customer_id' => 'required|exists:customers,id',
                'project_id' => 'required|exists:projects,id',
                'ra_bill_amount' => 'required|numeric|min:0',
                'dept_taxes_overheads' => 'required|numeric|min:0',
                'tds_1_percent' => 'required|numeric|min:0',
                'rmd_amount' => 'nullable|numeric|min:0',
                'welfare_cess' => 'nullable|numeric|min:0',
                'testing_charges' => 'nullable|numeric|min:0',
            ]);

            Log::info('Validation passed:', $validated);

            // Set default values for nullable fields
            $validated['rmd_amount'] = $validated['rmd_amount'] ?? 0;
            $validated['welfare_cess'] = $validated['welfare_cess'] ?? 0;
            $validated['testing_charges'] = $validated['testing_charges'] ?? 0;

            DB::beginTransaction();

            $raBill = RABill::create($validated);

            Log::info('RABill created:', $raBill->toArray());

            DB::commit();

            return redirect()
                ->route('ra-bills.show', $raBill)
                ->with('success', 'R.A. Bill created successfully with Bill No: ' . $raBill->bill_no);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withErrors(['error' => 'Failed to create R.A. Bill: ' . $e->getMessage()])
                ->withInput();
        }
    }
    public function show(RABill $raBill): View
    {
        $raBill->load(['customer', 'project']);
        return view('ra-bills.show', compact('raBill'));
    }

    public function edit(RABill $raBill): View
    {
        try {
            $customers = Customer::orderBy('name')->get();
        } catch (\Exception $e) {
            $customers = collect();
        }

        try {
            $projects = Project::where('active', 1)->orderBy('name')->get();
        } catch (\Exception $e) {
            $projects = collect();
        }

        return view('ra-bills.edit', compact('raBill', 'customers', 'projects'));
    }

    public function update(Request $request, RABill $raBill): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'customer_id' => 'required|exists:customers,id',
                'project_id' => 'required|exists:projects,id',
                'ra_bill_amount' => 'required|numeric|min:0',
                'dept_taxes_overheads' => 'required|numeric|min:0',
                'tds_1_percent' => 'required|numeric|min:0',
                'rmd_amount' => 'nullable|numeric|min:0',
                'welfare_cess' => 'nullable|numeric|min:0',
                'testing_charges' => 'nullable|numeric|min:0',
            ]);

            $validated['rmd_amount'] = $validated['rmd_amount'] ?? 0;
            $validated['welfare_cess'] = $validated['welfare_cess'] ?? 0;
            $validated['testing_charges'] = $validated['testing_charges'] ?? 0;

            DB::beginTransaction();

            $raBill->update($validated);

            DB::commit();

            return redirect()
                ->route('ra-bills.show', $raBill)
                ->with('success', 'R.A. Bill updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Failed to update R.A. Bill: ' . $e->getMessage()])
                ->withInput();
        }
    }
    public function destroy(RABill $raBill): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $raBill->delete();
            DB::commit();

            return redirect()
                ->route('ra-bills.index')
                ->with('success', 'R.A. Bill deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Failed to delete R.A. Bill: ' . $e->getMessage()]);
        }
    }

    public function generatePdf(RABill $raBill)
    {
        $raBill->load(['customer', 'project']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ra-bills.pdf', compact('raBill'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'Arial']);

        return $pdf->download('RA-Bill-' . $raBill->bill_no . '.pdf');
    }

    public function previewPdf(Request $request)
    {
        $raBill = new RABill($request->all());

        try {
            $raBill->customer = Customer::find($request->customer_id);
        } catch (\Exception $e) {
            $raBill->customer = (object)['name' => 'Default Customer'];
        }

        try {
            $raBill->project = Project::find($request->project_id);
        } catch (\Exception $e) {
            $raBill->project = (object)['name' => 'Default Project'];
        }

        $raBill->bill_no = RABill::generateBillNo();

        $calculations = RABill::calculateAmounts(
            $request->ra_bill_amount,
            $request->dept_taxes_overheads,
            $request->tds_1_percent,
            $request->rmd_amount ?? 0,
            $request->welfare_cess ?? 0,
            $request->testing_charges ?? 0
        );

        foreach ($calculations as $key => $value) {
            $raBill->$key = $value;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ra-bills.pdf', compact('raBill'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'Arial']);

        return $pdf->stream('RA-Bill-Preview.pdf');
    }

    public function getNextBillNo(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'bill_no' => RABill::generateBillNo()
        ]);
    }
}
