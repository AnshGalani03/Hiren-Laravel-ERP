<x-app-layout>
    <x-slot name="header">
        <div class="bill-detail-header flex justify-between items-center">
            <div class="flex space-x-2">
                <a href="{{ route('dealers.index') }}"
                    class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Active Dealers
                </a>
            </div>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Trashed Dealers
                </h2>
            </div>
        </div>
    </x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">

                    <table id="trashed-dealers-table" class="table table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Dealer Name</th>
                                <th>Mobile No</th>
                                <th>GST</th>
                                <th>Address</th>
                                <th>Deleted At</th>
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
            $('#trashed-dealers-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('dealers.trashed') }}",
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
                        data: 'deleted_at',
                        name: 'deleted_at',
                        responsivePriority: 5
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
                    [4, 'desc']
                ], // Order by deleted_at descending
                pageLength: 25,
                responsive: true
            });
        });
    </script>
    @endpush
</x-app-layout>