<x-app-layout>
    <x-slot name="header">
        <div class="bill-detail-header flex justify-between items-center">
            <div class="flex space-x-2">
                <a href="{{ route('ra-bills.index') }}"
                    class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Active R.A. Bills
                </a>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Trashed R.A. Bills
            </h2>
        </div>
    </x-slot>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table id="trashed-bills-table" class="table table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Bill No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Project</th>
                                <th>Amount</th>
                                <th>Net Amount</th>
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
            $('#trashed-bills-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: "{{ route('ra-bills.trashed') }}",
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
                        responsivePriority: 3
                    },
                    {
                        data: 'ra_bill_amount',
                        name: 'ra_bill_amount',
                        responsivePriority: 5
                    },
                    {
                        data: 'net_amount',
                        name: 'net_amount',
                        responsivePriority: 6
                    },
                    {
                        data: 'deleted_at',
                        name: 'deleted_at',
                        responsivePriority: 7
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
                    [6, 'desc']
                ], // Order by deleted_at descending
                pageLength: 25,
                responsive: true
            });
        });
    </script>
    @endpush
</x-app-layout>