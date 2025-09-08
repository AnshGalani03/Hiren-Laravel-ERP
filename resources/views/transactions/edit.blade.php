<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit ') . ucfirst($transaction->type) . __(' Transaction') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('transactions.update', $transaction) }}" id="transactionForm">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Transaction Type</label>
                        <div class="form-control-static">
                            <span class="badge bg-{{ $transaction->type == 'incoming' ? 'success' : 'danger' }} fs-6">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </div>
                        <input type="hidden" name="type" value="{{ $transaction->type }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                            id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}" required>
                        @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                            id="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        @if($transaction->type == 'incoming')
                        <label for="incoming_id" class="form-label">Income Category <span class="text-danger">*</span></label>
                        <select class="form-control @error('incoming_id') is-invalid @enderror" id="incoming_id" name="incoming_id" required>
                            <option value="">Select Income Category</option>
                            @foreach($incomings as $incoming)
                            <option value="{{ $incoming->id }}" {{ old('incoming_id', $transaction->incoming_id) == $incoming->id ? 'selected' : '' }}>
                                {{ $incoming->name }}
                            </option>
                            @endforeach
                        </select>
                        @else
                        <label for="outgoing_id" class="form-label">Expense Category <span class="text-danger">*</span></label>
                        <select class="form-control @error('outgoing_id') is-invalid @enderror" id="outgoing_id" name="outgoing_id" required>
                            <option value="">Select Expense Category</option>
                            @foreach($outgoings as $outgoing)
                            <option value="{{ $outgoing->id }}" {{ old('outgoing_id', $transaction->outgoing_id) == $outgoing->id ? 'selected' : '' }}>
                                {{ $outgoing->name }}
                            </option>
                            @endforeach
                        </select>
                        @endif
                        @error('incoming_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('outgoing_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('description') is-invalid @enderror"
                        id="description" name="description" value="{{ old('description', $transaction->description) }}" required
                        placeholder="Brief description of the transaction">
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Link to Project/Dealer -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Link to Project or Dealer (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="project_id" class="form-label">Link to Project</label>
                                <select class="form-control @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                                    <option value="">Select Project (Optional)</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $transaction->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }} - {{ $project->department_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dealer_id" class="form-label">Link to Dealer</label>
                                <select class="form-control @error('dealer_id') is-invalid @enderror" id="dealer_id" name="dealer_id">
                                    <option value="">Select Dealer (Optional)</option>
                                    @foreach($dealers as $dealer)
                                    <option value="{{ $dealer->id }}" {{ old('dealer_id', $transaction->dealer_id) == $dealer->id ? 'selected' : '' }}>
                                        {{ $dealer->dealer_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('dealer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea class="form-control @error('remark') is-invalid @enderror"
                        id="remark" name="remark" rows="3">{{ old('remark', $transaction->remark) }}</textarea>
                    @error('remark')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update {{ ucfirst($transaction->type) }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>