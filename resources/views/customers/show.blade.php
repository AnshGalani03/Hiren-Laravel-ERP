<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customer Details: ') . $customer->name }}
            </h2>
            <div>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm">
                    Edit Customer
                </a>
                <a href="{{ route('transactions.create', ['type' => 'incoming']) }}" class="btn btn-primary btn-sm">
                    Add Incoming
                </a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing']) }}" class="btn btn-success btn-sm">
                    Add Outgoing
                </a>
            </div>
        </div>
    </x-slot>
    <div class="row mb-4">
        <div class="col-lg-12">
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="mb-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Customer Name:</strong></td>
                                <td>{{ $customer->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phone Number:</strong></td>
                                <td>{{ $customer->phone_no }}</td>
                            </tr>
                            <tr>
                                <td><strong>GST Number:</strong></td>
                                <td>{{ $customer->gst ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>PAN Card:</strong></td>
                                <td>{{ $customer->pan_card ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>{{ $customer->address }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Transactions Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">
                    Customer Transactions
                </h3>
            </div>
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row gy-2">
                        <div class="col-md-3">
                            <label for="type-filter" class="form-label">Transaction Type</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="type-filter">
                                <option value="">All Types</option>
                                <option value="incoming">Incoming</option>
                                <option value="outgoing">Outgoing</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="from-date" class="form-label">From Date</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="from-date">
                        </div>
                        <div class="col-md-3">
                            <label for="to-date" class="form-label">To Date</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="to-date">
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <button type="button" class="btn btn-secondary w-100 mt-4" onclick="clearFilters()">
                                <i class="fas fa-sync"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="financial-summary-card card-body mb-4">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="card summery-card-box h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title"><i class="fas fa-arrow-down"></i> Total Incoming</h6>
                                <h4 class="mb-0" id="total-incoming">₹{{ number_format($totalIncoming, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card summery-card-box h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title"><i class="fas fa-arrow-up"></i> Total Outgoing</h6>
                                <h4 class="mb-0" id="total-outgoing">₹{{ number_format($totalOutgoing, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card summery-card-box h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title"><i class="fas fa-balance-scale"></i> Net Balance</h6>
                                <h4 class="mb-0" id="net-balance">₹{{ number_format($netBalance, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card summery-card-box h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title"><i class="fas fa-list"></i> Total Transactions</h6>
                                <h4 class="mb-0" id="total-records">{{ $transactions->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions DataTable -->
            <div class="card-body">
                <div class="table-responsive-wrapper">
                    <div class="table-responsive">
                        <table id="transactions-table" class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
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
            transactionsTable = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route("customers.transactions-data", $customer->id) }}',
                    data: function(d) {
                        d.type = $('#type-filter').val();
                        d.from_date = $('#from-date').val();
                        d.to_date = $('#to-date').val();
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
                    {
                        data: 'type',
                        name: 'type',
                        responsivePriority: 5
                    },
                    {
                        data: 'category',
                        name: 'category',
                        responsivePriority: 3
                    },
                    {
                        data: 'description',
                        name: 'description',
                        responsivePriority: 4
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
                ], // Order by date descending
                pageLength: 25,
                responsive: true,
                drawCallback: function(settings) {
                    updateSummary(settings.json);
                }
            });
        });


        // Filter event handlers
        $('#type-filter, #from-date, #to-date').change(function() {
            transactionsTable.draw();
        });

        function clearFilters() {
            $('#type-filter').val('');
            $('#from-date').val('');
            $('#to-date').val('');
            transactionsTable.ajax.reload();
        }

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
    </script>
    @endpush
</x-app-layout>