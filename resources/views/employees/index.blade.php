<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employees') }}
            </h2>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">Add New Employee</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <table class="table table-bordered" id="employees-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Mobile No</th>
                        <th>Alt Contact</th>
                        <th>PAN No</th>
                        <th>Aadhar No</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
    $(document).ready(function() {
        $('#employees-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employees.index') }}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'designation', name: 'designation'},
                {data: 'mobile_no', name: 'mobile_no'},
                {data: 'alt_contact_no', name: 'alt_contact_no'},
                {data: 'pan_no', name: 'pan_no'},
                {data: 'aadhar_no', name: 'aadhar_no'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
    </script>
    @endpush
</x-app-layout>
