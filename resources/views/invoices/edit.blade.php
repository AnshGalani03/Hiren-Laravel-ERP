<x-app-layout>
    <x-slot name="header">
        <div class="module-edit-page d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Invoice: ') . $invoice->bill_no }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">

            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PATCH')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="bill_no" class="form-label">Bill No <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('bill_no') is-invalid @enderror"
                            id="bill_no" name="bill_no"
                            value="{{ old('bill_no', $invoice->bill_no) }}" required>
                        @error('bill_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('date') is-invalid @enderror"
                            id="date" name="date"
                            value="{{ old('date', $invoice->date?->format('Y-m-d')) }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="original_amount" class="form-label">Total Amount <span class="text-danger">*</span></label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('original_amount') is-invalid @enderror"
                            id="original_amount" name="original_amount"
                            value="{{ old('original_amount', $invoice->original_amount) }}"
                            step="0.01" min="0" max="999999999.99" required>
                        @error('original_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Enter the total invoice amount</small>
                    </div>

                    <div class="col-md-6">
                        <label for="gst_rate" class="form-label">GST Rate (%)</label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('gst_rate') is-invalid @enderror"
                            id="gst_rate" name="gst_rate"
                            value="{{ old('gst_rate', $invoice->gst_rate) }}"
                            step="0.01" min="0" max="100">
                        @error('gst_rate')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Current: {{ $invoice->gst_rate }}%</small>
                    </div>
                </div>

                <!-- GST Amount Display -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">GST Amount (Calculated)</label>
                        <div class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control bg-light" id="gst_display">₹{{ number_format($invoice->amount, 2) }}</div>
                        <small class="text-success">This amount will be saved to database</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Current GST Amount</label>
                        <div class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control bg-secondary text-white">₹{{ number_format($invoice->amount, 2) }}</div>
                        <small class="text-muted">Currently saved in database</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('remark') is-invalid @enderror"
                            id="remark" name="remark"
                            rows="3" placeholder="Enter any remarks...">{{ old('remark', $invoice->remark) }}</textarea>
                        @error('remark')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dealers.show', $invoice->dealer_id) }}" class="btn btn-secondary btn-sm ms-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                Update Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const originalAmountInput = document.getElementById('original_amount');
            const gstRateInput = document.getElementById('gst_rate');
            const gstDisplay = document.getElementById('gst_display');

            function calculateGST() {
                const originalAmount = parseFloat(originalAmountInput.value) || 0;
                const gstRate = parseFloat(gstRateInput.value) || 18.0;
                const gstAmount = (originalAmount * gstRate) / 100;

                gstDisplay.textContent = '₹' + new Intl.NumberFormat('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(gstAmount);
            }

            originalAmountInput.addEventListener('input', calculateGST);
            gstRateInput.addEventListener('input', calculateGST);

            // Initial calculation
            calculateGST();
        });
    </script>
</x-app-layout>