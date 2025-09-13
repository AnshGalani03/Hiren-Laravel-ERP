<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="text-lg font-semibold mb-4">Overview</h3>
                </div>
            </div>
            <div class="row">
                <!-- Dealers Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Total Dealers</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Dealer::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('dealers.index') }}" class="btn dashboard-card-btn">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employees Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Total Employees</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Employee::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('employees.index') }}" class="btn dashboard-card-btn">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Customers Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Total Customers</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Customer::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('customers.index') }}" class="btn dashboard-card-btn">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projects Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Active Projects</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Project::where('active', true)->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('projects.index') }}" class="btn dashboard-card-btn">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tenders Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Total Tenders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Tender::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('tenders.index') }}" class="btn dashboard-card-btn">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sub-Contractors Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Sub-Contractors</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\SubContractor::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('sub-contractors.index') }}" class="btn dashboard-card-btn">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="col-xl-3 col-md-6 mb-4 d-none">
                    <div class="card dashboard-card h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Total Products</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Product::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-box fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('products.index') }}" class="btn dashboard-card-btn">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- All Invoices Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Dealers Invoices</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Invoice::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('invoices.index') }}" class="btn dashboard-card-btn">View All</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- All Bills Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card dashboard-card h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Bills</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Bill::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-receipt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('bills.index') }}" class="btn dashboard-card-btn">View All</a>
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
                            <div class="card mb-0 w-100 dashboard-quick-card">
                                <div class="card-body ">
                                    <a href="{{ route('dealers.create') }}" class="btn w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="#000" d="M17 13h-4v4h-2v-4H7v-2h4V7h2v4h4m-5-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" stroke-width="0.5" stroke="#000" />
                                        </svg>
                                        Add Dealer
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card mb-0 w-100 dashboard-quick-card">
                                <div class="card-body ">
                                    <a href="{{ route('employees.create') }}" class="btn w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="#000" d="M4.5 8.552c0 1.995 1.505 3.5 3.5 3.5s3.5-1.505 3.5-3.5s-1.505-3.5-3.5-3.5s-3.5 1.505-3.5 3.5M19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1z" stroke-width="0.5" stroke="#000" />
                                        </svg>
                                        Add Employee
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card mb-0 w-100 dashboard-quick-card">
                                <div class="card-body">
                                    <a href="{{ route('customers.create') }}" class="btn w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="#000" d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" stroke-width="0.5" stroke="#000" />
                                        </svg>
                                        Add Customer
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card mb-0 w-100 dashboard-quick-card">
                                <div class="card-body ">
                                    <a href="{{ route('projects.create') }}" class="btn w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="24" viewBox="0 0 640 512">
                                            <path fill="#000" d="M384 320H256c-17.67 0-32 14.33-32 32v128c0 17.67 14.33 32 32 32h128c17.67 0 32-14.33 32-32V352c0-17.67-14.33-32-32-32M192 32c0-17.67-14.33-32-32-32H32C14.33 0 0 14.33 0 32v128c0 17.67 14.33 32 32 32h95.72l73.16 128.04C211.98 300.98 232.4 288 256 288h.28L192 175.51V128h224V64H192zM608 0H480c-17.67 0-32 14.33-32 32v128c0 17.67 14.33 32 32 32h128c17.67 0 32-14.33 32-32V32c0-17.67-14.33-32-32-32" stroke-width="13" stroke="#000" />
                                        </svg>
                                        Add Project
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card mb-0 w-100 dashboard-quick-card">
                                <div class="card-body">
                                    <a href="{{ route('tenders.create') }}" class="btn w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="#000" d="M14 2H6c-1.11 0-2 .89-2 2v16c0 1.11.89 2 2 2h7.81c-.53-.91-.81-1.95-.81-3c0-3.31 2.69-6 6-6c.34 0 .67.03 1 .08V8zm-1 7V3.5L18.5 9zm10 11h-3v3h-2v-3h-3v-2h3v-3h2v3h3z" stroke-width="0.5" stroke="#000" />
                                        </svg>
                                        Add Tender
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card mb-0 w-100 dashboard-quick-card">
                                <div class="card-body">
                                    <a href="{{ route('sub-contractors.create') }}" class="btn w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" viewBox="0 0 640 512">
                                            <path fill="#000" d="M434.7 64h-85.9c-8 0-15.7 3-21.6 8.4l-98.3 90c-.1.1-.2.3-.3.4c-16.6 15.6-16.3 40.5-2.1 56c12.7 13.9 39.4 17.6 56.1 2.7c.1-.1.3-.1.4-.2l79.9-73.2c6.5-5.9 16.7-5.5 22.6 1c6 6.5 5.5 16.6-1 22.6l-26.1 23.9L504 313.8c2.9 2.4 5.5 5 7.9 7.7V128l-54.6-54.6c-5.9-6-14.1-9.4-22.6-9.4M544 128.2v223.9c0 17.7 14.3 32 32 32h64V128.2zm48 223.9c-8.8 0-16-7.2-16-16s7.2-16 16-16s16 7.2 16 16s-7.2 16-16 16M0 384h64c17.7 0 32-14.3 32-32V128.2H0zm48-63.9c8.8 0 16 7.2 16 16s-7.2 16-16 16s-16-7.2-16-16c0-8.9 7.2-16 16-16m435.9 18.6L334.6 217.5l-30 27.5c-29.7 27.1-75.2 24.5-101.7-4.4c-26.9-29.4-24.8-74.9 4.4-101.7L289.1 64h-83.8c-8.5 0-16.6 3.4-22.6 9.4L128 128v223.9h18.3l90.5 81.9c27.4 22.3 67.7 18.1 90-9.3l.2-.2l17.9 15.5c15.9 13 39.4 10.5 52.3-5.4l31.4-38.6l5.4 4.4c13.7 11.1 33.9 9.1 45-4.7l9.5-11.7c11.2-13.8 9.1-33.9-4.6-45.1" stroke-width="13" stroke="#000" />
                                        </svg>
                                        Add Sub-Contractor
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card mb-0 w-100 dashboard-quick-card">
                                <div class="card-body">
                                    <a href="{{ route('products.create') }}" class="btn w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <g fill="#000" stroke-width="0.5" stroke="#000">
                                                <path d="M12.268 6A2 2 0 0 0 14 9h1v1a2 2 0 0 0 3.04 1.708l-.311 1.496a1 1 0 0 1-.979.796H8.605l.208 1H16a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L4.686 5H4a1 1 0 0 1 0-2h1.5a1 1 0 0 1 .979.796L6.939 6z" />
                                                <path d="M18 4a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0V8h2a1 1 0 1 0 0-2h-2z" />
                                            </g>
                                        </svg>
                                        Add Product
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card mb-0 w-100 dashboard-quick-card">
                                <div class="card-body">
                                    <a href="{{ route('bills.create') }}" class="btn w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <g fill="#000" stroke-width="0.5" stroke="#000">
                                                <path d="m12 2l.117.007a1 1 0 0 1 .876.876L13 3v4l.005.15a2 2 0 0 0 1.838 1.844L15 9h4l.117.007a1 1 0 0 1 .876.876L20 10v9a3 3 0 0 1-2.824 2.995L17 22H7a3 3 0 0 1-2.995-2.824L4 19V5a3 3 0 0 1 2.824-2.995L7 2zm4 15h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2m0-4H8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2M9 6H8a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2" />
                                                <path d="M19 7h-4l-.001-4.001z" />
                                            </g>
                                        </svg>
                                        Add Bill
                                    </a>
                                </div>
                            </div>
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