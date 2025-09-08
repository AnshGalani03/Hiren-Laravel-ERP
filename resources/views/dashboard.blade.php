<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ERP Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="row">
                <!-- Dealers Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Dealers</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Dealer::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('dealers.index') }}" class="btn btn-primary btn-sm">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employees Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Employees</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Employee::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('employees.index') }}" class="btn btn-success btn-sm">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projects Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Active Projects</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Project::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('projects.index') }}" class="btn btn-info btn-sm">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tenders Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Tenders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Tender::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('tenders.index') }}" class="btn btn-warning btn-sm">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sub-Contractors Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-secondary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                        Sub-Contractors</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\SubContractor::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('sub-contractors.index') }}" class="btn btn-secondary btn-sm">View All</a>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('dealers.create') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-plus-circle"></i><br>
                                Add Dealer
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('employees.create') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-user-plus"></i><br>
                                Add Employee
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('projects.create') }}" class="btn btn-info btn-lg w-100">
                                <i class="fas fa-project-diagram"></i><br>
                                Add Project
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('tenders.create') }}" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-file-plus"></i><br>
                                Add Tender
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
    </style>
</x-app-layout>