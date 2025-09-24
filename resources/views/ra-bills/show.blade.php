<x-app-layout>
    <x-slot name="header">
        <div class="bill-detail-header flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                R.A. Bill Details - {{ $raBill->bill_no }}
            </h2>
            <div class="ra-bill-action-btn">
                <a href="{{ route('ra-bills.edit', $raBill) }}" class="btn btn-warning btn-sm">Edit</a>
                <a href="{{ route('ra-bills.download-pdf', $raBill->id) }}" class="btn btn-success btn-sm">Download PDF
                </a>
                <form action="{{ route('ra-bills.destroy', $raBill) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this R.A. Bill?')" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <a href="{{ route('ra-bills.index') }}"
                class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <!-- Bill Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bill Information</h3>
                    <div class="ra-bill-info">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Bill No:</span>
                            <span>{{ $raBill->bill_no }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Date:</span>
                            <span>{{ $raBill->date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Customer:</span>
                            <span>{{ $raBill->customer->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Project:</span>
                            <span>{{ $raBill->project->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">GST Number:</span>
                            <span>{{ $raBill->customer->gst ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">PAN Card Number:</span>
                            <span>{{ $raBill->customer->pan_card ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Summary</h3>
                    <div class="ra-bill-info">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">R.A. Bill Amount:</span>
                            <span>₹{{ number_format($raBill->ra_bill_amount, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Total GST:</span>
                            <span>₹{{ number_format($raBill->sgst_9_percent + $raBill->cgst_9_percent, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Total Deductions:</span>
                            <span>₹{{ number_format($raBill->total_deductions, 0) }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="font-bold text-gray-900">Net Amount:</span>
                            <span>₹{{ number_format($raBill->net_amount, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Calculation -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden mt-3">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Detailed Calculation</h3>
                </div>
                <div class="detail-calculation p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">(A) R.A. Bill Amount</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right font-semibold text-blue-600">₹{{ number_format($raBill->ra_bill_amount, 0) }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">(B) Department Taxes & Overheads</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->dept_taxes_overheads, 0) }}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-bold text-gray-900">(C) Total (A - B)</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right font-bold text-purple-600">₹{{ number_format($raBill->total_c, 0) }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">SGST @ 9%</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-green-600">₹{{ number_format($raBill->sgst_9_percent, 0) }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">CGST @ 9%</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-green-600">₹{{ number_format($raBill->cgst_9_percent, 0) }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">IGST @ 0%</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-500">₹0</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-bold text-gray-900">(D) Total With GST</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right font-bold text-blue-600">₹{{ number_format($raBill->total_with_gst, 0) }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-bold text-gray-900" colspan="2">(E) Deductions</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">TDS 1%</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->tds_1_percent, 0) }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">RMD</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->rmd_amount, 0) }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Welfare Cess</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->welfare_cess, 0) }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Testing Charges</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->testing_charges, 0) }}</td>
                            </tr>
                            <tr class="bg-red-50">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-bold text-gray-900">Total Deductions</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-right font-bold text-red-600">₹{{ number_format($raBill->total_deductions, 0) }}</td>
                            </tr>
                            <tr class="bg-green-100 border-t-2 border-green-300">
                                <td class="px-3 py-2 whitespace-nowrap text-lg font-bold text-gray-900">Net Amount </td>
                                <td class="px-3 py-2 whitespace-nowrap text-lg text-right font-bold text-green-700">₹{{ number_format($raBill->net_amount, 0) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>