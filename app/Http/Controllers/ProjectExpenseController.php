<?php

namespace App\Http\Controllers;

use App\Models\ProjectExpense;
use App\Models\Project;
use App\Models\Outgoing;
use Illuminate\Http\Request;

class ProjectExpenseController extends Controller
{
    public function create(Request $request)
    {
        $projectId = $request->get('project_id');
        $project = Project::findOrFail($projectId);
        $outgoings = Outgoing::all();
        return view('project-expenses.create', compact('project', 'outgoings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'outgoing_id' => 'required|exists:outgoings,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        ProjectExpense::create($request->all());
        return redirect()->route('projects.show', $request->project_id)->with('success', 'Expense added successfully.');
    }

    public function edit(ProjectExpense $projectExpense)
    {
        $outgoings = Outgoing::all();
        return view('project-expenses.edit', compact('projectExpense', 'outgoings'));
    }

    public function update(Request $request, ProjectExpense $projectExpense)
    {
        $request->validate([
            'outgoing_id' => 'required|exists:outgoings,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $projectExpense->update($request->all());
        return redirect()->route('projects.show', $projectExpense->project_id)->with('success', 'Expense updated successfully.');
    }

    public function destroy(ProjectExpense $projectExpense)
    {
        $projectId = $projectExpense->project_id;
        $projectExpense->delete();
        return redirect()->route('projects.show', $projectId)->with('success', 'Expense deleted successfully.');
    }
}
