<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Upad for: ') . $upad->employee->name }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('upads.update', $upad) }}">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="month" class="form-label">Month <span class="text-danger">*</span></label>
                        <select class="form-control @error('month') is-invalid @enderror" id="month" name="month" required>
                            <option value="">Select Month</option>
                            <option value="January" {{ old('month', $upad->month) == 'January' ? 'selected' : '' }}>January</option>
                            <option value="February" {{ old('month', $upad->month) == 'February' ? 'selected' : '' }}>February</option>
                            <option value="March" {{ old('month', $upad->month) == 'March' ? 'selected' : '' }}>March</option>
                            <option value="April" {{ old('month', $upad->month) == 'April' ? 'selected' : '' }}>April</option>
                            <option value="May" {{ old('month', $upad->month) == 'May' ? 'selected' : '' }}>May</option>
                            <option value="June" {{ old('month', $upad->month) == 'June' ? 'selected' : '' }}>June</option>
                            <option value="July" {{ old('month', $upad->month) == 'July' ? 'selected' : '' }}>July</option>
                            <option value="August" {{ old('month', $upad->month) == 'August' ? 'selected' : '' }}>August</option>
                            <option value="September" {{ old('month', $upad->month) == 'September' ? 'selected' : '' }}>September</option>
                            <option value="October" {{ old('month', $upad->month) == 'October' ? 'selected' : '' }}>October</option>
                            <option value="November" {{ old('month', $upad->month) == 'November' ? 'selected' : '' }}>November</option>
                            <option value="December" {{ old('month', $upad->month) == 'December' ? 'selected' : '' }}>December</option>
                        </select>
                        @error('month')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                            id="date" name="date" value="{{ old('date', $upad->date->format('Y-m-d')) }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror"
                            id="salary" name="salary" value="{{ old('salary', $upad->salary) }}" required>
                        @error('salary')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="upad" class="form-label">Upad Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('upad') is-invalid @enderror"
                            id="upad" name="upad" value="{{ old('upad', $upad->upad) }}" required>
                        @error('upad')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pending" class="form-label">Pending Amount</label>
                        <input type="number" step="0.01" class="form-control @error('pending') is-invalid @enderror"
                            id="pending" name="pending" value="{{ old('pending', $upad->pending) }}">
                        @error('pending')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="form-control @error('remark') is-invalid @enderror"
                        id="remark" name="remark" rows="3">{{ old('remark', $upad->remark) }}</textarea>
                    @error('remark')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('employees.show', $upad->employee) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Upad</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>