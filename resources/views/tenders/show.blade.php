<x-app-layout>
    <x-slot name="header">
        <div class="module-detail-page d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tender Details: ') . $tender->work_name }}
            </h2>
            <div>
                <a href="{{ route('tenders.edit', $tender) }}" class="btn btn-warning">Edit Tender</a>
                <form action="{{ route('tenders.destroy', $tender) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Tender</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="back-btn pb-3">
                <a href="{{ route('tenders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <!-- Tender Details Card -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tender Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Work Name:</strong></td>
                            <td>{{ $tender->work_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Department:</strong></td>
                            <td>{{ $tender->department }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date:</strong></td>
                            <td>{{ $tender->date ? $tender->date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>EMD/FDR Amount:</strong></td>
                            <td>₹{{ number_format($tender->amount_emd_fdr, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>DD Amount:</strong></td>
                            <td>₹{{ number_format($tender->amount_dd, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Above/Below:</strong></td>
                            <td>
                                <span class="badge {{ $tender->above_below == 'Above' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $tender->above_below }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Result:</strong></td>
                            <td>
                                @if($tender->result == 'Won')
                                <span class="badge bg-success">{{ $tender->result }}</span>
                                @elseif($tender->result == 'Lost')
                                <span class="badge bg-danger">{{ $tender->result }}</span>
                                @elseif($tender->result == 'Pending')
                                <span class="badge bg-warning">{{ $tender->result }}</span>
                                @else
                                <span class="text-muted">Not Set</span>
                                @endif
                            </td>
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
                        <strong>Remark:</strong>
                        <p>{{ $tender->remark ?: 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Return Detail:</strong>
                        <p>{{ $tender->return_detail ?: 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Created At:</strong>
                        <p>{{ $tender->created_at->format('d-m-Y H:i:s') }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <p>{{ $tender->updated_at->format('d-m-Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>