<x-app-layout>
    <x-slot name="header">
        <div class="module-edit-page d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Project: ') . $project->name }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('projects.update', $project) }}">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $project->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('department_name') is-invalid @enderror"
                            id="department_name" name="department_name" value="{{ old('department_name', $project->department_name) }}" required>
                        @error('department_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Project Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                            id="date" name="date" value="{{ old('date', $project->date->format('Y-m-d')) }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="work_order_date" class="form-label">Work Order Date</label>
                        <input type="date" class="form-control @error('work_order_date') is-invalid @enderror"
                            id="work_order_date" name="work_order_date"
                            value="{{ old('work_order_date', $project->work_order_date ? $project->work_order_date->format('Y-m-d') : '') }}">
                        @error('work_order_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount_project" class="form-label">Project Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount_project') is-invalid @enderror"
                            id="amount_project" name="amount_project" value="{{ old('amount_project', $project->amount_project) }}" required>
                        @error('amount_project')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="time_limit" class="form-label">Time Limit <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('time_limit') is-invalid @enderror"
                            id="time_limit" name="time_limit" value="{{ old('time_limit', $project->time_limit) }}"
                            placeholder="e.g., 6 months, 1 year" required>
                        @error('time_limit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <!-- Add this before the EMD/FDR Detail section -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="active" class="form-label">Project Status</label>
                        <select class="form-control @error('active') is-invalid @enderror" id="active" name="active">
                            <option value="1" {{ old('active', $project->active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('active', $project->active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('active')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="emd_fdr_detail" class="form-label">EMD/FDR Detail</label>
                    <textarea class="form-control @error('emd_fdr_detail') is-invalid @enderror"
                        id="emd_fdr_detail" name="emd_fdr_detail" rows="3">{{ old('emd_fdr_detail', $project->emd_fdr_detail) }}</textarea>
                    @error('emd_fdr_detail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="form-control @error('remark') is-invalid @enderror"
                        id="remark" name="remark" rows="3">{{ old('remark', $project->remark) }}</textarea>
                    @error('remark')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Project</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>