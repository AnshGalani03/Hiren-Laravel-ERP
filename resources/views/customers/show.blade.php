<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customer Details: ') . $customer->name }}
            </h2>
            <div>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>

            </div>
        </div>
    </x-slot>
    <div class="row mb-4">
        <div class="col-lg-12">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Customer Name:</strong></td>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phone Number:</strong></td>
                            <td>{{ $customer->phone_no }}</td>
                        </tr>
                        <tr>
                            <td><strong>GST Number:</strong></td>
                            <td>{{ $customer->gst ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>PAN Card:</strong></td>
                            <td>{{ $customer->pan_card ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Address:</strong></td>
                            <td>{{ $customer->address }}</td>
                        </tr>
                        <!-- <tr>
                            <td><strong>Created Date:</strong></td>
                            <td>{{ $customer->created_at->format('d/m/Y H:i A') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Last Updated:</strong></td>
                            <td>{{ $customer->updated_at->format('d/m/Y H:i A') }}</td>
                        </tr> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>