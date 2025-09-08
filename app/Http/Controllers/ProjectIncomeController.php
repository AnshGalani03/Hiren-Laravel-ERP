<?php

namespace App\Http\Controllers;

use App\Models\ProjectIncome;
use App\Models\Project;
use App\Models\Incoming;
use Illuminate\Http\Request;

class ProjectIncomeController extends Controller
{
    public function create(Request $request)
    {
        $projectId = $request->get('project_id');
        $project = Project::findOrFail($projectId);
        $incomings = Incoming::all();
        return view('project-incomes.create', compact('project', 'incomings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'incoming_id' => 'required|exists:incomings,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        ProjectIncome::create($request->all());
        return redirect()->route('projects.show', $request->project_id)->with('success', 'Income added successfully.');
    }

    public function edit(ProjectIncome $projectIncome)
    {
        $incomings = Incoming::all();
        return view('project-incomes.edit', compact('projectIncome', 'incomings'));
    }

    public function update(Request $request, ProjectIncome $projectIncome)
    {
        $request->validate([
            'incoming_id' => 'required|exists:incomings,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $projectIncome->update($request->all());
        return redirect()->route('projects.show', $projectIncome->project_id)->with('success', 'Income updated successfully.');
    }

    public function destroy(ProjectIncome $projectIncome)
    {
        $projectId = $projectIncome->project_id;
        $projectIncome->delete();
        return redirect()->route('projects.show', $projectId)->with('success', 'Income deleted successfully.');
    }
}
