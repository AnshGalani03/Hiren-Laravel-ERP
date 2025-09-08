<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Project Details: ') . $project->name }}
                @if(!$project->active)
                <span class="badge bg-secondary ms-2">Inactive</span>
                @endif
            </h2>
            <div>
                @if($project->active)
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'project_id' => $project->id]) }}" class="btn btn-success btn-sm">Add Income</a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing', 'project_id' => $project->id]) }}" class="btn btn-danger btn-sm">Add Expense</a>
                @endif
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">Edit Project</a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="back-btn pb-3">
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
    <!-- Financial Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>Total Income</h4>
                    <h2>₹{{ number_format($totalIncomes, 2) }}</h2>
                    <small>Project: ₹{{ number_format($totalProjectIncomes, 2) }} | Transactions: ₹{{ number_format($totalTransactionIncomes, 2) }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>Total Expenses</h4>
                    <h2>₹{{ number_format($totalExpenses, 2) }}</h2>
                    <small>Project: ₹{{ number_format($totalProjectExpenses, 2) }} | Transactions: ₹{{ number_format($totalTransactionExpenses, 2) }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card {{ $netProfit >= 0 ? 'bg-success' : 'bg-warning' }} text-white">
                <div class="card-body text-center">
                    <h4>Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</h4>
                    <h2>₹{{ number_format(abs($netProfit), 2) }}</h2>
                    <small>{{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>Project Budget</h4>
                    <h2>₹{{ number_format($project->amount_project, 2) }}</h2>
                    <small>Budget vs Expense: {{ $totalExpenses > 0 ? number_format(($totalExpenses / $project->amount_project) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Project Details Card -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Project Information</h5>
                </div>
                <!-- Update the Project Information card body -->
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>{{ $project->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($project->active)
                                <span class="badge bg-success fs-6">Active</span>
                                @else
                                <span class="badge bg-secondary fs-6">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Department:</strong></td>
                            <td>{{ $project->department_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Project Date:</strong></td>
                            <td>@formatDate($project->date)</td>
                        </tr>
                        <tr>
                            <td><strong>Work Order Date:</strong></td>
                            <td>@formatDate($project->work_order_date)</td>
                        </tr>
                        <tr>
                            <td><strong>Time Limit:</strong></td>
                            <td>{{ $project->time_limit }}</td>
                        </tr>
                        <tr>
                            <td><strong>Project Amount:</strong></td>
                            <td>₹{{ number_format($project->amount_project, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Additional Details Card -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Additional Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>EMD/FDR Detail:</strong>
                        <p>{{ $project->emd_fdr_detail ?: 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Remark:</strong>
                        <p>{{ $project->remark ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Assigned Employees Section -->
            <div class="assigne-employee mb-3">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-users text-primary"></i> Team Members ({{ $assignedEmployees->count() }})</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignEmployeeModal">
                            <i class="fas fa-user-plus"></i> Manage Team
                        </button>
                    </div>
                    <div class="card-body">
                        @if($assignedEmployees->count() > 0)
                        <div class="row g-3">
                            @foreach($assignedEmployees as $employee)
                            <div class="col-lg-3 col-sm-6">
                                <div class="card border-start border-primary border-1 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $employee->name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-briefcase me-1"></i>{{ $employee->designation ?: 'Team Member' }}
                                                    </small>
                                                </div>
                                            </div>
                                            <form action="{{ route('projects.remove-employee', $project) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill"
                                                    onclick="return confirm('Remove {{ $employee->name }} from this project?')"
                                                    title="Remove from project">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-users fa-3x text-muted"></i>
                            </div>
                            <h6 class="text-muted">No team members assigned</h6>
                            <p class="text-muted mb-3">Add employees to collaborate on this project</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignEmployeeModal">
                                <i class="fas fa-user-plus"></i> Add Team Members
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Employee Modal -->
    <div class="modal fade" id="assignEmployeeModal" tabindex="-1" aria-labelledby="assignEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="assignEmployeeModalLabel">
                        <i class="fas fa-users me-2"></i>Manage Project Team
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('projects.assign-employee', $project) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user-check text-primary me-1"></i>Select Team Members
                            </label>
                            <p class="text-muted small mb-2">Choose employees to work on this project</p>
                            <div class="row g-2">
                                @foreach($employees as $employee)
                                <div class="col-md-3">
                                    <div class="card border {{ $assignedEmployees->contains($employee) ? 'border-primary bg-light' : 'border-secondary' }}">
                                        <div class="card-body p-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="employee_ids[]"
                                                    value="{{ $employee->id }}"
                                                    id="employee{{ $employee->id }}"
                                                    {{ $assignedEmployees->contains($employee) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="employee{{ $employee->id }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-{{ $assignedEmployees->contains($employee) ? 'primary' : 'secondary' }} text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                            <i class="fas fa-user fa-sm"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $employee->name }}</div>
                                                            <small class="text-muted">{{ $employee->designation ?: 'Team Member' }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Team
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Rest of existing sections (Assigned Employees, Project Expenses, Project Incomes) -->
    <!-- ... existing code ... -->

    <!-- NEW: All Transactions Section -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Transactions ({{ $transactions->count() }})</h5>
            <div>
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'project_id' => $project->id]) }}" class="btn btn-sm btn-success">Add Income</a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing', 'project_id' => $project->id]) }}" class="btn btn-sm btn-danger">Add Expense</a>
            </div>
        </div>
        <div class="card-body">
            @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Linked Dealer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>
                                <span class="badge bg-{{ $transaction->type == 'incoming' ? 'success' : 'danger' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td>
                                @if($transaction->type == 'incoming' && $transaction->incoming)
                                {{ $transaction->incoming->name }}
                                @elseif($transaction->type == 'outgoing' && $transaction->outgoing)
                                {{ $transaction->outgoing->name }}
                                @else
                                N/A
                                @endif
                            </td>
                            <td>{{ $transaction->description }}</td>
                            <td>₹{{ number_format($transaction->amount, 2) }}</td>
                            <td>@formatDate($transaction->date)</td>
                            <td>{{ $transaction->dealer ? $transaction->dealer->dealer_name : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Transaction Totals</strong></td>
                            <td><strong>Income: ₹{{ number_format($totalTransactionIncomes, 2) }} | Expense: ₹{{ number_format($totalTransactionExpenses, 2) }}</strong></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <p class="text-muted">No transactions found for this project.</p>
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'project_id' => $project->id]) }}" class="btn btn-success me-2">Add First Income</a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing', 'project_id' => $project->id]) }}" class="btn btn-danger">Add First Expense</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Rest of existing modals and content -->
</x-app-layout>