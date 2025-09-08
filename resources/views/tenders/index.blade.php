<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tenders') }}
            </h2>
            <a href="{{ route('tenders.create') }}" class="btn btn-primary">Add New Tender</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tenders-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Work Name</th>
                                <th>Department</th>
                                <th>EMD/FDR Amount</th>
                                <th>DD Amount</th>
                                <th>Above/Below</th>
                                <th>Date</th>
                                <th>Result</th>
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
            $('#tenders-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: "{{ route('tenders.index') }}",
                columns: [{
                        data: 'work_name',
                        name: 'work_name',
                        responsivePriority: 1
                    },
                    {
                        data: 'department',
                        name: 'department',
                        responsivePriority: 2
                    },
                    {
                        data: 'amount_emd_fdr',
                        name: 'amount_emd_fdr',
                        render: function(data) {
                            return '₹' + parseFloat(data).toLocaleString('en-IN', {
                                minimumFractionDigits: 2
                            });
                        },
                        responsivePriority: 3
                    },
                    {
                        data: 'amount_dd',
                        name: 'amount_dd',
                        render: function(data) {
                            return '₹' + parseFloat(data).toLocaleString('en-IN', {
                                minimumFractionDigits: 2
                            });
                        },
                        responsivePriority: 4
                    },
                    {
                        data: 'above_below',
                        name: 'above_below',
                        responsivePriority: 5
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 6
                    },
                    {
                        data: 'result',
                        name: 'result',
                        responsivePriority: 7
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