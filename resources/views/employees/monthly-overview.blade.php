<x-app-layout>
    <x-slot name="header">
        <div class="monthly-overview-header d-flex justify-content-between align-items-center">
            <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Details
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Monthly Overview: {{ $employee->name }}
            </h2>
        </div>
    </x-slot>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Monthly Summary</h5>
        </div>
        <div class="p-4">
            @if($monthlySummary->count() > 0)
            <div class="row">
                @foreach($monthlySummary as $month)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ $month['month_name'] }}</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Salary:</strong> ₹{{ number_format($month['salary'], 2) }}</p>
                            <p class="mb-1"><strong>Total Upads:</strong> ₹{{ number_format($month['total_upads'], 2) }}</p>
                            <p class="mb-1"><strong>Pending:</strong> ₹{{ number_format($month['pending'], 2) }}</p>
                            <p class="mb-0"><strong>Records:</strong> {{ $month['record_count'] }}</p>

                            <div class="mt-2">
                                <a href="{{ route('employees.show', ['employee' => $employee, 'month' => $month['month_year']]) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4">
                <p>No monthly data found.</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>