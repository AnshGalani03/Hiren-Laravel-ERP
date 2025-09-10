<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Product') }}
            </h2>
            <a class="btn btn-secondary" href="{{ route('products.index') }}">
                <i class="fas fa-arrow-left"></i> Back to Products
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
            <h5 class="mb-0">Product Information</h5>
        </div>
        <div class="p-6 text-gray-900">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="product_name" class="form-label">
                        Product Name <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                        class="form-control @error('product_name') is-invalid @enderror"
                        id="product_name"
                        name="product_name"
                        value="{{ old('product_name') }}"
                        placeholder="Enter product name"
                        required>
                    @error('product_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Date Added</label>
                    <input type="text"
                        class="form-control"
                        value="{{ date('d/m/Y') }}"
                        readonly>
                    <small class="text-muted">Date will be automatically set to today's date.</small>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>