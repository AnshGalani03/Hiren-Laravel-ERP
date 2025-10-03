<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Project') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('projects.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('department_name') is-invalid @enderror"
                            id="department_name" name="department_name" value="{{ old('department_name') }}" required>
                        @error('department_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="work_order_date" class="form-label">Work Order Date</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('work_order_date') is-invalid @enderror"
                            id="work_order_date" name="work_order_date" value="{{ old('work_order_date') }}">
                        @error('work_order_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount_project" class="form-label">Project Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('amount_project') is-invalid @enderror"
                            id="amount_project" name="amount_project" value="{{ old('amount_project') }}" required>
                        @error('amount_project')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="percentage" class="form-label">Percentage <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('percentage') is-invalid @enderror"
                            id="percentage" name="percentage" value="{{ old('percentage') }}" required>
                        @error('percentage')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="final_project_amount" class="form-label">Final Project Amount <span class="text-danger">*</span></label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('final_project_amount') is-invalid @enderror"
                            id="final_project_amount" name="final_project_amount" value="{{ old('final_project_amount') }}"
                            placeholder="" required>
                        @error('final_project_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="active" class="form-label">Project Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('active') is-invalid @enderror" id="active" name="active">
                            <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('active')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="time_limit" class="form-label">Time Limit <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('time_limit') is-invalid @enderror"
                            id="time_limit" name="time_limit" value="{{ old('time_limit') }}"
                            placeholder="e.g., 6 months, 1 year" required>
                        @error('time_limit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="emd_fdr_detail" class="form-label">EMD/FDR Detail</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('emd_fdr_detail') is-invalid @enderror"
                        id="emd_fdr_detail" name="emd_fdr_detail" rows="3">{{ old('emd_fdr_detail') }}</textarea>
                    @error('emd_fdr_detail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('remark') is-invalid @enderror"
                        id="remark" name="remark" rows="3">{{ old('remark') }}</textarea>
                    @error('remark')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>