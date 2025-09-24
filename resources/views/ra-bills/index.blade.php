<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('R.A. Bills') }}
            </h2>
            <a href="{{ route('ra-bills.create') }}" class="btn btn-primary">Create New R.A. Bill</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table id="raBillsTable" class="table table-bordered" style="width:100%">
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
            $('#raBillsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: '{{ route("ra-bills.index") }}',
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
                        name: 'customer.name',
                        responsivePriority: 2
                    },
                    {
                        data: 'project_name',
                        name: 'project.name',
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
                        // className: 'text-center'
                    }
                ],
                order: [
                    [0, 'desc']
                ], // Sort by Bill No (latest first)
                pageLength: 25,
                // lengthMenu: [
                //     [10, 25, 50, 100, -1],
                //     [10, 25, 50, 100, "All"]
                // ],
                // dom: 'Bfrtip',
                // initComplete: function() {
                //     $('.dataTables_filter input').attr('placeholder', 'Search bills, customers, projects...');
                // }
            });
        });
    </script>
    @endpush
</x-app-layout>