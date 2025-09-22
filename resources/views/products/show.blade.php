<x-app-layout>
    <x-slot name="header">
        <div class="module-edit-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product Details: ') . $product->product_name }}
            </h2>
            <div>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row mb-4">
        <div class="col-lg-12">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Product Name:</strong></td>
                            <td>{{ $product->product_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>HSN Code:</strong></td>
                            <td>{{ $product->hsn_code ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date Added:</strong></td>
                            <td>{{ $product->date->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>