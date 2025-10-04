<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Upad;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\Dealer;
use App\Models\SubContractor;

class ExportController extends Controller
{
    /**
     * Show export options page
     */
    public function index()
    {
        $employees = Employee::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $dealers = Dealer::orderBy('dealer_name')->get();
        $subContractors = SubContractor::orderBy('contractor_name')->get();
        return view('exports.index', compact('employees', 'projects', 'dealers', 'subContractors'));
    }

    /**
     * Export Employee Upad Report
     */
    public function exportUpadReport(Request $request)
    {
        try {
            $request->validate([
                'employee_id' => 'nullable|exists:employees,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            // Add small delay for progress demo
            if ($request->ajax()) {
                sleep(1); // Remove this in production if not needed
            }

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            // Build query
            $query = Upad::with(['employee'])
                ->whereBetween('date', [$startDate, $endDate]);

            if ($request->employee_id) {
                $query->where('employee_id', $request->employee_id);
                $employee = Employee::find($request->employee_id);
                $title = $employee->name . ' - Upad Report';
            } else {
                $title = 'All Employees - Upad Report';
                $employee = null;
            }

            $upads = $query->orderBy('date', 'desc')->get();

            // Calculate totals using the correct 'upad' column
            $totalUpad = $upads->sum('upad');  // Changed from 'amount' to 'upad'

            $data = [
                'upads' => $upads,
                'employee' => $employee,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'title' => $title,
                'total_upad' => $totalUpad,  // Changed variable name
                'generated_at' => now()
            ];

            $pdf = Pdf::loadView('exports.upad-report', $data)
                ->setPaper('A4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);

            $filename = 'upad-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf';

            // Return appropriate response based on request type
            if ($request->ajax()) {
                return $pdf->download($filename);
            } else {
                return $pdf->download($filename);
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return redirect()->back()->withErrors(['error' => 'Failed to generate report: ' . $e->getMessage()]);
            }
        }
    }

    /**
     * Export Transactions Report
     */
    public function exportTransactionsReport(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'type' => 'nullable|in:incoming,outgoing',
                'project_id' => 'nullable|exists:projects,id',
                'dealer_id' => 'nullable|exists:dealers,id',          // Add dealer validation
                'sub_contractor_id' => 'nullable|exists:sub_contractors,id', // Add sub-contractor validation
            ]);

            // Add small delay for progress demo
            if ($request->ajax()) {
                sleep(1);
            }

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            // Build query with relationships loaded
            $query = Transaction::with(['incoming', 'outgoing', 'project', 'dealer', 'subContractor'])
                ->whereBetween('date', [$startDate, $endDate]);

            // Filter by type
            if ($request->type) {
                $query->where('type', $request->type);
            }

            // Filter by project
            $project = null;
            if ($request->project_id) {
                $query->where('project_id', $request->project_id);
                $project = \App\Models\Project::find($request->project_id);
            }

            // Filter by dealer
            $dealer = null;
            if ($request->dealer_id) {
                $query->where('dealer_id', $request->dealer_id);
                $dealer = \App\Models\Dealer::find($request->dealer_id);
            }

            // Filter by sub-contractor
            $subContractor = null;
            if ($request->sub_contractor_id) {
                $query->where('sub_contractor_id', $request->sub_contractor_id);
                $subContractor = \App\Models\SubContractor::find($request->sub_contractor_id);
            }

            // Build title based on filters
            $titleParts = [];
            if ($request->type) {
                $titleParts[] = ucfirst($request->type);
            } else {
                $titleParts[] = 'All';
            }
            $titleParts[] = 'Transactions';

            if ($project) {
                $titleParts[] = '- ' . $project->name;
            }
            if ($dealer) {
                $titleParts[] = '- ' . $dealer->dealer_name; // Changed to dealer_name
            }
            if ($subContractor) {
                $titleParts[] = '- ' . $subContractor->contractor_name; // Changed to contractor_name
            }

            $title = implode(' ', $titleParts) . ' Report';

            $transactions = $query->orderBy('date', 'desc')->get();

            // Calculate totals for incoming and outgoing
            $totalIncoming = $transactions->where('type', 'incoming')->sum('amount') ?? 0;
            $totalOutgoing = $transactions->where('type', 'outgoing')->sum('amount') ?? 0;
            $netAmount = $totalIncoming - $totalOutgoing;

            $data = [
                'transactions' => $transactions,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'title' => $title,
                'type' => $request->type,
                'project' => $project,
                'dealer' => $dealer,
                'sub_contractor' => $subContractor,
                'total_incoming' => $totalIncoming,
                'total_outgoing' => $totalOutgoing,
                'net_amount' => $netAmount,
                'generated_at' => now()
            ];

            $pdf = Pdf::loadView('exports.transactions-report', $data)
                ->setPaper('A4', 'landscape') // Changed to landscape for more columns
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);

            $filename = 'transactions-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d');
            if ($project) {
                $filename .= '-' . Str::slug($project->name);
            }
            if ($dealer) {
                $filename .= '-' . Str::slug($dealer->dealer_name);
            }
            if ($subContractor) {
                $filename .= '-' . Str::slug($subContractor->contractor_name);
            }
            $filename .= '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            // Log::error('Transactions Report Error: ' . $e->getMessage(), [
            //     'trace' => $e->getTraceAsString(),
            //     'request_data' => $request->all()
            // ]);

            if ($request->ajax()) {
                return response()->json(['error' => 'Error generating report: ' . $e->getMessage()], 500);
            } else {
                return redirect()->back()->withErrors(['error' => 'Failed to generate report: ' . $e->getMessage()]);
            }
        }
    }
}
