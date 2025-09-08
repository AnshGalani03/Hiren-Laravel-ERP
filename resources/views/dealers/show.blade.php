<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dealer Details: ') . $dealer->dealer_name }}
            </h2>
            <div>
                <a href="{{ route('invoices.create', ['dealer_id' => $dealer->id]) }}" class="btn btn-success">Add Invoice</a>
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'dealer_id' => $dealer->id]) }}" class="btn btn-info">Add Transaction</a>
                <a href="{{ route('dealers.edit', $dealer) }}" class="btn btn-warning">Edit Dealer</a>
            </div>
        </div>
    </x-slot>

    <!-- Financial Summary -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="back-btn pb-3">
                <a href="{{ route('dealers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>Total Invoices</h4>
                    <h2>₹{{ number_format($totalInvoices, 2) }}</h2>
                    <small>{{ $invoices->count() }} invoices</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>Total Transactions</h4>
                    <h2>₹{{ number_format($totalTransactions, 2) }}</h2>
                    <small>{{ $transactions->count() }} transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>Grand Total</h4>
                    <h2>₹{{ number_format($grandTotal, 2) }}</h2>
                    <small>All activities</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing dealer details cards -->
    <div class="row">
        <!-- ... existing dealer info and bank details cards ... -->
    </div>

    <!-- Invoices Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Invoices ({{ $invoices->count() }})</h5>
            <a href="{{ route('invoices.create', ['dealer_id' => $dealer->id]) }}" class="btn btn-sm btn-primary">Add New Invoice</a>
        </div>
        <div class="p-6 text-gray-900">
            @if($invoices->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->bill_no }}</td>
                            <td>₹{{ number_format($invoice->amount, 2) }}</td>
                            <td>@formatDate($invoice->date)</td>
                            <td>{{ $invoice->remark ?? '—' }}</td>
                            <td>
                                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" style="display:inline;">
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
                            <td><strong>Total Invoices</strong></td>
                            <td><strong>₹{{ number_format($totalInvoices, 2) }}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <p class="text-muted">No invoices found for this dealer.</p>
                <a href="{{ route('invoices.create', ['dealer_id' => $dealer->id]) }}" class="btn btn-primary">Add First Invoice</a>
            </div>
            @endif
        </div>
    </div>

    <!-- NEW: All Transactions Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Transactions ({{ $transactions->count() }})</h5>
            <div>
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'dealer_id' => $dealer->id]) }}" class="btn btn-sm btn-success">Add Income</a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing', 'dealer_id' => $dealer->id]) }}" class="btn btn-sm btn-danger">Add Expense</a>
            </div>
        </div>
        <div class="p-6 text-gray-900">
            @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Linked Project</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>
                                <span class="badge bg-{{ $transaction->type == 'incoming' ? 'success' : 'danger' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td>
                                @if($transaction->type == 'incoming' && $transaction->incoming)
                                {{ $transaction->incoming->name }}
                                @elseif($transaction->type == 'outgoing' && $transaction->outgoing)
                                {{ $transaction->outgoing->name }}
                                @else
                                N/A
                                @endif
                            </td>
                            <td>{{ $transaction->description }}</td>
                            <td>₹{{ number_format($transaction->amount, 2) }}</td>
                            <td>@formatDate($transaction->date)</td>
                            <td>{{ $transaction->project ? $transaction->project->name : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" style="display:inline;">
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
                            <td colspan="3"><strong>Total Transactions</strong></td>
                            <td><strong>₹{{ number_format($totalTransactions, 2) }}</strong></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <p class="text-muted">No transactions found for this dealer.</p>
                <a href="{{ route('transactions.create', ['type' => 'incoming', 'dealer_id' => $dealer->id]) }}" class="btn btn-success me-2">Add First Income</a>
                <a href="{{ route('transactions.create', ['type' => 'outgoing', 'dealer_id' => $dealer->id]) }}" class="btn btn-danger">Add First Expense</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>