<?php

namespace App\Http\Controllers;

use App\Models\Upad;
use App\Models\Employee;
use Illuminate\Http\Request;

class UpadController extends Controller
{
    public function create(Request $request)
    {
        $employeeId = $request->get('employee_id');
        $employee = Employee::findOrFail($employeeId);
        return view('upads.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'upad' => 'required|numeric|min:0',
            'remark' => 'nullable|string|max:500'
        ]);

        // Only save fields that exist in database
        Upad::create($request->only(['employee_id', 'date', 'salary', 'upad', 'remark']));

        return redirect()->route('employees.show', $request->employee_id)
            ->with('success', 'Upad created successfully.');
    }

    public function edit(Upad $upad)
    {
        return view('upads.edit', compact('upad'));
    }

    public function update(Request $request, Upad $upad)
    {
        $request->validate([
            'date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'upad' => 'required|numeric|min:0',
            'remark' => 'nullable|string|max:500'
        ]);

        // Only update fields that exist in database
        $upad->update($request->only(['date', 'salary', 'upad', 'remark']));

        return redirect()->route('employees.show', $upad->employee_id)
            ->with('success', 'Upad updated successfully.');
    }

    public function destroy(Upad $upad)
    {
        $employeeId = $upad->employee_id;
        $upad->delete();

        // No need to recalculate since we're using accessor
        return redirect()->back()
            ->with('success', 'Record deleted successfully.');
    }

    public function monthlyView(Request $request, Employee $employee)
    {
        $currentMonth = $request->get('month', date('n'));
        $currentYear = $request->get('year', date('Y'));

        $monthlyData = $employee->calculateMonthlySalary($currentMonth, $currentYear);
        $monthlySummary = $employee->getMonthlySummary(12);

        return view('upads.monthly', compact('employee', 'monthlyData', 'monthlySummary', 'currentMonth', 'currentYear'));
    }

    public function monthlyReport(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $employees = Employee::with(['upads' => function ($query) use ($month, $year) {
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }])->get();

        $reportData = [];
        foreach ($employees as $employee) {
            $reportData[] = $employee->calculateMonthlySalary($month, $year);
        }

        return view('upads.monthly-report', compact('reportData', 'month', 'year'));
    }

    public function updatePaymentStatus(Request $request, Upad $upad)
    {
        $request->validate([
            'field' => 'required|in:salary_paid',
            'value' => 'required'
        ]);

        $field = $request->field;
        $value = filter_var($request->value, FILTER_VALIDATE_BOOLEAN);

        $upad->update([$field => $value]);

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully'
        ]);
    }
}
