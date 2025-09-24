<x-app-layout>
    <x-slot name="header">
        <div class="module-edit-page d-flex justify-content-between align-items-center">
            <a class="btn btn-outline-secondary" href="{{ route('customers.index') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Customer: ') . $customer->name }}
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
        <div class="customer-add-form p-6 text-gray-900">
            <form action="{{ route('customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="name" name="name"
                            value="{{ old('name', $customer->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone_no" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="phone_no" name="phone_no"
                            value="{{ old('phone_no', $customer->phone_no) }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="gst" class="form-label">GST Number</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="gst" name="gst"
                            value="{{ old('gst', $customer->gst) }}" placeholder="Optional">
                    </div>
                    <div class="col-md-6">
                        <label for="pan_card" class="form-label">PAN Card Number</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control"
                            id="pan_card" name="pan_card" value="{{ old('pan_card', $customer->pan_card) }}"
                            placeholder="e.g., AAAAA0000A" maxlength="10" style="text-transform: uppercase;">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="address" name="address" rows="4"
                            required>{{ old('address', $customer->address) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- PAN Card Auto-formatting Script -->
    <script>
        document.getElementById('pan_card').addEventListener('input', function(e) {
            // Auto uppercase PAN card input
            e.target.value = e.target.value.toUpperCase();
        });
    </script>
</x-app-layout>