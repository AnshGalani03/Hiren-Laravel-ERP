<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Bill for: ') . $subContractor->contractor_name }}
            </h2>
            <a class="btn btn-secondary" href="{{ route('sub-contractors.show', $subContractor->id) }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </x-slot>

    @if ($errors->any())
    <div class="alert alert-danger d-none">
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
            <form action="{{ route('sub-contractor-bills.store') }}" method="POST">
                @csrf
                <input type="hidden" name="sub_contractor_id" value="{{ $subContractor->id }}">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="bill_no" class="form-label">Bill Number <span class="text-danger">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('bill_no') is-invalid @enderror" id="bill_no" name="bill_no"
                            value="{{ old('bill_no') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control @error('amount') is-invalid @enderror" id="amount" name="amount"
                            value="{{ old('amount') }}" step="0.01" min="0" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="date" class="form-label">Bill Date <span class="text-danger">*</span></label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="date" name="date"
                            value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm form-control" id="remark" name="remark" rows="3"
                            placeholder="Additional remarks...">{{ old('remark') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('sub-contractors.show', $subContractor->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Create Bill & Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>