<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <a class="btn btn-outline-secondary" href="{{ route('bills.index') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Bill: ') . $bill->bill_number }}
            </h2>
        </div>
    </x-slot>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('bills.update', $bill) }}" method="POST" id="billForm">
                @csrf
                @method('PUT')

                <!-- Bill Number and Date Row -->
                <div class="row bill-number-and-date">
                    <div class="col-md-6">
                        <label for="bill_number" class="form-label">Bill Number</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="bill_number" name="bill_number"
                            value="{{ old('bill_number', $bill->bill_number) }}" readonly
                            style="background-color: #f8f9fa; cursor: not-allowed;">
                        <small class="text-muted">Bill number cannot be changed</small>
                    </div>
                    <div class="col-md-6">
                        <label for="bill_date" class="form-label">Bill Date <span class="text-danger">*</span></label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="bill_date" name="bill_date"
                            value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <!-- Customer Row -->
                <div class="row customer-row">
                    <div class="col-md-6">
                        <div class="bill-customer-list">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ (old('customer_id', $bill->customer_id) == $customer->id) ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Status and Notes Row -->
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="status" name="status" required>
                            <option value="draft" {{ old('status', $bill->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent" {{ old('status', $bill->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="paid" {{ old('status', $bill->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                </div>

                <!-- GST Checkbox and Rate Row -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_gst" name="is_gst"
                                value="1" {{ old('is_gst', $bill->is_gst) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_gst">
                                <i class="fas fa-receipt text-primary"></i> This is a GST Bill
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6" id="gst_rate_section" style="{{ old('is_gst', $bill->is_gst) ? 'display: block;' : 'display: none;' }}">
                        <label for="tax_rate" class="form-label">GST Rate (%) <span class="text-danger">*</span></label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="tax_rate" name="tax_rate"
                            min="0" max="100" step="0.01"
                            value="{{ old('tax_rate', $bill->tax_rate) }}"
                            placeholder="e.g., 18">
                        <small class="text-muted">Common rates: 5%, 12%, 18%, 28%</small>
                    </div>
                </div>

                <!-- Bill Items Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Bill Items</h5>
                        <button type="button" class="btn btn-success btn-sm" id="addItem">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="billItems">
                            <!-- Existing items will be loaded here -->
                            @foreach($bill->billItems as $index => $item)
                            <div class="row bill-item mb-3" data-index="{{ $index }}">
                                <div class="col-md-4">
                                    <div class="product-list">
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control product-select product-lists" name="items[{{ $index }}][product_id]" required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control quantity" name="items[{{ $index }}][quantity]"
                                        value="{{ $item->quantity }}" placeholder="Qty" min="1" step="1" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control unit-price" name="items[{{ $index }}][unit_price]"
                                        value="{{ $item->unit_price }}" placeholder="Price" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control item-total" readonly
                                        value="{{ number_format($item->total_price, 2) }}" placeholder="Total">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Totals Display -->
                        <div class="row mt-3">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end"><span id="subtotal">₹{{ number_format($bill->subtotal, 2) }}</span></td>
                                    </tr>
                                    <tr id="gst_row" style="{{ $bill->is_gst ? 'display: table-row;' : 'display: none;' }}">
                                        <td><strong>GST (<span id="gst_percent">{{ $bill->tax_rate ?? 18 }}</span>%):</strong></td>
                                        <td class="text-end"><span id="gst_amount">₹{{ number_format($bill->tax_amount, 2) }}</span></td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Total:</strong></td>
                                        <td class="text-end"><strong><span id="total">₹{{ number_format($bill->total_amount, 2) }}</span></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Notes -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                            placeholder="Additional notes or comments">{{ old('notes', $bill->notes) }}</textarea>
                    </div>
                </div>

                <!-- Hidden Fields for Totals -->
                <input type="hidden" id="subtotal_input" name="subtotal" value="{{ $bill->subtotal }}">
                <input type="hidden" id="tax_amount_input" name="tax_amount" value="{{ $bill->tax_amount }}">
                <input type="hidden" id="total_input" name="total_amount" value="{{ $bill->total_amount }}">

                <!-- Submit Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('bills.index') }}" class="btn btn-secondary btn-sm">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        Update Bill
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            let itemIndex = {{count($bill -> billItems)}};

            // Toggle GST Rate field based on GST checkbox
            $('#is_gst').change(function() {
                if ($(this).is(':checked')) {
                    $('#gst_rate_section').show();
                    $('#gst_row').show();
                    $('#tax_rate').attr('required', true);
                } else {
                    $('#gst_rate_section').hide();
                    $('#gst_row').hide();
                    $('#tax_rate').attr('required', false);
                    $('#tax_rate').val('0');
                }
                calculateTotals();
            });

            // Update GST percentage display when rate changes
            $('#tax_rate').on('input', function() {
                $('#gst_percent').text($(this).val() || 0);
                calculateTotals();
            });

            // Add Item functionality
            $('#addItem').click(function() {
                addBillItem();
            });

            function addBillItem() {
                const itemHtml = `
                    <div class="row bill-item mb-3" data-index="${itemIndex}">
                        <div class="col-md-4">
                        <div class="product-list">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control product-select product-lists" name="items[${itemIndex}][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control quantity" name="items[${itemIndex}][quantity]" 
                                   placeholder="Qty" min="1" step="1" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control unit-price" name="items[${itemIndex}][unit_price]" 
                                   placeholder="Price" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control item-total" readonly placeholder="Total">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#billItems').append(itemHtml);
                // Initialize Select2 on all select elements that haven't been initialized yet
                $('.product-select:not(.select2-hidden-accessible)').select2({
                    placeholder: "Select Product",
                    allowClear: true,
                    width: '100%'
                });
                itemIndex++;
            }

            // Remove Item
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.bill-item').remove();
                calculateTotals();
            });

            // Calculate item total and grand totals
            $(document).on('input', '.quantity, .unit-price', function() {
                const row = $(this).closest('.bill-item');
                const quantity = parseFloat(row.find('.quantity').val()) || 0;
                const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
                const itemTotal = quantity * unitPrice;

                row.find('.item-total').val(itemTotal.toFixed(2));
                calculateTotals();
            });

            function calculateTotals() {
                let subtotal = 0;

                $('.bill-item').each(function() {
                    const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
                    subtotal += quantity * unitPrice;
                });

                const isGst = $('#is_gst').is(':checked');
                const taxRate = parseFloat($('#tax_rate').val()) || 0;
                const taxAmount = isGst ? (subtotal * taxRate) / 100 : 0;
                const total = subtotal + taxAmount;

                // Update display
                $('#subtotal').text('₹' + subtotal.toFixed(2));
                $('#gst_amount').text('₹' + taxAmount.toFixed(2));
                $('#total').text('₹' + total.toFixed(2));

                // Update hidden fields
                $('#subtotal_input').val(subtotal);
                $('#tax_amount_input').val(taxAmount);
                $('#total_input').val(total);
            }

            // Initial calculation
            calculateTotals();

            $(".product-lists").select2();
        });
    </script>
    @endpush
</x-app-layout>