<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Employee: ') . $employee->name }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('employees.update', $employee) }}">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $employee->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('designation') is-invalid @enderror"
                            id="designation" name="designation" value="{{ old('designation', $employee->designation) }}" required>
                        @error('designation')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mobile_no" class="form-label">Mobile No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('mobile_no') is-invalid @enderror"
                            id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $employee->mobile_no) }}" required>
                        @error('mobile_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="alt_contact_no" class="form-label">Alternative Contact No</label>
                        <input type="text" class="form-control @error('alt_contact_no') is-invalid @enderror"
                            id="alt_contact_no" name="alt_contact_no" value="{{ old('alt_contact_no', $employee->alt_contact_no) }}">
                        @error('alt_contact_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pan_no" class="form-label">PAN No</label>
                        <input type="text" class="form-control @error('pan_no') is-invalid @enderror"
                            id="pan_no" name="pan_no" value="{{ old('pan_no', $employee->pan_no) }}">
                        @error('pan_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="aadhar_no" class="form-label">Aadhar No</label>
                        <input type="text" class="form-control @error('aadhar_no') is-invalid @enderror"
                            id="aadhar_no" name="aadhar_no" value="{{ old('aadhar_no', $employee->aadhar_no) }}">
                        @error('aadhar_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror"
                            id="salary" name="salary" value="{{ old('salary', $employee->salary) }}" required>
                        @error('salary')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pf" class="form-label">PF Number</label>
                        <input type="text" class="form-control @error('pf') is-invalid @enderror"
                            id="pf" name="pf" value="{{ old('pf', $employee->pf) }}" placeholder="e.g., PF12345678">
                        @error('pf')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="esic" class="form-label">ESIC Number</label>
                        <input type="text" class="form-control @error('esic') is-invalid @enderror"
                            id="esic" name="esic" value="{{ old('esic', $employee->esic) }}" placeholder="e.g., ESIC87654321">
                        @error('esic')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                            id="bank_name" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}">
                        @error('bank_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="account_no" class="form-label">Account No</label>
                        <input type="text" class="form-control @error('account_no') is-invalid @enderror"
                            id="account_no" name="account_no" value="{{ old('account_no', $employee->account_no) }}">
                        @error('account_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="ifsc" class="form-label">IFSC Code</label>
                        <input type="text" class="form-control @error('ifsc') is-invalid @enderror"
                            id="ifsc" name="ifsc" value="{{ old('ifsc', $employee->ifsc) }}">
                        @error('ifsc')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>