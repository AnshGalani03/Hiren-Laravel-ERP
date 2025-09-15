<x-app-layout>
    <x-slot name="header">
        <div class="bill-detail-header d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bill Details: ') . $bill->bill_number }}
            </h2>
            <div>
                <a href="{{ route('bills.pdf', $bill->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-pdf"></i> Generate PDF
                </a>

                <a class="btn btn-warning btn-sm me-2" href="{{ route('bills.edit', $bill->id) }}">
                    <i class="fas fa-edit"></i> Edit
                </a>

            </div>
        </div>
    </x-slot>

    <!-- Bill Information -->

    <div class="row mb-4">
        <div class="col-lg-12">
            <a class="btn btn-secondary btn-sm" href="{{ route('bills.index') }}">
                <i class="fas fa-arrow-left"></i> Back to Bills
            </a>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
        <div class="card-header">
            <h5 class="mb-0">Bill Information</h5>
        </div>
        <div class="p-6 text-gray-900">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Bill Number:</strong></td>
                            <td>{{ $bill->bill_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Customer:</strong></td>
                            <td>{{ $bill->customer->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Bill Date:</strong></td>
                            <td>{{ $bill->bill_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($bill->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Subtotal:</strong></td>
                            <td>₹{{ number_format($bill->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>GST ({{ $bill->tax_rate }}%):</strong></td>
                            <td>₹{{ number_format($bill->tax_amount, 2) }}</td>
                        </tr>
                        <tr class="table-warning">
                            <td><strong>Total Amount:</strong></td>
                            <td><strong>₹{{ number_format($bill->total_amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>GST Bill:</strong></td>
                            <td>{{ $bill->is_gst ? 'Yes' : 'No' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($bill->notes)
            <div class="mt-3">
                <strong>Notes:</strong>
                <p class="text-muted">{{ $bill->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Bill Items -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header">
            <h5 class="mb-0">Bill Items</h5>
        </div>
        <div class="p-6 text-gray-900">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Sr. No</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill->billItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->unit_price, 2) }}</td>
                            <td>₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                            <td><strong>₹{{ number_format($bill->subtotal, 2) }}</strong></td>
                        </tr>
                        @if($bill->tax_rate > 0)
                        <tr>
                            <td colspan="4" class="text-end"><strong>GST ({{ $bill->tax_rate }}%):</strong></td>
                            <td><strong>₹{{ number_format($bill->tax_amount, 2) }}</strong></td>
                        </tr>
                        @endif
                        <tr class="table-warning">
                            <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                            <td><strong>₹{{ number_format($bill->total_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>