<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products Management') }}
            </h2>
            <a class="btn btn-success btn-sm" href="{{ route('products.create') }}">
                Add New Product
            </a>
        </div>
    </x-slot>


    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="table-responsive">
                <table class="table table-bordered" id="products-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Product Name</th>
                            <th>HSN Code</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')

    <script>
        $(document).ready(function() {
            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('products.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                    },
                    {
                        data: 'product_name',
                        name: 'product_name',
                        responsivePriority: 2,
                    },
                    {
                        data: 'hsn_code',
                        name: 'hsn_code',
                        responsivePriority: 3,
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 4,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 2,
                    }
                ],
            });
        });
    </script>
    @endpush
</x-app-layout>