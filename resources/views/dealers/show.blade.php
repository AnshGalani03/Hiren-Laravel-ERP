<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dealer Details: ') . $dealer->dealer_name }}
            </h2>
            <div>
                <a href="{{ route('invoices.create', ['dealer_id' => $dealer->id]) }}" class="btn btn-success btn-sm">Add Invoice</a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing', 'dealer_id' => $dealer->id]) }}" class="btn btn-danger btn-sm">Add Expense</a>
                <a href="{{ route('dealers.edit', $dealer) }}" class="btn btn-warning btn-sm">Edit Dealer</a>
            </div>
        </div>
    </x-slot>

    <!-- Financial Summary -->
    <div class="summery-card">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="back-btn pb-3">
                    <a href="{{ route('dealers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summery-card-box">
                    <div class="card-body text-center">
                        <h4>Total Invoices</h4>
                        <h2>₹{{ number_format($totalInvoices, 2) }}</h2>
                        <small>{{ $invoices->count() }} invoices</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summery-card-box">
                    <div class="card-body text-center">
                        <h4>Total Transactions</h4>
                        <h2>₹{{ number_format($totalTransactions, 2) }}</h2>
                        <small>{{ $transactions->count() }} transactions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summery-card-box">
                    <div class="card-body text-center">
                        <h4>Pending Amount</h4>
                        <h2>₹{{ number_format($pendingAmount, 2) }}</h2>
                        <small>All activities</small>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Dealer Details Cards -->
    <div class="row mb-4">
        <!-- Dealer Information Card -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Dealer Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="40%"><strong>Dealer Name:</strong></td>
                            <td>{{ $dealer->dealer_name ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Mobile No:</strong></td>
                            <td>{{ $dealer->mobile_no ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>GST:</strong></td>
                            <td>{{ $dealer->gst ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Address:</strong></td>
                            <td>{{ $dealer->address ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bank Details Card -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Bank Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="40%"><strong>Account No:</strong></td>
                            <td>{{ $dealer->account_no ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Account Name:</strong></td>
                            <td>{{ $dealer->account_name ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>IFSC:</strong></td>
                            <td>{{ $dealer->ifsc ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Bank Name:</strong></td>
                            <td>{{ $dealer->bank_name ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Section with DataTable -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-file-invoice-dollar text-success"></i> Invoices</h5>
            <a href="{{ route('invoices.create') }}?dealer_id={{ $dealer->id }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Add Invoice
            </a>
        </div>

        <!-- Invoice Filters -->
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="invoice_from_date" class="form-label">From Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="invoice_from_date">
                </div>
                <div class="col-md-4">
                    <label for="invoice_to_date" class="form-label">To Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="invoice_to_date">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="button" class="btn btn-secondary w-100" id="reset_invoice_filters">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards for Invoices -->
        <div class="summery-card card-body border-bottom bg-light">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="card summery-card-box">
                        <div class="card-body p-3">
                            <h6><i class="fas fa-file-invoice"></i> Total Invoices</h6>
                            <h4 id="dealer_total_invoices">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card summery-card-box">
                        <div class="card-body p-3">
                            <h6><i class="fas fa-money-bill"></i> Total Amount</h6>
                            <h4 id="dealer_invoice_amount">₹0.00</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card summery-card-box">
                        <div class="card-body p-3">
                            <h6><i class="fas fa-calendar"></i> GST Amount</h6>
                            <h4 id="dealer_gst_amount">₹0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices DataTable -->
        <div class="card-body">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dealer-invoices-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Bill No</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th class="d-none d-md-table-cell">Remark</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Section with DataTable -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-exchange-alt text-info"></i> Transactions</h5>
            <div>
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'dealer_id' => $dealer->id]) }}" class="btn btn-success btn-sm d-none">
                    <i class="fas fa-plus"></i> Add Income
                </a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing', 'dealer_id' => $dealer->id]) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-plus"></i> Add Expense
                </a>
            </div>
        </div>

        <!-- Transaction Filters -->
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-3 d-none">
                    <label for="dealer_tx_type_filter" class="form-label">Type</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="dealer_tx_type_filter">
                        <option value="">All Types</option>
                        <option value="incoming">Incoming</option>
                        <option value="outgoing">Outgoing</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="dealer_tx_from_date" class="form-label">From Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="dealer_tx_from_date">
                </div>
                <div class="col-md-4">
                    <label for="dealer_tx_to_date" class="form-label">To Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="dealer_tx_to_date">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="button" class="btn btn-secondary w-100" id="reset_tx_filters">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Summary Cards -->
        <div class="card-body border-bottom bg-light d-none">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body p-3">
                            <h6><i class="fas fa-arrow-up"></i> Total Incoming</h6>
                            <h4 id="dealer_tx_incoming">₹0.00</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body p-3">
                            <h6><i class="fas fa-arrow-down"></i> Total Outgoing</h6>
                            <h4 id="dealer_tx_outgoing">₹0.00</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body p-3">
                            <h6><i class="fas fa-balance-scale"></i> Net Balance</h6>
                            <h4 id="dealer_tx_balance">₹0.00</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body p-3">
                            <h6><i class="fas fa-list"></i> Total Records</h6>
                            <h4 id="dealer_tx_count">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions DataTable -->
        <div class="card-body">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dealer-transactions-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th class="d-none d-md-table-cell">Project</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="table-dark">
                                <th>Total</th>
                                <th></th>
                                <th></th>
                                <th class="d-none d-md-table-cell"></th>
                                <th></th>
                                <th id="tx_footer_total">₹0.00</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Invoices DataTable
            var invoicesTable = $('#dealer-invoices-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('dealers.invoices-data', $dealer) }}",
                    data: function(d) {
                        d.from_date = $('#invoice_from_date').val();
                        d.to_date = $('#invoice_to_date').val();
                    },
                    dataSrc: function(json) {
                        // Update summary cards with data from server
                        if (json.summary) {
                            updateInvoiceSummary(json.summary);
                        }
                        return json.data;
                    },
                    error: function(xhr, error, thrown) {
                        console.log('Invoices DataTable Error:', xhr.responseText);
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
                        data: 'bill_no',
                        name: 'bill_no',
                        responsivePriority: 2,
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        responsivePriority: 3,
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 4,
                    },
                    {
                        data: 'remark',
                        name: 'remark',
                        className: 'd-none d-md-table-cell',
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
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var total = 0;

                    for (var i = 0; i < data.length; i++) {
                        var $amount = $(data[i].amount);
                        var amount = parseFloat($amount.attr('data-amount')) || 0;
                        total += amount;
                    }

                    $('#invoice_footer_total').html('₹' + numberFormat(total));
                }
            });

            // Initialize Transactions DataTable
            var transactionsTable = $('#dealer-transactions-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('dealers.transactions-data', $dealer) }}",
                    data: function(d) {
                        d.type = $('#dealer_tx_type_filter').val();
                        d.from_date = $('#dealer_tx_from_date').val();
                        d.to_date = $('#dealer_tx_to_date').val();
                    },
                    dataSrc: function(json) {
                        // Update summary cards with data from server
                        if (json.summary) {
                            updateTransactionSummary(json.summary);
                        }
                        return json.data;
                    },
                    error: function(xhr, error, thrown) {
                        console.log('Transactions DataTable Error:', xhr.responseText);
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
                        data: 'type',
                        name: 'type',
                        responsivePriority: 2
                    },
                    {
                        data: 'category',
                        name: 'category',
                        responsivePriority: 3
                    },
                    {
                        data: 'project_name',
                        name: 'project_name',
                        className: 'd-none d-md-table-cell',
                        responsivePriority: 4
                    },
                    {
                        data: 'description',
                        name: 'description',
                        responsivePriority: 5
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        responsivePriority: 6
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 7
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
                    [6, 'desc']
                ],
                pageLength: 10,
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var incoming = 0;
                    var outgoing = 0;

                    for (var i = 0; i < data.length; i++) {
                        var $amount = $(data[i].amount);
                        var amount = parseFloat($amount.attr('data-amount')) || 0;
                        var type = $amount.attr('data-type');

                        if (type === 'incoming') {
                            incoming += amount;
                        } else if (type === 'outgoing') {
                            outgoing += amount;
                        }
                    }

                    var net = incoming - outgoing;
                    //Display Profit/Loss Text if incoming is available currently remove this line
                    // $('#tx_footer_total').html('₹' + numberFormat(Math.abs(net)) + ' (' + (net >= 0 ? 'Profit' : 'Loss') + ')');
                    $('#tx_footer_total').html('₹' + numberFormat(Math.abs(net)));
                }
            });

            // Filter event handlers
            $('#invoice_from_date, #invoice_to_date').change(function() {
                invoicesTable.draw();
            });

            $('#dealer_tx_type_filter, #dealer_tx_from_date, #dealer_tx_to_date').change(function() {
                transactionsTable.draw();
            });

            // Reset filters
            $('#reset_invoice_filters').click(function() {
                $('#invoice_from_date, #invoice_to_date').val('');
                invoicesTable.draw();
            });

            $('#reset_tx_filters').click(function() {
                $('#dealer_tx_type_filter').val('');
                $('#dealer_tx_from_date, #dealer_tx_to_date').val('');
                transactionsTable.draw();
            });

            // Update Invoice Summary Cards
            function updateInvoiceSummary(summary) {
                if (summary) {
                    $('#dealer_total_invoices').text(summary.total_invoices || 0);
                    $('#dealer_invoice_amount').text('₹' + numberFormat(summary.total_original_amount || 0));
                    $('#dealer_gst_amount').text('₹' + numberFormat(summary.total_gst_amount || 0));
                }
            }

            // Update Transaction Summary Cards
            function updateTransactionSummary(summary) {
                if (summary) {
                    $('#dealer_tx_incoming').text('₹' + numberFormat(summary.total_incoming || 0));
                    $('#dealer_tx_outgoing').text('₹' + numberFormat(summary.total_outgoing || 0));
                    $('#dealer_tx_balance').text('₹' + numberFormat(summary.net_balance || 0));
                    $('#dealer_tx_count').text(summary.total_records || 0);

                    // Update balance card color
                    var balanceCard = $('#dealer_tx_balance').closest('.card');
                    balanceCard.removeClass('bg-success bg-danger bg-warning bg-info');
                    if (summary.net_balance > 0) {
                        balanceCard.addClass('bg-success');
                    } else if (summary.net_balance < 0) {
                        balanceCard.addClass('bg-danger');
                    } else {
                        balanceCard.addClass('bg-warning');
                    }
                }
            }

            // Delete handlers
            $(document).on('click', '.delete-invoice', function() {
                var invoiceId = $(this).data('id');
                var deleteUrl = "{{ route('invoices.index') }}/" + invoiceId;

                if (confirm('Are you sure you want to delete this invoice?')) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                invoicesTable.ajax.reload(null, false);
                                alert('Invoice deleted successfully!');
                            }
                        },
                        error: function(xhr) {
                            alert('Error deleting invoice.');
                        }
                    });
                }
            });

            $(document).on('click', '.delete-transaction', function() {
                var transactionId = $(this).data('id');
                var deleteUrl = "{{ route('transactions.index') }}/" + transactionId;

                if (confirm('Are you sure you want to delete this transaction?')) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                transactionsTable.ajax.reload(null, false);
                                alert('Transaction deleted successfully!');
                            }
                        },
                        error: function(xhr) {
                            alert('Error deleting transaction.');
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

            // Initial load
            invoicesTable.draw();
            transactionsTable.draw();
        });
    </script>
    @endpush

</x-app-layout>