<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employees') }}
            </h2>
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">Add New Employee</a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
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
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: "{{ route('employees.index') }}",
                columns: [{
                        data: 'name',
                        name: 'name',
                        responsivePriority: 1
                    },
                    {
                        data: 'designation',
                        name: 'designation',
                        responsivePriority: 3
                    },
                    {
                        data: 'mobile_no',
                        name: 'mobile_no',
                        responsivePriority: 2
                    },
                    {
                        data: 'alt_contact_no',
                        name: 'alt_contact_no',
                        responsivePriority: 4
                    },
                    {
                        data: 'pan_no',
                        name: 'pan_no',
                        responsivePriority: 5
                    },
                    {
                        data: 'aadhar_no',
                        name: 'aadhar_no',
                        responsivePriority: 6
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