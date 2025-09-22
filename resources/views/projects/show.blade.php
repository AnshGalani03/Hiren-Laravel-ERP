<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Project Details: ') . $project->name }}
                @if(!$project->active)
                <span class="badge bg-secondary ms-2">Inactive</span>
                @endif
            </h2>
            <div>
                @if($project->active)
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'project_id' => $project->id]) }}" class="btn btn-primary btn-sm">Add Income</a>
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
    <div class="financial-summary-card row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>Total Income</h4>
                    <h2>₹{{ number_format($totalIncomes, 2) }}</h2>
                    <!-- <small>Project: ₹{{ number_format($totalProjectIncomes, 2) }} | Transactions: ₹{{ number_format($totalTransactionIncomes, 2) }}</small> -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>Total Expenses</h4>
                    <h2>₹{{ number_format($totalExpenses, 2) }}</h2>
                    <!-- <small>Project: ₹{{ number_format($totalProjectExpenses, 2) }} | Transactions: ₹{{ number_format($totalTransactionExpenses, 2) }}</small> -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card {{ $netProfit >= 0 ? 'bg-success' : 'bg-warning' }} text-white">
                <div class="card-body text-center">
                    <h4>Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</h4>
                    <h2>₹{{ number_format(abs($netProfit), 2) }}</h2>
                    <!-- <small>{{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</small> -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>Project Budget</h4>
                    <h2>₹{{ number_format($project->amount_project, 2) }}</h2>
                    <!-- <small>Budget vs Expense: {{ $totalExpenses > 0 ? number_format(($totalExpenses / $project->amount_project) * 100, 1) : 0 }}%</small> -->
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
                            <td>{{ $project->date ? $project->date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Work Order Date:</strong></td>
                            <td>{{ $project->work_order_date ? $project->work_order_date->format('d/m/Y') : 'N/A' }}</td>
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
                    <div class="team-members card-body">
                        @if($assignedEmployees->count() > 0)
                        <div class="row g-3">
                            @foreach($assignedEmployees as $employee)
                            <div class="col-lg-3 col-sm-6">
                                <div class="card border-start border-primary border-1 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="team-members-icon-name d-flex align-items-center">
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
                    <div class="modal-body team-member-modal">
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

    <!-- All Transactions Section -->
    <div class="card mb-4">
        <div class="transaction-header card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-exchange-alt text-info"></i> All Transactions</h5>
            <div>
                @if($project->active)
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'project_id' => $project->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Income
                </a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing', 'project_id' => $project->id]) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-plus"></i> Add Expense
                </a>
                @endif
            </div>
        </div>

        <!-- Filters Section -->
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="transaction_type_filter" class="form-label">Type</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="transaction_type_filter">
                        <option value="">All Types</option>
                        <option value="incoming">Incoming</option>
                        <option value="outgoing">Outgoing</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="transaction_from_date" class="form-label">From Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="transaction_from_date">
                </div>
                <div class="col-md-3">
                    <label for="transaction_to_date" class="form-label">To Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="transaction_to_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="button" class="btn btn-secondary w-100" id="reset_transaction_filters">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="financial-summary-card card-body border-bottom bg-light">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title"><i class="fas fa-arrow-up"></i> Total Incoming</h6>
                            <h4 class="mb-0" id="project_total_incoming">₹0.00</h4>
                            <small id="project_incoming_count">(0 transactions)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title"><i class="fas fa-arrow-down"></i> Total Outgoing</h6>
                            <h4 class="mb-0" id="project_total_outgoing">₹0.00</h4>
                            <small id="project_outgoing_count">(0 transactions)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title"><i class="fas fa-balance-scale"></i> Net Balance</h6>
                            <h4 class="mb-0" id="project_net_balance">₹0.00</h4>
                            <small id="project_total_count">(0 total)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white h-100">
                        <div class="card-body p-3">
                            <h6 class="card-title"><i class="fas fa-chart-pie"></i> Balance %</h6>
                            <h4 class="mb-0" id="project_balance_percentage">0%</h4>
                            <small>(Profit/Loss)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="card-body">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered" id="project-transactions-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th class="d-none d-md-table-cell">Linked To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Project Transactions DataTable
            var transactionsTable = $('#project-transactions-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('projects.transactions-data', $project) }}",
                    data: function(d) {
                        d.type = $('#transaction_type_filter').val();
                        d.from_date = $('#transaction_from_date').val();
                        d.to_date = $('#transaction_to_date').val();
                    },
                    dataSrc: function(json) {
                        // Update summary cards with data from server
                        if (json.summary) {
                            updateSummaryCards(json.summary);
                        }
                        return json.data;
                    },
                    error: function(xhr, error, thrown) {
                        console.log('DataTable AJAX Error:', xhr.responseText);
                        alert('Error loading transactions data.');
                    }
                },
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    }, // Sr. No
                    {
                        responsivePriority: 2,
                        targets: 1
                    }, // Type
                    {
                        responsivePriority: 3,
                        targets: 4
                    }, // Amount
                    {
                        responsivePriority: 4,
                        targets: 5
                    }, // Date
                    {
                        responsivePriority: 5,
                        targets: 2
                    }, // Category
                    {
                        responsivePriority: 6,
                        targets: 3
                    }, // Description
                    {
                        responsivePriority: 7,
                        targets: 6
                    }, // Linked To
                    {
                        responsivePriority: 1,
                        targets: 7
                    } // Actions
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'linked_to',
                        name: 'linked_to',
                        className: 'd-none d-md-table-cell'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [5, 'desc']
                ], // Order by date descending
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                // language: {
                //     processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                //     search: "Search Transactions:",
                //     lengthMenu: "Show _MENU_ transactions",
                //     info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                //     infoEmpty: "Showing 0 to 0 of 0 transactions",
                //     zeroRecords: "No transactions found for this project",
                //     emptyTable: "No transactions available for this project"
                // },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var pageIncoming = 0;
                    var pageOutgoing = 0;

                    // Calculate page totals
                    for (var i = 0; i < data.length; i++) {
                        var $amount = $(data[i].amount);
                        var amount = parseFloat($amount.attr('data-amount')) || 0;
                        var type = $amount.attr('data-type');

                        if (type === 'incoming') {
                            pageIncoming += amount;
                        } else if (type === 'outgoing') {
                            pageOutgoing += amount;
                        }
                    }

                    var pageTotal = pageIncoming - pageOutgoing;
                    $('#footer_amount_total').html('₹' + numberFormat(Math.abs(pageTotal)) + ' (' + (pageTotal >= 0 ? 'Profit' : 'Loss') + ')');
                }
            });

            // Filter event handlers
            $('#transaction_type_filter, #transaction_from_date, #transaction_to_date').change(function() {
                transactionsTable.draw();
            });

            // Reset filters
            $('#reset_transaction_filters').click(function() {
                $('#transaction_type_filter').val('');
                $('#transaction_from_date').val('');
                $('#transaction_to_date').val('');
                transactionsTable.draw();
            });

            // Update summary cards function
            function updateSummaryCards(summary) {
                if (summary) {
                    $('#project_total_incoming').text('₹' + numberFormat(summary.total_incoming));
                    $('#project_total_outgoing').text('₹' + numberFormat(summary.total_outgoing));
                    $('#project_net_balance').text('₹' + numberFormat(summary.net_balance));
                    $('#project_incoming_count').text('(' + summary.incoming_count + ' transactions)');
                    $('#project_outgoing_count').text('(' + summary.outgoing_count + ' transactions)');
                    $('#project_total_count').text('(' + summary.total_count + ' total)');

                    // Calculate balance percentage
                    var percentage = summary.total_incoming > 0 ?
                        ((summary.net_balance / summary.total_incoming) * 100).toFixed(1) : 0;
                    $('#project_balance_percentage').text(percentage + '%');

                    // Update net balance card color based on profit/loss
                    var netCard = $('#project_net_balance').closest('.card');
                    netCard.removeClass('bg-success bg-danger bg-warning bg-info');
                    if (summary.net_balance > 0) {
                        netCard.addClass('bg-success');
                    } else if (summary.net_balance < 0) {
                        netCard.addClass('bg-danger');
                    } else {
                        netCard.addClass('bg-warning');
                    }
                }
            }

            // Number formatting function
            function numberFormat(num) {
                return parseFloat(num || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Delete transaction handler
            $(document).on('click', '.delete-transaction', function() {
                var transactionId = $(this).data('id');
                var deleteUrl = "{{ route('transactions.index') }}/" + transactionId;

                if (confirm('Are you sure you want to delete this transaction?')) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                transactionsTable.ajax.reload(null, false);
                                alert('Transaction deleted successfully!');
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            console.log('Delete Error:', xhr.responseText);
                            alert('Error deleting transaction. Please try again.');
                        }
                    });
                }
            });

            // Initial load
            transactionsTable.draw();
        });
    </script>
    @endpush

</x-app-layout>