<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects') }}
            </h2>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Add New Project</a>
        </div>
    </x-slot>

    <!-- Filter Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Projects</h5>
        </div>
        <div class="p-4">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="status_filter" class="form-label">Status</label>
                    <select class="form-control" id="status_filter">
                        <option value="">All Projects</option>
                        <option value="active">Active Only</option>
                        <option value="inactive">Inactive Only</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="button" class="btn btn-secondary w-100" id="reset_filter">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered" id="projects-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Time Limit</th>
                                <th>Work Order Date</th>
                                <th>Status</th>
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
            var table = $('#projects-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('projects.index') }}",
                    data: function(d) {
                        d.status = $('#status_filter').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name',
                        responsivePriority: 1
                    },
                    {
                        data: 'department_name',
                        name: 'department_name',
                        responsivePriority: 2
                    },
                    {
                        data: 'amount_project',
                        name: 'amount_project',
                        responsivePriority: 2
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 3
                    },
                    {
                        data: 'time_limit',
                        name: 'time_limit',
                        responsivePriority: 4
                    },
                    {
                        data: 'work_order_date',
                        name: 'work_order_date',
                        responsivePriority: 5
                    },
                    {
                        data: 'status',
                        name: 'status',
                        responsivePriority: 2
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

            // Filter event handlers
            $('#status_filter').change(function() {
                table.draw();
            });

            // Reset filter
            $('#reset_filter').click(function() {
                $('#status_filter').val('');
                table.draw();
            });
        });
    </script>
    @endpush
</x-app-layout>