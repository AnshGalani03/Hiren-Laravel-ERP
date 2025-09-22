<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Upad for: ') . $employee->name }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('upads.store') }}">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('date') is-invalid @enderror"
                            id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('salary') is-invalid @enderror"
                            id="salary" name="salary" value="{{ old('salary', $employee->salary) }}" required>
                        @error('salary')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="upad" class="form-label">Upad Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('upad') is-invalid @enderror"
                            id="upad" name="upad" value="{{ old('upad') }}" required>
                        @error('upad')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('remark') is-invalid @enderror"
                            id="remark" name="remark" rows="3">{{ old('remark') }}</textarea>
                        @error('remark')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Upad</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
