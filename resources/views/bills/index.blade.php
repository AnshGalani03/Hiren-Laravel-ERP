<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bills Management') }}
            </h2>
            <a class="btn btn-primary" href="{{ route('bills.create') }}">
                Create New Bill
            </a>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-3">
        <div class="p-3">
            <div class="bills-filters row">
                <!-- Filter section -->
                <div class="col-md-3">
                    <label for="filterCustomer" class="form-label">Customer</label>
                    <select id="filterCustomer" class="form-select">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->name }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="sent">Sent</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStartDate" class="form-label">Start Date</label>
                    <input type="date" id="filterStartDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="filterEndDate" class="form-label">End Date</label>
                    <input type="date" id="filterEndDate" class="form-control">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="button" id="resetFilters" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bills Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive">
                <table class="table table-bordered" id="bills-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Bill Number</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var billsTable = $('#bills-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('bills.index') }}",
                    type: "GET",
                    data: function(d) {
                        // Add filter parameters to the AJAX request
                        d.customer_name = $('#filterCustomer').val();
                        d.status = $('#filterStatus').val();
                        d.start_date = $('#filterStartDate').val();
                        d.end_date = $('#filterEndDate').val();
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
                        data: 'bill_number',
                        name: 'bill_number',
                        responsivePriority: 2
                    },
                    {
                        data: 'customer_name',
                        name: 'customer.name',
                        responsivePriority: 3
                    },
                    {
                        data: 'bill_date',
                        name: 'bill_date',
                        responsivePriority: 5
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        responsivePriority: 4
                    },
                    {
                        data: 'status',
                        name: 'status',
                        responsivePriority: 6
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 7
                    }
                ],
                order: [
                    [3, 'desc']
                ]
            });

            // Filter functionality
            $('#filterCustomer, #filterStatus, #filterStartDate, #filterEndDate').on('change keyup', function() {
                billsTable.draw();
            });

            // Reset filters
            $('#resetFilters').click(function() {
                $('#filterCustomer, #filterStatus, #filterStartDate, #filterEndDate').val('');
                billsTable.draw();
            });

            // Handle delete button click
            $(document).on('click', '.delete-bill', function(e) {
                e.preventDefault();

                var billId = $(this).data('id');
                var billNumber = $(this).data('bill-number') || billId;

                if (confirm('Are you sure you want to delete Bill #' + billNumber + '?')) {
                    // Create a form and submit it to trigger Laravel flash messages
                    var form = $('<form>', {
                        'method': 'POST',
                        'action': '{{ route("bills.index") }}/' + billId
                    });

                    // Add CSRF token
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': $('meta[name="csrf-token"]').attr('content')
                    }));

                    // Add DELETE method
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));

                    // Append form to body and submit
                    $('body').append(form);
                    form.submit();
                }
            });

            // Handle status update
            $(document).on('change', '.status-select', function() {
                var billId = $(this).data('id');
                var newStatus = $(this).val();
                var statusSelect = $(this);
                var originalValue = statusSelect.data('original-value') || statusSelect.val();

                $.ajax({
                    url: '{{ route("bills.index") }}/' + billId + '/update-status',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update badge color and text
                            var statusBadge = statusSelect.siblings('.status-badge');
                            var badgeClass = newStatus === 'paid' ? 'bg-success' : (newStatus === 'sent' ? 'bg-warning' : 'bg-secondary');
                            statusBadge.removeClass('bg-success bg-warning bg-secondary').addClass(badgeClass);
                            statusBadge.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));

                            // Store new original value
                            statusSelect.data('original-value', newStatus);
                        }
                    },
                    error: function() {
                        // Revert the select value on error
                        statusSelect.val(originalValue);
                    }
                });
            });

            // Store original values for status selects
            $('.status-select').each(function() {
                $(this).data('original-value', $(this).val());
            });
        });
    </script>
    @endpush
</x-app-layout>