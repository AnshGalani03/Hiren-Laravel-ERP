<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sub-Contractors') }}
            </h2>
            <a href="{{ route('sub-contractors.create') }}" class="btn btn-primary">Add New Sub-Contractor</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <table class="table table-bordered" id="sub-contractors-table">
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

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#sub-contractors-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sub-contractors.index') }}",
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex',
                    //     searchable: false,
                    //     orderable: false
                    // },
                    {
                        data: 'contractor_name',
                        name: 'contractor_name'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        data: 'amount_project',
                        name: 'amount_project'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'time_limit',
                        name: 'time_limit'
                    },
                    {
                        data: 'work_order_date',
                        name: 'work_order_date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>