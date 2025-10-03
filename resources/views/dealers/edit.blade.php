<x-app-layout>
    <x-slot name="header">
        <div class="module-edit-page d-flex justify-content-between align-items-center">
            <!-- Back Button -->
            <a href="{{ route('dealers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Dealer: ') . $dealer->dealer_name }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('dealers.update', $dealer) }}">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dealer_name" class="form-label">Dealer Name <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('dealer_name') is-invalid @enderror"
                            id="dealer_name" name="dealer_name" value="{{ old('dealer_name', $dealer->dealer_name) }}" required>
                        @error('dealer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="mobile_no" class="form-label">Mobile No <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('mobile_no') is-invalid @enderror"
                            id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $dealer->mobile_no) }}" required>
                        @error('mobile_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="gst" class="form-label">GST</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('gst') is-invalid @enderror"
                            id="gst" name="gst" value="{{ old('gst', $dealer->gst) }}">
                        @error('gst')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <!-- Bank Accounts Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-university"></i> Bank Accounts
                        </h5>
                        <button type="button" class="btn btn-success btn-sm" id="addBankAccount">
                            <i class="fas fa-plus"></i> Add Bank Account
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="bankAccountsContainer">
                            <!-- Existing bank accounts will be populated here -->
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('address') is-invalid @enderror"
                        id="address" name="address" rows="3" required>{{ old('address', $dealer->address) }}</textarea>
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('dealers.show', $dealer) }}" class="btn btn-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">Update Dealer</button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            let bankAccountIndex = 0;

            // Existing bank accounts data
            const existingBankAccounts = @json($dealer -> bankAccounts);

            // Bank account template
            function getBankAccountTemplate(index, data = {}) {
                return `
                    <div class="bank-account-item border rounded p-3 mb-3" data-index="${index}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Bank Account #${index + 1}</h6>
                            <button type="button" class="btn btn-danger btn-sm remove-bank-account">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>

                        ${data.id ? `<input type="hidden" name="bank_accounts[${index}][id]" value="${data.id}">` : ''}

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                <input type="text" name="bank_accounts[${index}][account_name]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control " value="${data.account_name || ''}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                <input type="text" name="bank_accounts[${index}][account_no]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control " value="${data.account_no || ''}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" name="bank_accounts[${index}][bank_name]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control " value="${data.bank_name || ''}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                <input type="text" name="bank_accounts[${index}][ifsc]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control " value="${data.ifsc || ''}" 
                                       style="text-transform: uppercase;" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="bank_accounts[${index}][notes]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" 
                                          rows="2" placeholder="Any additional notes...">${data.notes || ''}</textarea>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Add bank account
            function addBankAccount(data = {}) {
                const template = getBankAccountTemplate(bankAccountIndex, data);
                $('#bankAccountsContainer').append(template);
                updateRemoveButtons();
                bankAccountIndex++;
            }

            // Update remove button visibility
            function updateRemoveButtons() {
                const accountItems = $('.bank-account-item');
                accountItems.each(function(index) {
                    const removeBtn = $(this).find('.remove-bank-account');
                    if (accountItems.length > 1) {
                        removeBtn.show();
                    } else {
                        removeBtn.hide();
                    }
                });
            }

            // Load existing bank accounts
            if (existingBankAccounts.length > 0) {
                existingBankAccounts.forEach(function(account) {
                    addBankAccount(account);
                });
            } else {
                addBankAccount(); // Add at least one empty account
            }

            // Add bank account button
            $('#addBankAccount').on('click', function() {
                addBankAccount();
            });

            // Remove bank account
            $(document).on('click', '.remove-bank-account', function() {
                if ($('.bank-account-item').length > 1) {
                    $(this).closest('.bank-account-item').remove();
                    updateRemoveButtons();
                } else {
                    alert('At least one bank account is required.');
                }
            });

            // Form validation
            $('#dealerForm').on('submit', function(e) {
                const bankAccounts = $('.bank-account-item').length;
                if (bankAccounts === 0) {
                    e.preventDefault();
                    alert('At least one bank account is required.');
                    return false;
                }

                // Show loading state
                $(this).find('button[type="submit"]').html(
                    '<i class="fas fa-spinner fa-spin"></i> Updating...'
                ).prop('disabled', true);
            });


            // IFSC code validation
            $(document).on('input', 'input[name*="[ifsc]"]', function() {
                this.value = this.value.toUpperCase();
            });
        });
    </script>
    @endpush
</x-app-layout>