<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sub-Contractor Details: ') . $subContractor->contractor_name }}
            </h2>
            <div>
                <a href="{{ route('sub-contractor-bills.create', ['sub_contractor_id' => $subContractor->id]) }}" class="btn btn-success">Add Bill</a>
                <a href="{{ route('sub-contractors.edit', $subContractor) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </x-slot>
    <div class="row">
        <div class="col-lg-12">
            <div class="back-btn pb-3">
                <a href="{{ route('sub-contractors.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>Project Amount</h4>
                    <h2>₹{{ number_format($subContractor->amount_project, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>Total Bills</h4>
                    <h2>₹{{ number_format($subContractor->total_bill_amount, 2) }}</h2>
                    <small>{{ $bills->count() }} bills</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>Remaining</h4>
                    <h2>₹{{ number_format($subContractor->amount_project - $subContractor->total_bill_amount, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sub-Contractor Details Card -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sub-Contractor Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Contractor Name:</strong></td>
                            <td>{{ $subContractor->contractor_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Project Name:</strong></td>
                            <td>{{ $subContractor->project_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Department:</strong></td>
                            <td>{{ $subContractor->department_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date:</strong></td>
                            <td>@formatDate($subContractor->date)</td>
                        </tr>
                        <tr>
                            <td><strong>Work Order Date:</strong></td>
                            <td>@formatDate($subContractor->work_order_date)</td>
                        </tr>
                        <tr>
                            <td><strong>Time Limit:</strong></td>
                            <td>{{ $subContractor->time_limit }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Additional Details Card -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Additional Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>EMD/FDR Detail:</strong>
                        <p>{{ $subContractor->emd_fdr_detail ?: 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Remark:</strong>
                        <p>{{ $subContractor->remark ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bills Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Bills ({{ $bills->count() }})</h5>
            <a href="{{ route('sub-contractor-bills.create', ['sub_contractor_id' => $subContractor->id]) }}" class="btn btn-sm btn-primary">Add New Bill</a>
        </div>
        <div class="p-6 text-gray-900">
            @if($bills->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Bill No</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bills as $index => $bill)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $bill->bill_no ?: 'N/A' }}</strong></td>
                            <td>₹{{ number_format($bill->amount, 2) }}</td>
                            <td>@formatDate($bill->date)</td>
                            <td>{{ $bill->remark ?: 'N/A' }}</td>
                            <td>
                                <a href="{{ route('sub-contractor-bills.edit', $bill) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('sub-contractor-bills.destroy', $bill) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total</strong></td>
                            <td><strong>₹{{ number_format($bills->sum('amount'), 2) }}</strong></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <p class="text-muted">No bills found for this sub-contractor.</p>
                <a href="{{ route('sub-contractor-bills.create', ['sub_contractor_id' => $subContractor->id]) }}" class="btn btn-primary">Add First Bill</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>