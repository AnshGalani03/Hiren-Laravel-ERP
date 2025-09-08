<?php

namespace App\Http\Controllers;

use App\Models\SubContractorBill;
use App\Models\SubContractor;
use Illuminate\Http\Request;

class SubContractorBillController extends Controller
{
    public function create(Request $request)
    {
        $subContractorId = $request->get('sub_contractor_id');
        $subContractor = SubContractor::findOrFail($subContractorId);
        return view('sub-contractor-bills.create', compact('subContractor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_contractor_id' => 'required|exists:sub_contractors,id',
            'bill_no' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        SubContractorBill::create($request->all());
        return redirect()->route('sub-contractors.show', $request->sub_contractor_id)->with('success', 'Bill created successfully.');
    }

    public function edit(SubContractorBill $subContractorBill)
    {
        return view('sub-contractor-bills.edit', compact('subContractorBill'));
    }

    public function update(Request $request, SubContractorBill $subContractorBill)
    {
        $request->validate([
            'bill_no' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $subContractorBill->update($request->all());
        return redirect()->route('sub-contractors.show', $subContractorBill->sub_contractor_id)->with('success', 'Bill updated successfully.');
    }

    public function destroy(SubContractorBill $subContractorBill)
    {
        $subContractorId = $subContractorBill->sub_contractor_id;
        $subContractorBill->delete();
        return redirect()->route('sub-contractors.show', $subContractorId)->with('success', 'Bill deleted successfully.');
    }
}
