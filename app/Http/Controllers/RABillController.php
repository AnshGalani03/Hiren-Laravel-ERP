<?php

namespace App\Http\Controllers;

use App\Models\RABill;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

                // Apply date filters
                if ($request->from_date) {
                    $raBills->whereDate('date', '>=', $request->from_date);
                }

                if ($request->to_date) {
                    $raBills->whereDate('date', '<=', $request->to_date);
                }

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
                            <a href="' . route('ra-bills.show', $bill->id) . '" class="btn btn-info btn-sm">
                                View
                            </a>
                            <a href="' . route('ra-bills.edit', $bill->id) . '" class="btn btn-warning btn-sm">
                                Edit
                            </a>
                            <a href="' . route('ra-bills.download-pdf', $bill->id) . '" class="btn btn-success btn-sm">
                                Download PDF
                            </a>
                            <form action="' . route('ra-bills.destroy', $bill->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return view('ra-bills.index');
    }

    public function create()
    {
        try {
            $customers = Customer::orderBy('name')->get();
            $projects = Project::orderBy('name')->get();

            // Generate next bill number for display
            $nextBillNo = RABill::generateBillNo();

            return view('ra-bills.create', compact('customers', 'projects', 'nextBillNo'));
        } catch (\Exception $e) {
            return redirect()->route('ra-bills.index')->with('error', 'Error loading create page: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'project_id' => 'required|exists:projects,id',
                'date' => 'required|date',
                'ra_bill_amount' => 'required|numeric|min:0',
                'dept_taxes_overheads' => 'required|numeric|min:0',
                'tds_1_percent' => 'required|numeric|min:0',
                'rmd_amount' => 'nullable|numeric|min:0',
                'welfare_cess' => 'nullable|numeric|min:0',
                'testing_charges' => 'nullable|numeric|min:0',
            ]);

            // Set default values for nullable fields
            $validated['rmd_amount'] = $validated['rmd_amount'] ?? 0;
            $validated['welfare_cess'] = $validated['welfare_cess'] ?? 0;
            $validated['testing_charges'] = $validated['testing_charges'] ?? 0;

            // Start database transaction
            DB::beginTransaction();

            try {
                // Create the RA Bill (calculations will be done automatically in model)
                $raBill = RABill::create($validated);

                DB::commit();

                return redirect()->route('ra-bills.index')
                    ->with('success', 'RA Bill created successfully! Bill No: ' . $raBill->bill_no);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating RA Bill: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $raBill = RABill::with(['customer', 'project'])->findOrFail($id);
            return view('ra-bills.show', compact('raBill'));
        } catch (\Exception $e) {
            return redirect()->route('ra-bills.index')->with('error', 'RA Bill not found.');
        }
    }

    public function edit($id)
    {
        try {
            $raBill = RABill::findOrFail($id);
            $customers = Customer::orderBy('name')->get();
            $projects = Project::orderBy('name')->get();
            return view('ra-bills.edit', compact('raBill', 'customers', 'projects'));
        } catch (\Exception $e) {
            return redirect()->route('ra-bills.index')->with('error', 'Error loading edit page.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $raBill = RABill::findOrFail($id);

            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'project_id' => 'required|exists:projects,id',
                'date' => 'required|date',
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

            $raBill->update($validated);

            return redirect()->route('ra-bills.index')
                ->with('success', 'RA Bill updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating RA Bill: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $raBill = RABill::findOrFail($id);
            $raBill->delete();

            return redirect()->route('ra-bills.index')
                ->with('success', 'RA Bill deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('ra-bills.index')
                ->with('error', 'Error deleting RA Bill.');
        }
    }

    public function downloadPdf($id)
    {
        try {
            $raBill = RABill::with(['customer', 'project'])->find($id);

            if (!$raBill) {
                return redirect()->route('ra-bills.index')->with('error', 'RA Bill not found.');
            }

            // Use your proper PDF template instead of simple HTML
            $pdf = Pdf::loadView('ra-bills.pdf', compact('raBill'))
                ->setPaper('A4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => false,
                    'debugKeepTemp' => false,
                ]);

            return $pdf->download($raBill->bill_no . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('ra-bills.index')
                ->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }


    public function calculateAmounts(Request $request)
    {
        try {
            $raBillAmount = floatval($request->ra_bill_amount ?? 0);
            $deptTaxes = floatval($request->dept_taxes_overheads ?? 0);
            $tds1 = floatval($request->tds_1_percent ?? 0);
            $rmd = floatval($request->rmd_amount ?? 0);
            $welfare = floatval($request->welfare_cess ?? 0);
            $testing = floatval($request->testing_charges ?? 0);

            $calculations = RABill::calculateAmounts($raBillAmount, $deptTaxes, $tds1, $rmd, $welfare, $testing);

            return response()->json([
                'total_c' => number_format($calculations['total_c'], 0),
                'sgst_9_percent' => number_format($calculations['sgst_9_percent'], 0),
                'cgst_9_percent' => number_format($calculations['cgst_9_percent'], 0),
                'igst_0_percent' => number_format($calculations['igst_0_percent'], 0),
                'total_with_gst' => number_format($calculations['total_with_gst'], 0),
                'total_deductions' => number_format($calculations['total_deductions'], 0),
                'net_amount' => number_format($calculations['net_amount'], 0),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
