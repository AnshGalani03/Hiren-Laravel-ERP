<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('incomings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Incoming Entry') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('incomings.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Incoming Name <span class="text-danger">*</span></label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('name') is-invalid @enderror"
                        id="name" name="name" value="{{ old('name') }}" required
                        placeholder="e.g., Payment Received, Advance, Bonus">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('incomings.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Incoming Entry</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>