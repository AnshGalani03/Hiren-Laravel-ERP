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
            'month' => 'required|string|max:255',
            'date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'upad' => 'required|numeric|min:0',
            'pending' => 'numeric|min:0',
        ]);

        Upad::create($request->all());
        return redirect()->route('employees.show', $request->employee_id)->with('success', 'Upad created successfully.');
    }

    public function edit(Upad $upad)
    {
        return view('upads.edit', compact('upad'));
    }

    public function update(Request $request, Upad $upad)
    {
        $request->validate([
            'month' => 'required|string|max:255',
            'date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'upad' => 'required|numeric|min:0',
            'pending' => 'numeric|min:0',
        ]);

        $upad->update($request->all());
        return redirect()->route('employees.show', $upad->employee_id)->with('success', 'Upad updated successfully.');
    }

    public function destroy(Upad $upad)
    {
        $employeeId = $upad->employee_id;
        $upad->delete();
        return redirect()->route('employees.show', $employeeId)->with('success', 'Upad deleted successfully.');
    }
}
