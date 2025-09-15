<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Incoming Entries') }}
            </h2>
            <a href="{{ route('incomings.create') }}" class="btn btn-primary">Add New Incoming</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive">
                <div class="table-responsive-wrapper">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="incomings-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="d-none d-md-table-cell">Sr. No</th>
                                    <th>Name</th>
                                    <!-- <th>Created At</th> -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#incomings-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: "{{ route('incomings.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false,
                        responsivePriority: 3,
                        className: 'd-none d-md-table-cell'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        responsivePriority: 1,
                    },
                    // {
                    //     data: 'created_at',
                    //     name: 'created_at'
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 2,
                    },
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>