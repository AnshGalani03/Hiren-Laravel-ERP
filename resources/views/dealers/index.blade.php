<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dealers') }}
            </h2>
            <a href="{{ route('dealers.create') }}" class="btn btn-primary">Add New Dealer</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <table class="table table-bordered" id="dealers-table">
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

    @push('scripts')
    <script>
    $(document).ready(function() {
        $('#dealers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dealers.index') }}",
            columns: [
                {data: 'dealer_name', name: 'dealer_name'},
                {data: 'mobile_no', name: 'mobile_no'},
                {data: 'gst', name: 'gst'},
                {data: 'address', name: 'address'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
    </script>
    @endpush
</x-app-layout>
