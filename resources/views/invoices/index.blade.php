<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Invoices') }}
            </h2>
        </div>
    </x-slot>

    <!-- Filter Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Invoices</h5>
        </div>
        <div class="p-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="dealer_filter" class="form-label">Filter by Dealer</label>
                    <select class="form-control" id="dealer_filter">
                        <option value="">All Dealers</option>
                        @foreach($dealers as $dealer)
                        <option value="{{ $dealer->id }}">{{ $dealer->dealer_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="button" class="btn btn-secondary w-100" id="reset_filter">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4><i class="fas fa-file-invoice"></i> Total Invoices</h4>
                    <h2 id="total_invoices">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4><i class="fas fa-money-bill"></i> Total Amount</h4>
                    <h2 id="total_amount">₹0.00</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4><i class="fas fa-users"></i> Unique Dealers</h4>
                    <h2 id="unique_dealers">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4><i class="fas fa-chart-line"></i> Average Amount</h4>
                    <h2 id="avg_amount">₹0.00</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-table"></i> Invoices List</h5>
        </div>
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered" id="invoices-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Bill No</th>
                                <th>Dealer Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Remark</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        $(document).ready(function() {
            // CSRF Token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize DataTable
            var table = $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('invoices.index') }}",
                    data: function(d) {
                        d.dealer_id = $('#dealer_filter').val();
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
                        data: 'bill_no',
                        name: 'bill_no',
                        responsivePriority: 2
                    },
                    {
                        data: 'dealer_name',
                        name: 'dealer_name',
                        responsivePriority: 2
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        responsivePriority: 1
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 3
                    },
                    {
                        data: 'remark',
                        name: 'remark',
                        responsivePriority: 4
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
                    [4, 'desc']
                ], // Order by date descending
                dom: 'lrtip', // This removes the default buttons including create button
                drawCallback: function(settings) {
                    updateSummary();
                }
            });

            // Filter event handler
            $('#dealer_filter').change(function() {
                table.draw();
            });

            // Reset filter
            $('#reset_filter').click(function() {
                $('#dealer_filter').val('');
                table.draw();
            });

            // Delete invoice
            $(document).on('click', '.delete-invoice', function() {
                var invoiceId = $(this).data('id');
                var deleteUrl = "{{ route('invoices.index') }}/" + invoiceId;

                if (confirm('Are you sure you want to delete this invoice?')) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload(null, false);
                                alert('Invoice deleted successfully!');
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            console.log('Delete Error:', xhr.responseText);
                            alert('Error deleting invoice. Please try again.');
                        }
                    });
                }
            });

            // Update summary function
            function updateSummary() {
                $.ajax({
                    url: "{{ route('invoices.summary') }}",
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        dealer_id: $('#dealer_filter').val()
                    },
                    success: function(response) {
                        $('#total_invoices').text(response.total_invoices || 0);
                        $('#total_amount').text('₹' + numberFormat(response.total_amount || 0));
                        $('#unique_dealers').text(response.unique_dealers || 0);
                        $('#avg_amount').text('₹' + numberFormat(response.avg_amount || 0));
                    },
                    error: function(xhr) {
                        console.log('Summary Error:', xhr.responseText);
                        // Set to zero if error
                        $('#total_invoices').text('0');
                        $('#total_amount').text('₹0.00');
                        $('#unique_dealers').text('0');
                        $('#avg_amount').text('₹0.00');
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