<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sub-Contractors') }}
            </h2>
            <a href="{{ route('sub-contractors.create') }}" class="btn btn-primary btn-sm">Add New Sub-Contractor</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">

            <!-- Filter Options -->
            <div class="mb-4 ">
                <div class="row gy-2">
                    <div class="col-lg-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contractor Type</label>
                        <select id="contractorTypeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="">All Types</option>
                            <option value="self">Self</option>
                            <option value="third_party">Third Party</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" id="dateFromFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                    <div class="col-lg-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" id="dateToFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                    <div class="col-lg-3">
                        <div class="">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                            <button id="filterBtn" class="btn btn-primary me-2 btn-sm">
                                <i class="fas fa-filter"></i> Apply Filter
                            </button>
                            <button id="clearFilterBtn" class="btn btn-secondary btn-sm">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered" id="sub-contractors-table" style="width:100%">
                        <thead>
                            <tr>
                                <!-- <th>Sr. No</th> -->
                                <th>Contractor Name</th>
                                <th>Department</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Time Limit</th>
                                <th>Work Order Date</th>
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
            let table = $('#sub-contractors-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('sub-contractors.index') }}",
                    data: function(d) {
                        d.contractor_type = $('#contractorTypeFilter').val();
                        d.department = $('#departmentFilter').val();
                        d.date_from = $('#dateFromFilter').val();
                        d.date_to = $('#dateToFilter').val();
                    }
                },
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex',
                    //     searchable: false,
                    //     orderable: false
                    // },
                    {
                        data: 'contractor_name',
                        name: 'contractor_name',
                        responsivePriority: 1
                    },
                    {
                        data: 'department_name',
                        name: 'department_name',
                        responsivePriority: 2
                    },
                    {
                        data: 'amount_project',
                        name: 'amount_project',
                        responsivePriority: 3
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 4
                    },
                    {
                        data: 'time_limit',
                        name: 'time_limit',
                        responsivePriority: 5
                    },
                    {
                        data: 'work_order_date',
                        name: 'work_order_date',
                        responsivePriority: 6
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1
                    },
                ],
                pageLength: 25,
            });

            // Filter functionality
            $('#filterBtn').on('click', function() {
                table.ajax.reload();
            });

            $('#clearFilterBtn').on('click', function() {
                $('#contractorTypeFilter').val('');
                $('#departmentFilter').val('');
                $('#dateFromFilter').val('');
                $('#dateToFilter').val('');
                table.ajax.reload();
            });

            // Enter key support
            $('#departmentFilter').on('keypress', function(e) {
                if (e.which == 13) {
                    table.ajax.reload();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>