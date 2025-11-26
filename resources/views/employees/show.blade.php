<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee Details: ') . $employee->name }}
            </h2>
            <div>
                <a href="{{ route('upads.create', ['employee_id' => $employee->id]) }}" class="btn btn-primary btn-sm d-none">
                    Add Upad
                </a>
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">
                    Edit Employee
                </a>
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
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Name:</strong></td>
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
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Salary & Bank Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Salary:</strong></td>
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

    <!-- Salary & Upad Management Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg d-none">
        <!-- Header with Filter and Buttons on Same Line -->
        <div class="card-header p-4">
            <div class="salary-upad-management d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Upad Management</h5>

                <!-- Right side: Month Filter + Buttons -->
                <div class="salary-upad-management-btn d-flex align-items-center">
                    <!-- Month Filter Form -->
                    <form method="GET" action="{{ route('employees.show', $employee) }}" class="d-flex align-items-center me-3">
                        <label for="month" class="form-label me-2 mb-0">Filter:</label>
                        <select name="month" id="month" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control me-2" style="padding-right: 35px !important;">
                            @foreach($availableMonths as $month)
                            <option value="{{ $month->month_key }}" {{ $selectedMonth == $month->month_key ? 'selected' : '' }}>
                                {{ $month->month_name }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm me-2">
                            Filter
                        </button>
                    </form>

                    <!-- Action Buttons -->
                    <div class="btn-group" role="group">
                        <a href="{{ route('employees.monthly-overview', $employee) }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-calendar-alt"></i> Monthly Overview
                        </a>
                        <a href="{{ route('upads.create', ['employee_id' => $employee->id]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Upad
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4">
            @if($upads->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Salary</th>
                            <th>Upad</th>
                            <th class="text-center">Pending</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $showSalary = true;
                        $monthlySalary = $upads->first()->salary ?? 0; // Get salary for this month
                        $cumulativePending = $monthlySalary; // Start with full salary
                        @endphp

                        @foreach($upads as $index => $upad)
                        @php
                        // Deduct current upad from cumulative pending
                        $cumulativePending -= $upad->upad;
                        // Ensure pending never goes below 0 (but can show negative if needed)
                        $displayPending = $cumulativePending;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $upad->date->format('d/m/Y') }}</strong>
                                <br>
                                <small class="text-muted">{{ $upad->date->format('F Y') }}</small>
                            </td>

                            <!-- Show salary only once for the month -->
                            <td class="text-success">
                                @if($index === 0)
                                ₹{{ number_format($upad->salary, 2) }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!-- Upad amount -->
                            <td class="text-danger">₹{{ number_format($upad->upad, 2) }}</td>

                            <!-- Cumulative pending (shows remaining after each upad) -->
                            <td class="text-center">
                                @if($displayPending > 0)
                                <span class="badge bg-warning text-dark fs-6">
                                    ₹{{ number_format($displayPending, 2) }}
                                </span>
                                @elseif($displayPending == 0)
                                <span class="badge bg-success fs-6">
                                    ₹0.00
                                </span>
                                @else
                                <span class="badge bg-danger fs-6">
                                    -₹{{ number_format(abs($displayPending), 2) }}
                                </span>
                                @endif
                            </td>

                            <td>{{ Str::limit($upad->remark, 20) ?: 'N/A' }}</td>

                            <td>
                                <a href="{{ route('upads.edit', $upad) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('upads.destroy', $upad) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this record?')"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Month Summary -->
            <div class="salary-summery-card mt-4 p-3 bg-light rounded">
                @php
                $monthSalary = $upads->first()->salary ?? 0;
                $monthUpads = $upads->sum('upad');
                $finalPending = $monthSalary - $monthUpads; // Final calculation for summary
                @endphp
                <div class="row text-center">
                    <div class="col-md-3">
                        <h6>This Month Salary</h6>
                        <span class="text-success">₹{{ number_format($monthSalary, 2) }}</span>
                    </div>
                    <div class="col-md-3">
                        <h6>This Month Upads</h6>
                        <span class="text-danger">₹{{ number_format($monthUpads, 2) }}</span>
                    </div>
                    <div class="col-md-3">
                        <h6>This Month Pending</h6>
                        @if($finalPending > 0)
                        <span class="text-warning">₹{{ number_format($finalPending, 2) }}</span>
                        @elseif($finalPending == 0)
                        <span class="text-success">₹0.00</span>
                        @else
                        <span class="text-danger">-₹{{ number_format(abs($finalPending), 2) }}</span>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <h6>Records Count</h6>
                        <span class="text-info">{{ $upads->count() }}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-4">
                <p>No records found for {{ now()->createFromFormat('Y-m', $selectedMonth)->format('F Y') }}.</p>
                <a href="{{ route('upads.create', ['employee_id' => $employee->id]) }}" class="btn btn-primary">Add Record</a>
            </div>
            @endif
        </div>

    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Employee Transactions</h5>
                </div>
            </div>
            <div class="card-body">
                {{-- Filters --}}
                <div class="row mb-3 gy-2">
                    <div class="col-md-3 d-none">
                        <label for="type-filter" class="form-label">Type</label>
                        <select class="form-select" id="type-filter">
                            <option value="">All Types</option>
                            <option value="incoming">Incoming</option>
                            <option value="outgoing">Outgoing</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="from-date" class="form-label">From Date</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="from-date">
                    </div>
                    <div class="col-md-4">
                        <label for="to-date" class="form-label">To Date</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="to-date">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-secondary w-100" id="clear-filter">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>

                {{-- Summary Cards --}}
                <div class="row mb-3 d-none">
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Total Incoming</h6>
                                <h4 class="text-success mb-0" id="total-incoming">₹0.00</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Total Outgoing</h6>
                                <h4 class="text-danger mb-0" id="total-outgoing">₹0.00</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Net Balance</h6>
                                <h4 class="text-primary mb-0" id="net-balance">₹0.00</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Total Records</h6>
                                <h4 class="text-info mb-0" id="total-records">0</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Transactions Table --}}
                <div class="card-body">
                    <div class="table-responsive-wrapper">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="emp-transactions-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Date</th>
                                        <!-- <th>Type</th> -->
                                        <th>Category</th>
                                        <th>Project</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let transactionsTable;

        $(document).ready(function() {
            // Initialize DataTable
            transactionsTable = $('#emp-transactions-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route("employees.transactions-data", $employee->id) }}',
                    data: function(d) {
                        d.type = $('#type-filter').val();
                        d.from_date = $('#from-date').val();
                        d.to_date = $('#to-date').val();
                    },
                    error: function(xhr, error, code) {
                        console.error('DataTable AJAX error:', xhr.responseText);
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 1
                    },
                    // {
                    //     data: 'type',
                    //     name: 'type',
                    //     responsivePriority: 2
                    // },
                    {
                        data: 'category',
                        name: 'category',
                        responsivePriority: 4
                    },
                    {
                        data: 'project_name',
                        name: 'project_name',
                        responsivePriority: 5
                    },
                    {
                        data: 'description',
                        name: 'description',
                        responsivePriority: 6
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        responsivePriority: 2
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 2
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
                responsive: true,
                drawCallback: function(settings) {
                    updateSummary(settings.json);
                }
            });

            // Filter events
            $('#type-filter, #from-date, #to-date').on('change', function() {
                transactionsTable.ajax.reload();
            });

            $('#clear-filter').on('click', function() {
                $('#type-filter').val('');
                $('#from-date').val('');
                $('#to-date').val('');
                transactionsTable.ajax.reload();
            });
        });

        function updateSummary(data) {
            if (data && data.summary) {
                $('#total-incoming').text('₹' + Number(data.summary.total_incoming || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#total-outgoing').text('₹' + Number(data.summary.total_outgoing || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#net-balance').text('₹' + Number(data.summary.net_balance || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#total-records').text(data.summary.total_records || 0);
            }
        }

        // Handle delete button click
        $(document).on('click', '.delete-emp-transaction', function(e) {
            e.preventDefault();
            var transactionId = $(this).data('id');

            if (confirm('Are you sure you want to delete this transaction?')) {
                $.ajax({
                    url: '/transactions/' + transactionId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            transactionsTable.ajax.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        // console.log('Delete Error:', xhr.responseText);
                        alert('An error occurred while deleting the transaction.');
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>