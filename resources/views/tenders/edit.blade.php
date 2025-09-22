<x-app-layout>
    <x-slot name="header">
        <div class="module-edit-page d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('tenders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Tender: ') . $tender->work_name }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('tenders.update', $tender) }}">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="work_name" class="form-label">Work Name <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('work_name') is-invalid @enderror"
                            id="work_name" name="work_name" value="{{ old('work_name', $tender->work_name) }}" required>
                        @error('work_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('department') is-invalid @enderror"
                            id="department" name="department" value="{{ old('department', $tender->department) }}" required>
                        @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="amount_emd_fdr" class="form-label">EMD/FDR Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('amount_emd_fdr') is-invalid @enderror"
                            id="amount_emd_fdr" name="amount_emd_fdr" value="{{ old('amount_emd_fdr', $tender->amount_emd_fdr) }}" required>
                        @error('amount_emd_fdr')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="amount_dd" class="form-label">DD Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('amount_dd') is-invalid @enderror"
                            id="amount_dd" name="amount_dd" value="{{ old('amount_dd', $tender->amount_dd) }}" required>
                        @error('amount_dd')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="above_below" class="form-label">Above/Below <span class="text-danger">*</span></label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('above_below') is-invalid @enderror" id="above_below" name="above_below" required>
                            <option value="">Select</option>
                            <option value="Above" {{ old('above_below', $tender->above_below) == 'Above' ? 'selected' : '' }}>Above</option>
                            <option value="Below" {{ old('above_below', $tender->above_below) == 'Below' ? 'selected' : '' }}>Below</option>
                        </select>
                        @error('above_below')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('date') is-invalid @enderror"
                            id="date" name="date" value="{{ old('date', $tender->date->format('Y-m-d')) }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="result" class="form-label">Result</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('result') is-invalid @enderror" id="result" name="result">
                            <option value="">Select Result</option>
                            <option value="Won" {{ old('result', $tender->result) == 'Won' ? 'selected' : '' }}>Won</option>
                            <option value="Lost" {{ old('result', $tender->result) == 'Lost' ? 'selected' : '' }}>Lost</option>
                            <option value="Pending" {{ old('result', $tender->result) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('result')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('remark') is-invalid @enderror"
                        id="remark" name="remark" rows="3">{{ old('remark', $tender->remark) }}</textarea>
                    @error('remark')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="return_detail" class="form-label">Return Detail</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('return_detail') is-invalid @enderror"
                        id="return_detail" name="return_detail" rows="3">{{ old('return_detail', $tender->return_detail) }}</textarea>
                    @error('return_detail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('tenders.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Tender</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>