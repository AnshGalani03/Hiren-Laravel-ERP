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
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filters</h5>
        </div>
        <div class="p-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="filterDealer" class="form-label">Dealer</label>
                    <select id="filterDealer" class="form-select">
                        <option value="">All Dealers</option>
                        @foreach($dealers as $dealer)
                        <option value="{{ $dealer->dealer_name }}">{{ $dealer->dealer_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All Statuses</option>
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
                    <button type="button" id="clearFilters" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bills Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Bills List</h5>
        </div>
        <div class="p-6 text-gray-900">
            <div class="table-responsive">
                <table class="table table-bordered" id="bills-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Bill Number</th>
                            <th>Dealer</th>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#bills-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('bills.index') }}",
                    data: function(d) {
                        d.dealer_name = $('#filterDealer').val();
                        d.status = $('#filterStatus').val();
                        d.start_date = $('#filterStartDate').val();
                        d.end_date = $('#filterEndDate').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'bill_number',
                        name: 'bill_number'
                    },
                    {
                        data: 'dealer_name',
                        name: 'dealer.dealer_name'
                    },
                    {
                        data: 'bill_date',
                        name: 'bill_date'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [3, 'desc']
                ]
            });

            // Filter change events
            $('#filterDealer, #filterStatus, #filterStartDate, #filterEndDate').change(function() {
                table.draw();
            });

            // Clear filters
            $('#clearFilters').click(function() {
                $('#filterDealer').val('');
                $('#filterStatus').val('');
                $('#filterStartDate').val('');
                $('#filterEndDate').val('');
                table.draw();
            });

            // Handle delete button
            $(document).on('click', '.delete-bill', function() {
                var billId = $(this).data('id');
                if (confirm('Are you sure you want to delete this bill?')) {
                    $.ajax({
                        url: "{{ route('bills.index') }}/" + billId,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                alert('Bill deleted successfully!');
                            }
                        },
                        error: function() {
                            alert('Error deleting bill');
                        }
                    });
                }
            });

            // Handle status change
            $(document).on('change', '.status-select', function() {
                var billId = $(this).data('id');
                var newStatus = $(this).val();
                var selectElement = $(this);

                $.ajax({
                    url: '/bills/' + billId + '/update-status',
                    type: 'POST',
                    data: {
                        status: newStatus,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            var badge = selectElement.closest('tr').find('.status-badge');
                            badge.removeClass('bg-secondary bg-warning bg-success');

                            if (newStatus === 'paid') {
                                badge.addClass('bg-success');
                            } else if (newStatus === 'sent') {
                                badge.addClass('bg-warning');
                            } else {
                                badge.addClass('bg-secondary');
                            }
                            badge.text(response.status);

                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Error updating status');
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>