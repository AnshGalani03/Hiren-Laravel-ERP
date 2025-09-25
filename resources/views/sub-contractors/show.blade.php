<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sub-Contractor Details: ') . $subContractor->contractor_name }}
            </h2>
            <div>
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'sub_contractor_id' => $subContractor->id]) }}" class="btn btn-success btn-sm">Add Income</a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing', 'sub_contractor_id' => $subContractor->id]) }}" class="btn btn-danger btn-sm">Add Expense</a>
                <a href="{{ route('sub-contractors.edit', $subContractor) }}" class="btn btn-warning btn-sm">Edit</a>
            </div>
        </div>
    </x-slot>

    <!-- Add CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        <div class="col-lg-12">
            <div class="back-btn pb-3">
                <a href="{{ route('sub-contractors.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Project Summary Cards -->
    <div class="row mb-4 d-none">
        <div class="col-md-4">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4>Project Amount</h4>
                    <h2>₹{{ number_format($subContractor->amount_project, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4>Total Transactions</h4>
                    <h2 id="total-bills-amount">₹{{ number_format($subContractor->total_bill_amount, 2) }}</h2>
                    <small id="bills-count">{{ $transactions->count() }} transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4>Remaining</h4>
                    <h2 id="remaining-amount">₹{{ number_format($subContractor->remaining_amount, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sub-Contractor Details Card -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sub-Contractor Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Contractor Name:</strong></td>
                            <td>{{ $subContractor->contractor_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Project Name:</strong></td>
                            <td>{{ $subContractor->project_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Project Amount:</strong></td>
                            <td>₹{{ number_format($subContractor->amount_project, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Department:</strong></td>
                            <td>{{ $subContractor->department_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date:</strong></td>
                            <td>{{ $subContractor->date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Work Order Date:</strong></td>
                            <td>{{ $subContractor->work_order_date ? $subContractor->work_order_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Time Limit:</strong></td>
                            <td>{{ $subContractor->time_limit }}</td>
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
                    @if($subContractor->contractor_type === 'third_party' && $subContractor->third_party_name)
                    <div class="mb-3">
                        <strong>Contractor Type:</strong>
                        <p>Third Party: {{ $subContractor->third_party_name }}</p>
                    </div>
                    @else
                    <div class="mb-3">
                        <strong>Contractor Type:</strong>
                        <p>Self</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <strong>EMD/FDR Detail:</strong>
                        <p>{{ $subContractor->emd_fdr_detail ?: 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Remark:</strong>
                        <p>{{ $subContractor->remark ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header p-3">
            <h5 class="mb-0">Transactions Overview & Filters</h5>
        </div>

        <!-- Filter Controls -->
        <div class="transactions-overview p-3 border-bottom">
            <div class="row">
                <div class="col-md-3">
                    <label for="transaction-type" class="form-label">Transaction Type</label>
                    <select class="form-control" id="transaction-type">
                        <option value="">All Transactions</option>
                        <option value="incoming">Incoming</option>
                        <option value="outgoing">Outgoing</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start-date" class="form-label">Start Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md" id="start-date">
                </div>
                <div class="col-md-3">
                    <label for="end-date" class="form-label">End Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md" id="end-date">
                </div>
                <div class="col-md-3 ">
                    <label for="action" class="block mb-1">Action</label>
                    <button type="button" class="btn btn-primary me-2 btn-sm" id="apply-filter">
                        <i class="fas fa-filter"></i> Apply Filter
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" id="clear-filter">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Enhanced Summary Cards -->
        <div class="card-body border-bottom bg-light p-3">
            <div class="row text-center g-3">
                <!-- Total Incoming Card -->
                <div class="col-md-4">
                    <div class="card summery-card-box h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <i class="fas fa-arrow-down me-2"></i>
                                <h6 class="card-title mb-0">Total Incoming</h6>
                            </div>
                            <h4 class="mb-1" id="summary-incoming">Rs 0.00</h4>
                        </div>
                    </div>
                </div>

                <!-- Total Outgoing Card -->
                <div class="col-md-4">
                    <div class="card summery-card-box h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <i class="fas fa-arrow-up me-2"></i>
                                <h6 class="card-title mb-0">Total Outgoing</h6>
                            </div>
                            <h4 class="mb-1" id="summary-outgoing">Rs 0.00</h4>
                        </div>
                    </div>
                </div>

                <!-- Net Balance Card -->
                <div class="col-md-4">
                    <div class="card summery-card-box h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <i class="fas fa-balance-scale me-2"></i>
                                <h6 class="card-title mb-0">Net Balance</h6>
                            </div>
                            <h4 class="mb-1" id="summary-balance">Rs 0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- DataTable -->
        <div class="p-6 text-gray-900">
            <div class="transaction-table-header d-flex justify-content-between mb-3">
                <h6>Transaction Details</h6>
                <div>
                    <a href="{{ route('transactions.create', ['type' => 'incoming', 'sub_contractor_id' => $subContractor->id]) }}" class="btn btn-sm btn-success">Add Income</a>
                    <a href="{{ route('transactions.create', ['type' => 'outgoing', 'sub_contractor_id' => $subContractor->id]) }}" class="btn btn-sm btn-danger">Add Expense</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="transactions-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var filterType = '';
            var startDate = '';
            var endDate = '';

            // Initialize DataTable
            var transactionsTable = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('sub-contractors.bills-data', $subContractor->id) }}",
                    type: 'GET',
                    data: function(d) {
                        d.type = filterType;
                        d.start_date = startDate;
                        d.end_date = endDate;
                    },
                    dataSrc: function(json) {
                        // Update summary cards
                        updateSummaryCards(json);

                        // Update project summary cards
                        if (json.total_amount !== undefined) {
                            $('#total-bills-amount').text('Rs ' + numberFormat(json.total_amount));
                            $('#bills-count').text(json.total_count + ' transactions');

                            var projectAmount = @json($subContractor -> amount_project);
                            var remaining = projectAmount - json.total_amount;
                            $('#remaining-amount').text('Rs ' + numberFormat(remaining));
                        }

                        return json.data;
                    },
                    error: function(xhr, error, thrown) {
                        console.log('DataTable Error:', xhr.responseText);
                        alert('Error loading data. Please refresh the page.');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                    },
                    {
                        data: 'description',
                        name: 'description',
                        responsivePriority: 4,
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        responsivePriority: 2,
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 6,
                    },
                    {
                        data: 'type',
                        name: 'type',
                        responsivePriority: 3,
                    },
                    {
                        data: 'category',
                        name: 'category',
                        responsivePriority: 5,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                    }
                ],
                order: [
                    [3, 'desc']
                ],
                pageLength: 10,
                language: {
                    emptyTable: "No transactions found for this sub-contractor.",
                    zeroRecords: "No matching transactions found.",
                    processing: "Loading transactions..."
                }
            });

            // Function to update summary cards
            function updateSummaryCards(json) {
                const totalIncoming = parseFloat(json.total_incoming) || 0;
                const totalOutgoing = parseFloat(json.total_outgoing) || 0;
                const balance = totalIncoming - totalOutgoing;

                // Update amounts
                $('#summary-incoming').text('Rs ' + numberFormat(totalIncoming));
                $('#summary-outgoing').text('Rs ' + numberFormat(totalOutgoing));
                $('#summary-balance').text('Rs ' + numberFormat(balance));
            }

            // Apply Filter Button
            $('#apply-filter').click(function() {
                filterType = $('#transaction-type').val();
                startDate = $('#start-date').val();
                endDate = $('#end-date').val();
                transactionsTable.ajax.reload();
            });

            // Clear Filter Button
            $('#clear-filter').click(function() {
                $('#transaction-type').val('');
                $('#start-date').val('');
                $('#end-date').val('');
                filterType = '';
                startDate = '';
                endDate = '';
                transactionsTable.ajax.reload();
            });

            // Handle delete button click
            $(document).on('click', '.delete-transaction', function(e) {
                e.preventDefault();
                var transactionId = $(this).data('id');

                if (confirm('Are you sure you want to delete this transaction?')) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '/transactions/' + transactionId,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                transactionsTable.ajax.reload();
                                alert(response.message);
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            console.log('Delete Error:', xhr.responseText);
                            alert('An error occurred while deleting the transaction.');
                        }
                    });
                }
            });

            // Number formatting function
            function numberFormat(num) {
                return parseFloat(num || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        });
    </script>
    @endpush

</x-app-layout>