<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customers Management') }}
            </h2>
            <a class="btn btn-primary" href="{{ route('customers.create') }}">
                Add New Customer
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-users"></i> Customers List</h5>
        </div>
        <div class="p-6 text-gray-900">
            <div class="table-responsive">
                <table class="table table-bordered" id="customers-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>GST</th>
                            <th>Phone</th>
                            <th>Created Date</th>
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
            $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('customers.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                    },
                    {
                        data: 'name',
                        name: 'name',
                        responsivePriority: 2,
                    },
                    {
                        data: 'address',
                        name: 'address',
                        responsivePriority: 3,
                    },
                    {
                        data: 'gst',
                        name: 'gst',
                        responsivePriority: 4,
                    },
                    {
                        data: 'phone_no',
                        name: 'phone_no',
                        responsivePriority: 5,
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        responsivePriority: 6,
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
                    [5, 'desc']
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>