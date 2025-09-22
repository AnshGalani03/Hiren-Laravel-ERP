<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Project Expense') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('project-expenses.update', $projectExpense) }}">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="outgoing_id" class="form-label">Expense Type <span class="text-danger">*</span></label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('outgoing_id') is-invalid @enderror" id="outgoing_id" name="outgoing_id" required>
                            <option value="">Select Expense Type</option>
                            @foreach($outgoings as $outgoing)
                            <option value="{{ $outgoing->id }}"
                                {{ old('outgoing_id', $projectExpense->outgoing_id) == $outgoing->id ? 'selected' : '' }}>
                                {{ $outgoing->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('outgoing_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('amount') is-invalid @enderror"
                            id="amount" name="amount" value="{{ old('amount', $projectExpense->amount) }}" required>
                        @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('date') is-invalid @enderror"
                            id="date" name="date" value="{{ old('date', $projectExpense->date->format('Y-m-d')) }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('remark') is-invalid @enderror"
                        id="remark" name="remark" rows="3">{{ old('remark', $projectExpense->remark) }}</textarea>
                    @error('remark')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('projects.show', $projectExpense->project) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Expense</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>