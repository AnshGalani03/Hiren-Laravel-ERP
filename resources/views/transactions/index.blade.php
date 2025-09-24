<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Transactions') }}
            </h2>
            <div>
                <a href="{{ route('transactions.create', ['type' => 'incoming']) }}" class="btn btn-primary">
                    Add Incoming
                </a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing']) }}" class="btn btn-danger">
                    Add Outgoing
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Filter Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
        <div class="p-4">
            <form id="filter-form">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="project_filter" class="form-label">Project</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="project_filter" name="project_id">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="dealer_filter" class="form-label">Dealer</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="dealer_filter" name="dealer_id">
                            <option value="">All Dealers</option>
                            @foreach($dealers as $dealer)
                            <option value="{{ $dealer->id }}">{{ $dealer->dealer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="sub_contractor_id" class="form-label">Sub-Contractor</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="sub_contractor_id" name="sub_contractor_id">
                            <option value="">All Sub-Contractors</option>
                            @foreach($subContractors as $subContractor)
                            <option value="{{ $subContractor->id }}">{{ $subContractor->contractor_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="type_filter" class="form-label">Type</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="type_filter" name="type">
                            <option value="">All Types</option>
                            <option value="incoming">Incoming</option>
                            <option value="outgoing">Outgoing</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="daterange" class="form-label">Date Range</label>
                        <div id="daterange" class="form-control" style="cursor: pointer;">
                            <i class="fas fa-calendar"></i>
                            <span></span>
                            <i class="fas fa-caret-down float-end"></i>
                        </div>
                    </div>

                    <div class="col-md-1 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-secondary w-100" id="reset_filters">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4 transactions-summery-card">
        <div class="col-md-3">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4><i class="fas fa-arrow-up"></i> Total Incoming</h4>
                    <h2 id="total_incoming">₹0.00</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4><i class="fas fa-arrow-down"></i> Total Outgoing</h4>
                    <h2 id="total_outgoing">₹0.00</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4><i class="fas fa-balance-scale"></i> Net Balance</h4>
                    <h2 id="net_balance">₹0.00</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4><i class="fas fa-list"></i> Total Records</h4>
                    <h2 id="total_records">0</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered" id="transactions-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Linked To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Include required CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        $(document).ready(function() {
            // CSRF Token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize date range picker
            var start = moment().subtract(30, 'days');
            var end = moment();

            function cb(start, end) {
                $('#daterange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'All Time': [moment().subtract(10, 'years'), moment()]
                }
            }, cb);

            cb(start, end);

            // Initialize DataTable
            var table = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('transactions.index') }}",
                    data: function(d) {
                        d.project_id = $('#project_filter').val();
                        d.dealer_id = $('#dealer_filter').val();
                        d.type = $('#type_filter').val();
                        d.from_date = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        d.to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    },
                    error: function(xhr, error, thrown) {
                        console.log('DataTable AJAX Error:', xhr.responseText);
                        alert('Error loading data. Please check console for details.');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false,
                        responsivePriority: 1
                    },
                    {
                        data: 'type',
                        name: 'type',
                        responsivePriority: 2
                    },
                    {
                        data: 'category',
                        name: 'category',
                        responsivePriority: 4
                    },
                    {
                        data: 'description',
                        name: 'description',
                        responsivePriority: 3
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        responsivePriority: 2
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 3
                    },
                    {
                        data: 'linked_to',
                        name: 'linked_to',
                        responsivePriority: 5
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1
                    },
                ],
                order: [
                    [5, 'desc']
                ], // Order by date descending
                pageLength: 25,
                drawCallback: function(settings) {
                    updateSummary();
                }
            });

            // Filter event handlers
            $('#project_filter, #dealer_filter, #type_filter').change(function() {
                table.draw();
            });

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                table.draw();
            });

            // Reset filters
            $('#reset_filters').click(function() {
                $('#project_filter').val('');
                $('#dealer_filter').val('');
                $('#type_filter').val('');

                // Reset date range to last 30 days
                var start = moment().subtract(30, 'days');
                var end = moment();
                $('#daterange').data('daterangepicker').setStartDate(start);
                $('#daterange').data('daterangepicker').setEndDate(end);
                cb(start, end);

                table.draw();
            });

            // Delete transaction - FIXED
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
                                table.ajax.reload(null, false);
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

            // Update summary function - FIXED
            function updateSummary() {
                $.ajax({
                    url: "{{ route('transactions.summary') }}",
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        project_id: $('#project_filter').val(),
                        dealer_id: $('#dealer_filter').val(),
                        type: $('#type_filter').val(),
                        from_date: $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                        to_date: $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD')
                    },
                    success: function(response) {
                        $('#total_incoming').text('₹' + numberFormat(response.total_incoming));
                        $('#total_outgoing').text('₹' + numberFormat(response.total_outgoing));
                        $('#net_balance').text('₹' + numberFormat(response.net_balance));
                        $('#total_records').text(response.total_records);

                        // Update net balance card color
                        // var netCard = $('#net_balance').closest('.card');
                        // netCard.removeClass('bg-success bg-danger bg-warning');
                        // if (response.net_balance > 0) {
                        //     netCard.addClass('bg-success');
                        // } else if (response.net_balance < 0) {
                        //     netCard.addClass('bg-danger');
                        // } else {
                        //     netCard.addClass('bg-warning');
                        // }
                    },
                    error: function(xhr) {
                        console.log('Summary Error:', xhr.responseText);
                        // Set to zero if error
                        $('#total_incoming').text('₹0.00');
                        $('#total_outgoing').text('₹0.00');
                        $('#net_balance').text('₹0.00');
                        $('#total_records').text('0');
                    }
                });
            }

            // Number formatting function
            function numberFormat(num) {
                return parseFloat(num || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Initial summary load
            updateSummary();
        });
    </script>

    @endpush
</x-app-layout>