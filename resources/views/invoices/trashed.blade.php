<x-app-layout>
    <x-slot name="header">
        <div class="bill-detail-header flex justify-between items-center">
            <div class="flex space-x-2">
                <a href="{{ route('invoices.index') }}"
                    class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Active Invoices
                </a>
            </div>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Trashed Invoices
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="table-responsive-wrapper">
                <div class="table-responsive">
                    <table id="trashed-invoices-table" class="table table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Bill No</th>
                                <th>Invoice Date</th>
                                <th>Dealer Name</th>
                                <th>Original Amount</th>
                                <th>GST Amount</th>
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
            $('#trashed-invoices-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('invoices.trashed') }}",
                columns: [{
                        data: 'bill_no',
                        name: 'bill_no',
                        responsivePriority: 1
                    },
                    {
                        data: 'date',
                        name: 'date',
                        responsivePriority: 2
                    },
                    {
                        data: 'dealer_name',
                        name: 'dealer_name',
                        responsivePriority: 2
                    },
                    {
                        data: 'original_amount',
                        name: 'original_amount',
                        responsivePriority: 3,
                        render: function(data, type, row) {
                            return data || '₹0.00';
                        }
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        responsivePriority: 4,
                        render: function(data, type, row) {
                            return '₹' + Number(data || 0).toLocaleString('en-IN', {
                                minimumFractionDigits: 2
                            });
                        }
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
                    [5, 'desc']
                ], // Order by deleted_at descending
                pageLength: 25,
                responsive: true
            });
        });

        // Individual restore function
        function restoreInvoice(invoiceId) {
            if (confirm('Are you sure you want to restore this invoice?')) {
                $.ajax({
                    url: `/invoices/${invoiceId}/restore`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success !== false) {
                            $('#trashed-invoices-table').DataTable().ajax.reload();
                            toastr.success('Invoice restored successfully!');
                        } else {
                            toastr.error(response.message || 'Error restoring invoice');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error restoring invoice');
                    }
                });
            }
        }

        // Individual permanent delete function
        function deleteInvoicePermanently(invoiceId) {
            if (confirm('Are you sure you want to permanently delete this invoice? This action cannot be undone!')) {
                $.ajax({
                    url: `/invoices/${invoiceId}/force-delete`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success !== false) {
                            $('#trashed-invoices-table').DataTable().ajax.reload();
                            toastr.success('Invoice permanently deleted!');
                        } else {
                            toastr.error(response.message || 'Error deleting invoice');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error deleting invoice permanently');
                    }
                });
            }
        }

        // Add toastr notifications if not already included
        if (typeof toastr === 'undefined') {
            window.toastr = {
                success: function(msg) {
                    alert('Success: ' + msg);
                },
                error: function(msg) {
                    alert('Error: ' + msg);
                },
                warning: function(msg) {
                    alert('Warning: ' + msg);
                },
                info: function(msg) {
                    alert('Info: ' + msg);
                }
            };
        }
    </script>
    @endpush
</x-app-layout>