<x-app-layout>
    <x-slot name="header">
        <div class="bill-detail-header flex justify-between items-center">
            <div class="flex space-x-2">
                <a href="{{ route('transactions.index') }}"
                    class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Active Transactions
                </a>
            </div>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Trashed Transactions
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table id="trashed-transactions-table" class="table table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Sr. No</th>
                                <!-- <th>Date</th> -->
                                <th>Type</th>
                                <!-- <th>Description</th> -->
                                <th>Category</th>
                                <th>Linked To</th>
                                <th>Amount</th>
                                
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
            $('#trashed-transactions-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('transactions.trashed') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1
                    },
                    // {
                    //     data: 'date',
                    //     name: 'date'
                    // },
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
                    // {
                    //     data: 'description',
                    //     name: 'description'
                    // },
                    {
                        data: 'linked_to',
                        name: 'linked_to',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 4
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        responsivePriority: 5
                    },
                    {
                        data: 'deleted_at',
                        name: 'deleted_at',
                        responsivePriority: 6
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 2
                    }
                ],
                order: [
                    [5, 'desc']
                ], // Order by deleted_at descending
                pageLength: 25,
                responsive: true
            });
        });
    </script>
    @endpush
</x-app-layout>