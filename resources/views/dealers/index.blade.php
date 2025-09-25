<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dealers') }}
            </h2>
            <a href="{{ route('dealers.create') }}" class="btn btn-primary btn-sm">Add New Dealer</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dealers-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Dealer Name</th>
                                <th>Mobile No</th>
                                <th>GST</th>
                                <th>Address</th>
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
            $('#dealers-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: "{{ route('dealers.index') }}",
                columns: [{
                        data: 'dealer_name',
                        name: 'dealer_name',
                        responsivePriority: 1
                    },
                    {
                        data: 'mobile_no',
                        name: 'mobile_no',
                        responsivePriority: 2
                    },
                    {
                        data: 'gst',
                        name: 'gst',
                        responsivePriority: 3
                    },
                    {
                        data: 'address',
                        name: 'address',
                        responsivePriority: 4
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1
                    },
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>