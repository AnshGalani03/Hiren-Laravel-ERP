<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                R.A. Bill Details - {{ $raBill->bill_no }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('ra-bills.edit', $raBill) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('ra-bills.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Bill Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bill Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Bill No:</span>
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm font-semibold">{{ $raBill->bill_no }}</span>
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
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">{{ $raBill->project->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Summary</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">R.A. Bill Amount:</span>
                                    <span class="text-blue-600 font-semibold">₹{{ number_format($raBill->ra_bill_amount, 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Total GST:</span>
                                    <span class="text-green-600">₹{{ number_format($raBill->sgst_9_percent + $raBill->cgst_9_percent, 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Total Deductions:</span>
                                    <span class="text-red-600">₹{{ number_format($raBill->total_deductions, 0) }}</span>
                                </div>
                                <div class="flex justify-between border-t pt-3">
                                    <span class="font-bold text-gray-900">Net Amount:</span>
                                    <span class="text-green-700 font-bold text-lg">₹{{ number_format($raBill->net_amount, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Calculation -->
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Detailed Calculation</h3>
                        </div>
                        <div class="p-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">(A) R.A. Bill Amount</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-blue-600">₹{{ number_format($raBill->ra_bill_amount, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">(B) Department Taxes & Overheads</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->dept_taxes_overheads, 0) }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">(C) Total (A - B)</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-purple-600">₹{{ number_format($raBill->total_c, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SGST @ 9%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">₹{{ number_format($raBill->sgst_9_percent, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">CGST @ 9%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">₹{{ number_format($raBill->cgst_9_percent, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">IGST @ 0%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">₹0</td>
                                    </tr>
                                    <tr class="bg-blue-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">(D) Total With GST</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-blue-600">₹{{ number_format($raBill->total_with_gst, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900" colspan="2">(E) Deductions</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TDS 1%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->tds_1_percent, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RMD</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->rmd_amount, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Welfare Cess</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->welfare_cess, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Testing Charges</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">₹{{ number_format($raBill->testing_charges, 0) }}</td>
                                    </tr>
                                    <tr class="bg-red-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Total Deductions</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-red-600">₹{{ number_format($raBill->total_deductions, 0) }}</td>
                                    </tr>
                                    <tr class="bg-green-100 border-t-2 border-green-300">
                                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-900">NET AMOUNT</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-lg text-right font-bold text-green-700">₹{{ number_format($raBill->net_amount, 0) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('ra-bills.edit', $raBill) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded">
                            <i class="fas fa-edit mr-2"></i>Edit R.A. Bill
                        </a>
                        <form action="{{ route('ra-bills.destroy', $raBill) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this R.A. Bill?')"
                                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
