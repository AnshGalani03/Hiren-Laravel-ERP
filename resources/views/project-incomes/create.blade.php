<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Income to Project: ') . $project->name }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('project-incomes.store') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="incoming_id" class="form-label">Income Type <span class="text-danger">*</span></label>
                        <select class="form-control @error('incoming_id') is-invalid @enderror" id="incoming_id" name="incoming_id" required>
                            <option value="">Select Income Type</option>
                            @foreach($incomings as $incoming)
                            <option value="{{ $incoming->id }}" {{ old('incoming_id') == $incoming->id ? 'selected' : '' }}>
                                {{ $incoming->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('incoming_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                            id="amount" name="amount" value="{{ old('amount') }}" required>
                        @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                            id="date" name="date" value="{{ old('date') }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="form-control @error('remark') is-invalid @enderror"
                        id="remark" name="remark" rows="3">{{ old('remark') }}</textarea>
                    @error('remark')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Income</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>