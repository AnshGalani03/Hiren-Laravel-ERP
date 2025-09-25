<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Invoices') }}
            </h2>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="row">
                <div class="col-lg-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dealer</label>
                    <select id="dealerFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Dealers</option>
                        @foreach($dealers as $dealer)
                        <option value="{{ $dealer->id }}">{{ $dealer->dealer_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" id="startDateFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-lg-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" id="endDateFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-lg-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                    <button id="clearFilterBtn" class="btn btn-secondary w-100">
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="invoices-summary row mb-4 mt-4">
        <div class="col-md-3">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4 class="pb-1"><i class="fas fa-file-invoice"></i> Total Invoices</h4>
                    <h2 id="totalInvoices">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4 class="pb-1"><i class="fas fa-money-bill"></i> Total Amount</h4>
                    <h2 id="totalAmount">₹0.00</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4 class="pb-1"><i class="fas fa-users"></i> Unique Dealers</h4>
                    <h2 id="uniqueDealers">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card summery-card-box">
                <div class="card-body text-center">
                    <h4 class="pb-1"><i class="fas fa-chart-line"></i> GST Amount</h4>
                    <h2 id="totalGst">₹0.00</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                        d.dealer_id = $('#dealerFilter').val();
                        d.start_date = $('#startDateFilter').val();
                        d.end_date = $('#endDateFilter').val();
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
                        name: 'dealer.dealer_name', // Important: This enables search on relationship
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
                    }
                ],
                order: [
                    [3, 'desc']
                ], // Order by date desc
                pageLength: 25,
                responsive: true,

                // Enable search box - remove the restrictive dom option
                dom: 'Blfrtip', // This includes the search box ('f')

                drawCallback: function(settings) {
                    updateSummary();
                }
            });


            // Filter functionality
            $('#filterBtn').on('click', function() {
                table.ajax.reload();
                updateSummary();
            });

            $('#clearFilterBtn').on('click', function() {
                $('#dealerFilter').val('');
                $('#startDateFilter').val('');
                $('#endDateFilter').val('');
                table.ajax.reload();
                updateSummary();
            });

            $('#refreshBtn').on('click', function() {
                table.ajax.reload();
                updateSummary();
            });

            // Enter key support for date inputs
            $('#startDateFilter, #endDateFilter, #dealerFilter').on('change', function() {
                table.ajax.reload();
                updateSummary();
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
                        dealer_id: $('#dealerFilter').val(),
                        start_date: $('#startDateFilter').val(),
                        end_date: $('#endDateFilter').val()
                    },
                    success: function(response) {
                        $('#totalInvoices').text(response.total_invoices || 0);
                        $('#totalAmount').text('₹' + numberFormat(response.total_original_amount || 0));
                        $('#uniqueDealers').text(response.unique_dealers || 0);
                        $('#totalGst').text('₹' + numberFormat(response.total_gst_amount || 0));
                    },
                    error: function(xhr) {
                        console.log('Summary Error:', xhr.responseText);
                        // Set to zero if error
                        $('#totalInvoices').text('0');
                        $('#totalAmount').text('₹0.00');
                        $('#uniqueDealers').text('0');
                        $('#totalGst').text('₹0.00');
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