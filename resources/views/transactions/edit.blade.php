<x-app-layout>
    <x-slot name="header">
        <div class="module-edit-page d-flex justify-content-between align-items-center">
            <a class="btn btn-outline-secondary" href="{{ route('transactions.index') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Transaction') }}
            </h2>
        </div>
    </x-slot>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="transaction-module-form">
                    <!-- Transaction Type -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="incoming" {{ old('type', $transaction->type) == 'incoming' ? 'selected' : '' }}>
                                    Incoming
                                </option>
                                <option value="outgoing" {{ old('type', $transaction->type) == 'outgoing' ? 'selected' : '' }}>
                                    Outgoing
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="amount" name="amount"
                                value="{{ old('amount', $transaction->amount) }}" step="0.01" min="0" required>
                        </div>
                    </div>

                    <!-- Date and Description -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="date" name="date"
                                value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="description" name="description"
                                value="{{ old('description', $transaction->description) }}" required>
                        </div>
                    </div>

                    <!-- Categories (Incoming/Outgoing) -->
                    <div class="row mb-4">
                        <div class="col-md-12" id="incoming-category" style="display: none;">
                            <div class="transactions-filter-wrapper">
                                <label for="incoming_id" class="d-block form-label">Incoming Category <span class="text-danger">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="incoming_id" name="incoming_id" required>
                                    <option value="">Select Incoming Category</option>
                                    @foreach($incomings as $incoming)
                                    <option value="{{ $incoming->id }}" {{ old('incoming_id', $transaction->incoming_id) == $incoming->id ? 'selected' : '' }}>
                                        {{ $incoming->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" id="outgoing-category" style="display: none;">
                            <div class="transactions-filter-wrapper">
                                <label for="outgoing_id" class="d-block form-label">Outgoing Category <span class="text-danger">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="outgoing_id" name="outgoing_id" required>
                                    <option value="">Select Outgoing Category</option>
                                    @foreach($outgoings as $outgoing)
                                    <option value="{{ $outgoing->id }}" {{ old('outgoing_id', $transaction->outgoing_id) == $outgoing->id ? 'selected' : '' }}>
                                        {{ $outgoing->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Linked Entities -->
                    <div class="row mb-4 gy-3">
                        <div class="col-md-6">
                            <div class="transactions-filter-wrapper">
                                <label for="project_id" class="form-label">Project</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="project_id" name="project_id">
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $transaction->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="transactions-filter-wrapper">
                                <label for="dealer_id" class="form-label">Dealer</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="dealer_id" name="dealer_id">
                                    <option value="">Select Dealer</option>
                                    @foreach($dealers as $dealer)
                                    <option value="{{ $dealer->id }}" {{ old('dealer_id', $transaction->dealer_id) == $dealer->id ? 'selected' : '' }}>
                                        {{ $dealer->dealer_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="transactions-filter-wrapper">
                                <label for="sub_contractor_id" class="form-label">Sub-Contractor</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="sub_contractor_id" name="sub_contractor_id">
                                    <option value="">Select Sub-Contractor</option>
                                    @foreach($subContractors as $subContractor)
                                    <option value="{{ $subContractor->id }}" {{ old('sub_contractor_id', $transaction->sub_contractor_id) == $subContractor->id ? 'selected' : '' }}>
                                        {{ $subContractor->contractor_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="transactions-filter-wrapper">
                                <label for="customer_id" class="form-label">Customer</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="customer_id" name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ (old('customer_id', $transaction->customer_id) == $customer->id) ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- 🔥 ADDED: Employee Selection --}}
                        <div class="col-md-6">
                            <div class="transactions-filter-wrapper">
                                <label for="employee_id" class="form-label">Employee</label>
                                <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('employee_id', $transaction->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>

                    <div class="mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('remark') is-invalid @enderror"
                            id="remark" name="remark" rows="3">{{ old('remark', $transaction->remark) }}</textarea>
                        @error('remark')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            Update Transaction
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            // Function to toggle category fields based on type
            function toggleCategoryFields() {
                const type = $('#type').val();

                if (type === 'incoming') {
                    $('#incoming-category').show();
                    $('#outgoing-category').hide();
                    $('#incoming_id').attr('required', true);
                    $('#outgoing_id').attr('required', false);
                } else if (type === 'outgoing') {
                    $('#incoming-category').hide();
                    $('#outgoing-category').show();
                    $('#incoming_id').attr('required', false);
                    $('#outgoing_id').attr('required', true);
                } else {
                    $('#incoming-category').hide();
                    $('#outgoing-category').hide();
                    $('#incoming_id').attr('required', false);
                    $('#outgoing_id').attr('required', false);
                }
            }

            // Initialize on page load
            toggleCategoryFields();

            // Handle type change
            $('#type').change(function() {
                toggleCategoryFields();
            });
        });
    </script>
    @endpush

</x-app-layout>