<?php

namespace App\Http\Controllers;

use App\Models\SubContractor;
use App\Models\SubContractorBill;
use App\Models\Transaction;
use App\Models\Outgoing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'remark' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($request) {
            // Create the bill
            $bill = SubContractorBill::create([
                'sub_contractor_id' => $request->sub_contractor_id,
                'bill_no' => $request->bill_no,
                'amount' => $request->amount,
                'date' => $request->date,
                'remark' => $request->remark
            ]);

            // Create corresponding transaction
            $subContractor = SubContractor::find($request->sub_contractor_id);

            // Get or create "Sub-Contractor Payments" outgoing category
            $outgoingCategory = Outgoing::firstOrCreate(
                ['name' => 'Sub-Contractor Payments'],
                ['description' => 'Payments to sub-contractors']
            );

            Transaction::create([
                'type' => 'outgoing',
                'amount' => $request->amount,
                'date' => $request->date,
                'description' => "Bill #{$request->bill_no} - {$subContractor->contractor_name} - {$subContractor->project_name}",
                'sub_contractor_id' => $request->sub_contractor_id,
                'outgoing_id' => $outgoingCategory->id
            ]);
        });

        return redirect()
            ->route('sub-contractors.show', $request->sub_contractor_id)
            ->with('success', 'Bill created successfully and transaction recorded.');
    }

    public function edit(SubContractorBill $subContractorBill)
    {
        $subContractor = $subContractorBill->subContractor;
        return view('sub-contractor-bills.edit', compact('subContractorBill', 'subContractor'));
    }

    public function update(Request $request, SubContractorBill $subContractorBill)
    {
        $request->validate([
            'bill_no' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'remark' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($request, $subContractorBill) {
            // Store original bill number for transaction lookup
            $originalBillNo = $subContractorBill->getOriginal('bill_no');

            // Update the bill
            $subContractorBill->update([
                'bill_no' => $request->bill_no,
                'amount' => $request->amount,
                'date' => $request->date,
                'remark' => $request->remark
            ]);

            // Update corresponding transaction
            $transaction = Transaction::where('sub_contractor_id', $subContractorBill->sub_contractor_id)
                ->where('description', 'like', '%' . $originalBillNo . '%')
                ->first();

            if ($transaction) {
                $transaction->update([
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'description' => "Bill #{$request->bill_no} - {$subContractorBill->subContractor->contractor_name} - {$subContractorBill->subContractor->project_name}"
                ]);
            }
        });

        return redirect()
            ->route('sub-contractors.show', $subContractorBill->sub_contractor_id)
            ->with('success', 'Bill updated successfully and transaction updated.');
    }

    public function destroy(SubContractorBill $subContractorBill)
    {
        DB::transaction(function () use ($subContractorBill) {
            // Delete the associated transaction
            $transaction = Transaction::where('sub_contractor_id', $subContractorBill->sub_contractor_id)
                ->where('description', 'like', '%' . $subContractorBill->bill_no . '%')
                ->first();

            if ($transaction) {
                $transaction->delete();
            }

            // Delete the bill
            $subContractorBill->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Bill and transaction deleted successfully!'
        ]);
    }
}
