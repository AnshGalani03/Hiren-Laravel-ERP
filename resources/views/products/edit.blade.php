<x-app-layout>
    <x-slot name="header">
        <div class="module-edit-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Product: ') . $product->product_name }}
            </h2>
            <a class="btn btn-secondary" href="{{ route('products.index') }}">
                <i class="fas fa-arrow-left"></i> Back to List
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
            <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Product Information</h5>
        </div>
        <div class="p-6 text-gray-900">
            <form action="{{ route('products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="product-edit-form">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                id="product_name" name="product_name"
                                value="{{ old('product_name', $product->product_name) }}" required>
                            @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="hsn_code" class="form-label">HSN Code</label>
                            <input type="text" class="form-control @error('hsn_code') is-invalid @enderror"
                                id="hsn_code" name="hsn_code"
                                value="{{ old('hsn_code', $product->hsn_code) }}"
                                placeholder="e.g., 1234567890">
                            <small class="text-muted">HSN (Harmonized System of Nomenclature) code for tax classification</small>
                            @error('hsn_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>