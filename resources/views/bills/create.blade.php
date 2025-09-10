<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Bill') }}
            </h2>
            <a class="btn btn-secondary" href="{{ route('bills.index') }}">
                <i class="fas fa-arrow-left"></i> Back to Bills
            </a>
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
        <div class="card-header">
            <h5 class="mb-0">Bill Information</h5>
        </div>
        <div class="p-6 text-gray-900">
            <form action="{{ route('bills.store') }}" method="POST" id="billForm">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="dealer_id" class="form-label">Dealer <span class="text-danger">*</span></label>
                        <select class="form-control" id="dealer_id" name="dealer_id" required>
                            <option value="">Select Dealer</option>
                            @foreach($dealers as $dealer)
                            <option value="{{ $dealer->id }}">{{ $dealer->dealer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="bill_date" class="form-label">Bill Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="bill_date" name="bill_date"
                            value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <!-- Product Items -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h6>Products</h6>
                        <button type="button" class="btn btn-success btn-sm" id="addItem">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="itemsContainer">
                            <!-- Items will be added here -->
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tax_rate" class="form-label">GST Rate (%)</label>
                        <input type="number" class="form-control" id="tax_rate" name="tax_rate"
                            min="0" max="100" step="0.01" placeholder="18">
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="draft" {{ old('status', $bill->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent" {{ old('status', $bill->status ?? '') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="paid" {{ old('status', $bill->status ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                </div>
                <div class="row mt-3 mb-3">
                    <div class="col-md-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                            placeholder="Additional notes..."></textarea>
                    </div>
                </div>

                <!-- Total Display -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end"><span id="subtotalDisplay">₹0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>GST:</strong></td>
                                        <td class="text-end"><span id="taxDisplay">₹0.00</span></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong>Total:</strong></td>
                                        <td class="text-end"><strong><span id="totalDisplay">₹0.00</span></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('bills.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Create Bill
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let itemIndex = 0;
        const products = @json($products);
        $('#addItem').click(function() {
            addItemRow();
        });

        function addItemRow() {
            const productOptions = products.map(product =>
                `<option value="${product.id}">${product.product_name}</option>`
            ).join('');

            const itemHtml = `
                <div class="row mb-3 item-row" data-index="${itemIndex}">
                    <div class="col-md-4">
                        <select class="form-control product-select" name="items[${itemIndex}][product_id]" required>
                            <option value="">Select Product</option>
                            ${productOptions}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control quantity-input" name="items[${itemIndex}][quantity]" 
                               placeholder="Qty" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control price-input" name="items[${itemIndex}][unit_price]" 
                               placeholder="Unit Price" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control total-input" readonly placeholder="Total">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#itemsContainer').append(itemHtml);
            itemIndex++;
        }

        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            calculateTotal();
        });

        $(document).on('input', '.quantity-input, .price-input, #tax_rate', function() {
            const row = $(this).closest('.item-row');
            const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
            const price = parseFloat(row.find('.price-input').val()) || 0;
            const total = quantity * price;

            row.find('.total-input').val('₹' + total.toFixed(2));
            calculateTotal();
        });

        function calculateTotal() {
            let subtotal = 0;

            $('.item-row').each(function() {
                const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
                const price = parseFloat($(this).find('.price-input').val()) || 0;
                subtotal += quantity * price;
            });

            const taxRate = parseFloat($('#tax_rate').val()) || 0;
            const taxAmount = (subtotal * taxRate) / 100;
            const total = subtotal + taxAmount;

            $('#subtotalDisplay').text('₹' + subtotal.toFixed(2));
            $('#taxDisplay').text('₹' + taxAmount.toFixed(2));
            $('#totalDisplay').text('₹' + total.toFixed(2));
        }

        // Add first item row on page load
        $(document).ready(function() {
            addItemRow();
        });
    </script>
    @endpush
</x-app-layout>