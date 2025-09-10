<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a class="btn btn-secondary btn-sm" href="{{ route('products.index') }}">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header">
            <h5 class="mb-0">Product Information</h5>
        </div>
        <div class="p-6 text-gray-900">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Product ID:</strong></td>
                            <td>{{ $product->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Product Name:</strong></td>
                            <td>{{ $product->product_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date Added:</strong></td>
                            <td>{{ $product->date->format('d/m/Y') }}</td>
                        </tr>
                        <!-- <tr>
                            <td><strong>Created At:</strong></td>
                            <td>{{ $product->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Last Updated:</strong></td>
                            <td>{{ $product->updated_at->format('d/m/Y H:i:s') }}</td>
                        </tr> -->
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <div class="btn-group gap-2" role="group">
                    <a class="btn btn-warning btn-sm" href="{{ route('products.edit', $product->id) }}">
                        <i class="fas fa-edit"></i> Edit Product
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this product?')">
                            <i class="fas fa-trash"></i> Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>