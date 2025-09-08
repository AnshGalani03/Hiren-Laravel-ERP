<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee Details: ') . $employee->name }}
            </h2>
            <div>
                <a href="{{ route('upads.create', ['employee_id' => $employee->id]) }}" class="btn btn-success">Add Upad</a>
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">Edit Employee</a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="back-btn pb-3">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <!-- Employee Details Card -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>{{ $employee->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Designation:</strong></td>
                            <td>{{ $employee->designation }}</td>
                        </tr>
                        <tr>
                            <td><strong>Mobile:</strong></td>
                            <td>{{ $employee->mobile_no }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alt Contact:</strong></td>
                            <td>{{ $employee->alt_contact_no ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>PAN:</strong></td>
                            <td>{{ $employee->pan_no ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Aadhar:</strong></td>
                            <td>{{ $employee->aadhar_no ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Salary & Details Card -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Salary & Other Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Salary:</strong></td>
                            <td>₹{{ number_format($employee->salary, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>PF Number:</strong></td>
                            <td>{{ $employee->pf ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>ESIC Number:</strong></td>
                            <td>{{ $employee->esic ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Bank Name:</strong></td>
                            <td>{{ $employee->bank_name ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Account No:</strong></td>
                            <td>{{ $employee->account_no ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>IFSC:</strong></td>
                            <td>{{ $employee->ifsc ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Upad Records section remains the same -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Upad Records</h5>
            <a href="{{ route('upads.create', ['employee_id' => $employee->id]) }}" class="btn btn-sm btn-primary">Add New Upad</a>
        </div>
        <div class="p-6 text-gray-900">
            @if($upads->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Date</th>
                            <th>Salary</th>
                            <th>Upad</th>
                            <th>Pending</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upads as $upad)
                        <tr>
                            <td>{{ $upad->month }}</td>
                            <td>@formatDate($upad->date)</td>
                            <td>₹{{ number_format($upad->salary, 2) }}</td>
                            <td>₹{{ number_format($upad->upad, 2) }}</td>
                            <td>₹{{ number_format($upad->pending, 2) }}</td>
                            <td>{{ $upad->remark ?: 'N/A' }}</td>
                            <td>
                                <a href="{{ route('upads.edit', $upad) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('upads.destroy', $upad) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <p class="text-muted">No upad records found.</p>
                <a href="{{ route('upads.create', ['employee_id' => $employee->id]) }}" class="btn btn-primary">Add First Upad</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>