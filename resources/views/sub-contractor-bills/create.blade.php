<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('sub-contractors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Bill for: ') . $subContractor->contractor_name }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('sub-contractor-bills.store') }}">
                @csrf
                <input type="hidden" name="sub_contractor_id" value="{{ $subContractor->id }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bill_no" class="form-label">Bill Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('bill_no') is-invalid @enderror"
                            id="bill_no" name="bill_no" value="{{ old('bill_no') }}" required
                            placeholder="e.g., BILL-001, INV-2025-001">
                        @error('bill_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Bill Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                            id="date" name="date" value="{{ old('date') }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="amount" class="form-label">Bill Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                            id="amount" name="amount" value="{{ old('amount') }}" required>
                        @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="form-control @error('remark') is-invalid @enderror"
                        id="remark" name="remark" rows="3"
                        placeholder="Enter bill description, work completed, etc.">{{ old('remark') }}</textarea>
                    @error('remark')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Sub-Contractor Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Contractor:</strong> {{ $subContractor->contractor_name }}</p>
                                <p><strong>Project:</strong> {{ $subContractor->project_name }}</p>
                                <p><strong>Department:</strong> {{ $subContractor->department_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Project Amount:</strong> ₹{{ number_format($subContractor->amount_project, 2) }}</p>
                                <p><strong>Previous Bills:</strong> ₹{{ number_format($subContractor->total_bill_amount, 2) }}</p>
                                <p><strong>Remaining:</strong> ₹{{ number_format($subContractor->amount_project - $subContractor->total_bill_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('sub-contractors.show', $subContractor) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Bill</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>