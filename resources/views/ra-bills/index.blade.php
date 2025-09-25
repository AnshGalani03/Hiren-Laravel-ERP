<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('R.A. Bills') }}
            </h2>
            <a href="{{ route('ra-bills.create') }}" class="btn btn-primary btn-sm">Create New R.A. Bill</a>
        </div>
    </x-slot>


    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Date Filter Card -->
        <div class="p-6 text-gray-900">
            <form id="dateFilterForm">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" id="from_date" name="from_date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                            value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" id="to_date" name="to_date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                            value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label d-block">Date Filter</label>
                        <button type="button" id="filterBtn" class="btn btn-primary me-2 btn-sm d-none">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <button type="button" id="todayBtn" class="btn btn-outline-info me-2 btn-sm">
                            <i class="fas fa-calendar-day"></i> Today
                        </button>
                        <button type="button" id="thisMonthBtn" class="btn btn-outline-success me-2 btn-sm">
                            <i class="fas fa-calendar-alt"></i> This Month
                        </button>
                        <button type="button" id="clearBtn" class="btn btn-outline-secondary me-2 btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table id="ra-bills-table" class="table table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Bill No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Project</th>
                                <th>R.A. Bill Amount</th>
                                <th>Net Amount</th>
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
            let table;

            // Initialize DataTable
            function initializeTable() {
                table = $('#ra-bills-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    scrollX: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('ra-bills.index') }}",
                        data: function(d) {
                            d.from_date = $('#from_date').val();
                            d.to_date = $('#to_date').val();
                        },
                        error: function(xhr, error, thrown) {
                            console.error('DataTables error:', error);
                            alert('Error loading data. Please refresh the page.');
                        }
                    },
                    columns: [{
                            data: 'bill_no',
                            name: 'bill_no',
                            responsivePriority: 1
                        },
                        {
                            data: 'date',
                            name: 'date',
                            responsivePriority: 4
                        },
                        {
                            data: 'customer_name',
                            name: 'customer_name',
                            responsivePriority: 2
                        },
                        {
                            data: 'project_name',
                            name: 'project_name',
                            responsivePriority: 4
                        },
                        {
                            data: 'ra_bill_amount',
                            name: 'ra_bill_amount',
                            className: 'text-right font-semibold text-blue-600',
                            responsivePriority: 5
                        },
                        {
                            data: 'net_amount',
                            name: 'net_amount',
                            className: 'text-right font-bold text-green-600',
                            responsivePriority: 3
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
                        [2, 'desc']
                    ], // Sort by date descending
                    pageLength: 25,
                    responsive: true,
                });
            }

            // Initialize table on page load
            initializeTable();

            // Filter button click
            $('#filterBtn').on('click', function() {
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();

                // Validate date range
                if (fromDate && toDate && fromDate > toDate) {
                    alert('From Date cannot be greater than To Date');
                    return;
                }

                // Reload the table with new filters
                table.ajax.reload();
            });

            // Clear filters
            $('#clearBtn').on('click', function() {
                $('#from_date').val('');
                $('#to_date').val('');
                table.ajax.reload();
            });

            // Today filter
            $('#todayBtn').on('click', function() {
                const today = new Date().toISOString().split('T')[0];
                $('#from_date').val(today);
                $('#to_date').val(today);
                table.ajax.reload();
            });

            // This month filter
            $('#thisMonthBtn').on('click', function() {
                const now = new Date();
                const firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
                const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().split('T')[0];

                $('#from_date').val(firstDay);
                $('#to_date').val(lastDay);
                table.ajax.reload();
            });

            // Auto-filter when date fields change
            $('#from_date, #to_date').on('change', function() {
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();

                if (fromDate || toDate) {
                    // Add a small delay to allow user to set both dates
                    setTimeout(function() {
                        if (fromDate && toDate && fromDate > toDate) {
                            alert('From Date cannot be greater than To Date');
                            return;
                        }
                        table.ajax.reload();
                    }, 500);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>