<x-app-layout>
    <x-slot name="header">
        <div class="bill-detail-header flex justify-between items-center">
            <a href="{{ route('ra-bills.show', $raBill) }}"
                class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit R.A. Bill - {{ $raBill->bill_no }}
            </h2>
        </div>
    </x-slot>


    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form action="{{ route('ra-bills.update', $raBill) }}" method="POST" id="raBillForm">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="rabill-amount">
                    <div class="row mb-6">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bill No (Auto Generated)</label>
                            <input type="text" value="{{ $raBill->bill_no }}" readonly
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="date" id="date" required
                                value="{{ old('date', $raBill->date->format('Y-m-d')) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date') border-red-500 @enderror">
                            @error('date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Customer <span class="text-red-500">*</span></label>
                            <select name="customer_id" id="customer_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_id') border-red-500 @enderror">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $raBill->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project <span class="text-red-500">*</span></label>
                            <select name="project_id" id="project_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('project_id') border-red-500 @enderror">
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $raBill->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                <!-- Manual Amount Fields -->
                <div class="rabill-amount">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Amount Details</h3>

                        <div class="row gap-6">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="block text-sm font-medium text-gray-700 mb-2">(A) R.A. Bill Amount <span class="text-red-500">*</span></label>
                                <input type="number" name="ra_bill_amount" id="ra_bill_amount" required
                                    value="{{ old('ra_bill_amount', $raBill->ra_bill_amount) }}" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ra_bill_amount') border-red-500 @enderror"
                                    placeholder="0.00">
                                @error('ra_bill_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="block text-sm font-medium text-gray-700 mb-2">(B) Department Taxes & Overheads <span class="text-red-500">*</span></label>
                                <input type="number" name="dept_taxes_overheads" id="dept_taxes_overheads" required
                                    value="{{ old('dept_taxes_overheads', $raBill->dept_taxes_overheads) }}" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('dept_taxes_overheads') border-red-500 @enderror"
                                    placeholder="0.00">
                                @error('dept_taxes_overheads')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="block text-sm font-medium text-gray-700 mb-2">TDS 1% <span class="text-red-500">*</span></label>
                                <input type="number" name="tds_1_percent" id="tds_1_percent" required
                                    value="{{ old('tds_1_percent', $raBill->tds_1_percent) }}" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tds_1_percent') border-red-500 @enderror"
                                    placeholder="0.00">
                                @error('tds_1_percent')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="block text-sm font-medium text-gray-700 mb-2">RMD Amount</label>
                                <input type="number" name="rmd_amount" id="rmd_amount"
                                    value="{{ old('rmd_amount', $raBill->rmd_amount) }}" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('rmd_amount') border-red-500 @enderror"
                                    placeholder="0.00">
                                @error('rmd_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Welfare Cess</label>
                                <input type="number" name="welfare_cess" id="welfare_cess"
                                    value="{{ old('welfare_cess', $raBill->welfare_cess) }}" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('welfare_cess') border-red-500 @enderror"
                                    placeholder="0.00">
                                @error('welfare_cess')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Testing Charges</label>
                                <input type="number" name="testing_charges" id="testing_charges"
                                    value="{{ old('testing_charges', $raBill->testing_charges) }}" step="0.01" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('testing_charges') border-red-500 @enderror"
                                    placeholder="0.00">
                                @error('testing_charges')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Live Calculation Display -->
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Live Calculations</h3>

                    <div class="amount-detail-wrapper">
                        <div class="amount-detail-cal">
                            <div class="flex justify-between">
                                <span class="font-medium">(A) R.A. Bill Amount:</span>
                                <span class="text-blue-600 font-mono" id="display_ra_bill">₹{{ number_format($raBill->ra_bill_amount, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">(B) Dept. Taxes & Overheads:</span>
                                <span class="text-red-600 font-mono" id="display_dept_taxes">₹{{ number_format($raBill->dept_taxes_overheads, 0) }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-bold">(C) Total (A - B):</span>
                                <span class="text-purple-600 font-bold font-mono" id="display_total_c">₹{{ number_format($raBill->total_c, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">SGST @ 9%:</span>
                                <span class="text-green-600 font-mono" id="display_sgst">₹{{ number_format($raBill->sgst_9_percent, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">CGST @ 9%:</span>
                                <span class="text-green-600 font-mono" id="display_cgst">₹{{ number_format($raBill->cgst_9_percent, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">IGST @ 0%:</span>
                                <span class="text-gray-500 font-mono">₹0</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-bold">(D) Total With GST:</span>
                                <span class="text-indigo-600 font-bold font-mono" id="display_total_with_gst">₹{{ number_format($raBill->total_with_gst, 0) }}</span>
                            </div>
                        </div>

                        <div class="amount-detail-cal">
                            <div class="font-bold text-gray-900 mb-2">(E) Deductions:</div>
                            <div class="flex justify-between">
                                <span>TDS 1%:</span>
                                <span class="text-red-600 font-mono" id="display_tds">₹{{ number_format($raBill->tds_1_percent, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>RMD:</span>
                                <span class="text-red-600 font-mono" id="display_rmd">₹{{ number_format($raBill->rmd_amount, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Welfare Cess:</span>
                                <span class="text-red-600 font-mono" id="display_welfare">₹{{ number_format($raBill->welfare_cess, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Testing Charges:</span>
                                <span class="text-red-600 font-mono" id="display_testing">₹{{ number_format($raBill->testing_charges, 0) }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-bold">Total Deductions:</span>
                                <span class="text-red-700 font-bold font-mono" id="display_total_deductions">₹{{ number_format($raBill->total_deductions, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-300 pt-4 mt-4">
                        <div class="flex justify-content-end">
                            <div class="bg-green-100 rounded-lg">
                                <span class="font-bold text-green-700">Net Amount:</span>
                                <span class="text-green-700 font-bold text-2xl font-mono ml-4" id="display_net_amount">₹{{ number_format($raBill->net_amount, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('ra-bills.show', $raBill) }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update R.A. Bill</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = ['ra_bill_amount', 'dept_taxes_overheads', 'tds_1_percent', 'rmd_amount', 'welfare_cess', 'testing_charges'];

            function updateCalculations() {
                const raBillAmount = parseFloat(document.getElementById('ra_bill_amount').value) || 0;
                const deptTaxes = parseFloat(document.getElementById('dept_taxes_overheads').value) || 0;
                const tds1 = parseFloat(document.getElementById('tds_1_percent').value) || 0;
                const rmd = parseFloat(document.getElementById('rmd_amount').value) || 0;
                const welfare = parseFloat(document.getElementById('welfare_cess').value) || 0;
                const testing = parseFloat(document.getElementById('testing_charges').value) || 0;

                // Simple calculations as per your requirements
                const totalC = Math.round((raBillAmount - deptTaxes) * 100) / 100;
                const sgst9 = Math.round((totalC * 0.09) * 100) / 100;
                const cgst9 = Math.round((totalC * 0.09) * 100) / 100;
                const totalWithGst = Math.round((totalC + sgst9 + cgst9) * 100) / 100;
                const totalDeductions = Math.round((tds1 + rmd + welfare + testing) * 100) / 100;
                const netAmount = Math.round((totalWithGst - totalDeductions) * 100) / 100;

                // Update displays (without decimals)
                document.getElementById('display_ra_bill').textContent = '₹' + Math.round(raBillAmount).toLocaleString();
                document.getElementById('display_dept_taxes').textContent = '₹' + Math.round(deptTaxes).toLocaleString();
                document.getElementById('display_total_c').textContent = '₹' + Math.round(totalC).toLocaleString();
                document.getElementById('display_sgst').textContent = '₹' + Math.round(sgst9).toLocaleString();
                document.getElementById('display_cgst').textContent = '₹' + Math.round(cgst9).toLocaleString();
                document.getElementById('display_total_with_gst').textContent = '₹' + Math.round(totalWithGst).toLocaleString();
                document.getElementById('display_tds').textContent = '₹' + Math.round(tds1).toLocaleString();
                document.getElementById('display_rmd').textContent = '₹' + Math.round(rmd).toLocaleString();
                document.getElementById('display_welfare').textContent = '₹' + Math.round(welfare).toLocaleString();
                document.getElementById('display_testing').textContent = '₹' + Math.round(testing).toLocaleString();
                document.getElementById('display_total_deductions').textContent = '₹' + Math.round(totalDeductions).toLocaleString();
                document.getElementById('display_net_amount').textContent = '₹' + Math.round(netAmount).toLocaleString();
            }

            // Add event listeners
            inputs.forEach(inputId => {
                document.getElementById(inputId).addEventListener('input', updateCalculations);
            });

            // Initial calculation
            updateCalculations();
        });
    </script>
</x-app-layout>