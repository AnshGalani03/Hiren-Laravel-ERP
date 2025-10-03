<x-app-layout>
    <x-slot name="header">
        <div class="sub-contractors-header d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('sub-contractors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Sub-Contractor') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">

            <form action="{{ route('sub-contractors.store') }}" method="POST">
                @csrf

                <div class="row gy-2 mb-2">
                    <div class="col-md-6">
                        <label for="contractor_name" class="form-label">Contractor Name <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('contractor_name') is-invalid @enderror"
                            id="contractor_name" name="contractor_name"
                            value="{{ old('contractor_name') }}" required>
                        @error('contractor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="contractor_type" class="form-label">Contractor Type <span class="text-danger">*</span></label>
                        <select name="contractor_type" id="contractor_type"
                            class="form-control @error('contractor_type') is-invalid @enderror" required>
                            <option value="">Select Contractor Type</option>
                            <option value="self" {{ old('contractor_type') == 'self' ? 'selected' : '' }}>Self</option>
                            <option value="agency" {{ old('contractor_type') == 'agency' ? 'selected' : '' }}>Agency</option>
                        </select>
                        @error('contractor_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Third Party Name Row (conditional) -->
                <div class="row gy-2 mb-2" id="third_party_row">
                    <div class="col-md-12">
                        <label for="agency_name" class="form-label">Agency Name <span class="text-danger">*</span></label>
                        <input type="text" name="agency_name" id="agency_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('agency_name') is-invalid @enderror"
                            value="{{ old('agency_name') }}" required
                            placeholder="Agency Name">
                        @error('agency_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row gy-2 mb-2">
                    <div class="col-md-6">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('date') is-invalid @enderror"
                            id="date" name="date"
                            value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('project_name') is-invalid @enderror"
                            id="project_name" name="project_name"
                            value="{{ old('project_name') }}" required>
                        @error('project_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row gy-2 mb-2">
                    <div class="col-md-6">
                        <label for="department_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('department_name') is-invalid @enderror"
                            id="department_name" name="department_name"
                            value="{{ old('department_name') }}" required>
                        @error('department_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="amount_project" class="form-label">Project Amount (₹) <span class="text-danger">*</span></label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('amount_project') is-invalid @enderror"
                            id="amount_project" name="amount_project"
                            value="{{ old('amount_project') }}"
                            step="0.01" min="0" max="999999999.99" required>
                        @error('amount_project')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row gy-2 mb-2">
                    <div class="col-md-6">
                        <label for="time_limit" class="form-label">Time Limit <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('time_limit') is-invalid @enderror"
                            id="time_limit" name="time_limit"
                            value="{{ old('time_limit') }}"
                            placeholder="e.g., 6 months" required>
                        @error('time_limit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="work_order_date" class="form-label">Work Order Date</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('work_order_date') is-invalid @enderror"
                            id="work_order_date" name="work_order_date"
                            value="{{ old('work_order_date') }}">
                        @error('work_order_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row gy-2 mb-2">
                    <div class="col-md-12">
                        <label for="emd_fdr_detail" class="form-label">EMD/FDR Detail</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('emd_fdr_detail') is-invalid @enderror"
                            id="emd_fdr_detail" name="emd_fdr_detail"
                            rows="3" placeholder="Enter EMD/FDR details...">{{ old('emd_fdr_detail') }}</textarea>
                        @error('emd_fdr_detail')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row gy-2 mb-2">
                    <div class="col-md-12">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('remark') is-invalid @enderror"
                            id="remark" name="remark"
                            rows="3" placeholder="Enter any remarks...">{{ old('remark') }}</textarea>
                        @error('remark')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('sub-contractors.index') }}" class="btn btn-secondary btn-sm ms-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                Create Sub-Contractor
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>