<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Customer') }}
            </h2>
            <a class="btn btn-secondary" href="{{ route('customers.index') }}">
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
            <h5 class="mb-0"><i class="fas fa-user-plus"></i> Customer Information</h5>
        </div>
        <div class="p-6 text-gray-900">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone_no" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone_no" name="phone_no" 
                               value="{{ old('phone_no') }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="gst" class="form-label">GST Number</label>
                        <input type="text" class="form-control" id="gst" name="gst" 
                               value="{{ old('gst') }}" placeholder="Optional">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="address" name="address" rows="4" 
                                  required>{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
