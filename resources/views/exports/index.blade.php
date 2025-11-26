<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center" style="row-gap: 30px;">
        <div class="col-lg-6 d-none">
            <!-- Employee Upad Report -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <i class="fas fa-users text-blue-600 mr-2 pe-1"></i>Employee Upad Report
                        </h3>
                        <p class="text-sm text-gray-600">Export employee upad records for a specific date range</p>
                    </div>

                    <form id="upadReportForm" action="{{ route('exports.upad-report') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="mb-2">
                                <label for="upad_employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Employee (Optional)
                                </label>
                                <div class="product-list">
                                    <select name="employee_id" id="upad_employee_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Employees</option>
                                        @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="mb-2">
                                <label for="upad_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" id="upad_start_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="mb-2">
                                <label for="upad_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" id="upad_end_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="flex items-end">
                                <button type="submit" id="upadExportBtn"
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 relative btn-primary">
                                    <span class="btn-text">
                                        <i class="fas fa-download mr-2 px-1"></i>Export PDF
                                    </span>
                                    <span class="btn-loading hidden">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>Generating...
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div id="upadProgress" class="hidden mt-4">
                            <div class="bg-gray-200 rounded-full h-3 mb-2">
                                <div id="upadProgressBar" class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span id="upadProgressText">Preparing report...</span>
                                <span id="upadProgressPercent">0%</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <!-- Transactions Report -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <i class="fas fa-exchange-alt text-green-600 mr-2 pe-1"></i>Transactions Report
                        </h3>
                        <p class="text-sm text-gray-600">Export transaction records with multiple filter options</p>
                    </div>

                    <form id="transactionsReportForm" action="{{ route('exports.transactions-report') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- First Row: Type, Project, Dealer, Sub-Contractor -->
                        <div class="row gy-3">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="trans_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Transaction Type
                                </label>
                                <select name="type" id="trans_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">All Transactions</option>
                                    <option value="incoming">Incoming Only</option>
                                    <option value="outgoing">Outgoing Only</option>
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="trans_project" class="block text-sm font-medium text-gray-700 mb-2">
                                    Project
                                </label>
                                <div class="product-list">
                                    <select name="project_id" id="trans_project"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">All Projects</option>
                                        @foreach(\App\Models\Project::orderBy('name')->get() as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="trans_dealer" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dealer
                                </label>
                                <div class="product-list">
                                    <select name="dealer_id" id="trans_dealer"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">All Dealers</option>
                                        @foreach(\App\Models\Dealer::orderBy('dealer_name')->get() as $dealer)
                                        <option value="{{ $dealer->id }}">{{ $dealer->dealer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="trans_sub_contractor" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sub-Contractor
                                </label>
                                <div class="product-list">
                                    <select name="sub_contractor_id" id="trans_sub_contractor"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">All Sub-Contractors</option>
                                        @foreach(\App\Models\SubContractor::orderBy('contractor_name')->get() as $subContractor)
                                        <option value="{{ $subContractor->id }}">{{ $subContractor->contractor_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="trans_customer" class="block text-sm font-medium text-gray-700 mb-2">
                                    Customer
                                </label>
                                <div class="product-list">
                                    <select name="customer_id" id="trans_customer"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">All Customers</option>
                                        @foreach(\App\Models\Customer::orderBy('name')->get() as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="trans_employee" class="block text-sm font-medium text-gray-700 mb-2">
                                    Employee
                                </label>
                                <div class="product-list">
                                    <select name="employee_id" id="trans_employee"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">All Employees</option>
                                        @foreach(\App\Models\Employee::orderBy('name')->get() as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="trans_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" id="trans_start_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="trans_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" id="trans_end_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <div class="flex items-end">
                                <button type="submit" id="transExportBtn"
                                    class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200 relative btn-primary">
                                    <span class="btn-text">
                                        <i class="fas fa-download mr-2 px-1"></i>Export PDF
                                    </span>
                                    <span class="btn-loading hidden">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>Generating...
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div id="transProgress" class="hidden mt-4">
                            <div class="bg-gray-200 rounded-full h-3 mb-2">
                                <div id="transProgressBar" class="bg-green-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span id="transProgressText">Preparing report...</span>
                                <span id="transProgressPercent">0%</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Success/Error Messages -->
    <div id="successMessage" class="hidden fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50 d-none">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>Report generated successfully!</span>
        </div>
    </div>

    <div id="errorMessage" class="hidden fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span>Error generating report. Please try again.</span>
        </div>
    </div>

    @if ($errors->any())
    <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50" id="error-alert">
        <strong class="font-bold">Error!</strong>
        <ul class="mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <script>
        // Progress simulation and form handling
        document.addEventListener('DOMContentLoaded', function() {
            // Set default dates (last 30 days)
            const today = new Date();
            const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));

            const formatDate = (date) => {
                return date.toISOString().split('T')[0];
            };

            document.getElementById('upad_start_date').value = formatDate(thirtyDaysAgo);
            document.getElementById('upad_end_date').value = formatDate(today);
            document.getElementById('trans_start_date').value = formatDate(thirtyDaysAgo);
            document.getElementById('trans_end_date').value = formatDate(today);

            // Upad Report Form Handler
            document.getElementById('upadReportForm').addEventListener('submit', function(e) {
                e.preventDefault();
                generateReport('upad', this);
            });

            // Transactions Report Form Handler
            document.getElementById('transactionsReportForm').addEventListener('submit', function(e) {
                e.preventDefault();
                generateReport('transactions', this);
            });
        });

        function generateReport(type, form) {
            const btn = document.getElementById(type === 'upad' ? 'upadExportBtn' : 'transExportBtn');
            const progress = document.getElementById(type === 'upad' ? 'upadProgress' : 'transProgress');
            const progressBar = document.getElementById(type === 'upad' ? 'upadProgressBar' : 'transProgressBar');
            const progressText = document.getElementById(type === 'upad' ? 'upadProgressText' : 'transProgressText');
            const progressPercent = document.getElementById(type === 'upad' ? 'upadProgressPercent' : 'transProgressPercent');

            // Show loading state
            btn.disabled = true;
            btn.querySelector('.btn-text').classList.add('hidden');
            btn.querySelector('.btn-loading').classList.remove('hidden');
            progress.classList.remove('hidden');

            // Progress simulation
            let progressValue = 0;
            const progressMessages = [
                'Preparing report...',
                'Fetching data...',
                'Processing records...',
                'Formatting PDF...',
                'Almost done...'
            ];

            const progressInterval = setInterval(() => {
                progressValue += Math.random() * 20;
                if (progressValue > 90) progressValue = 90;

                const messageIndex = Math.floor(progressValue / 20);
                progressBar.style.width = progressValue + '%';
                progressPercent.textContent = Math.round(progressValue) + '%';
                progressText.textContent = progressMessages[messageIndex] || 'Generating PDF...';
            }, 200);

            // Submit form using fetch for better control
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    clearInterval(progressInterval);
                    progressBar.style.width = '100%';
                    progressPercent.textContent = '100%';
                    progressText.textContent = 'Download ready!';

                    if (response.ok) {
                        return response.blob();
                    } else {
                        throw new Error('Network response was not ok');
                    }
                })
                .then(blob => {
                    // Create download link
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;

                    // Generate filename
                    const startDate = form.querySelector('[name="start_date"]').value;
                    const endDate = form.querySelector('[name="end_date"]').value;
                    link.download = `${type}-report-${startDate}-to-${endDate}.pdf`;

                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(url);

                    // Show success message
                    showMessage('success');

                    // Reset form after 2 seconds
                    setTimeout(() => {
                        resetForm(type);
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    clearInterval(progressInterval);
                    showMessage('error');
                    resetForm(type);
                });
        }

        function resetForm(type) {
            const btn = document.getElementById(type === 'upad' ? 'upadExportBtn' : 'transExportBtn');
            const progress = document.getElementById(type === 'upad' ? 'upadProgress' : 'transProgress');
            const progressBar = document.getElementById(type === 'upad' ? 'upadProgressBar' : 'transProgressBar');

            // Reset button
            btn.disabled = false;
            btn.querySelector('.btn-text').classList.remove('hidden');
            btn.querySelector('.btn-loading').classList.add('hidden');

            // Hide progress
            progress.classList.add('hidden');
            progressBar.style.width = '0%';
        }

        function showMessage(type) {
            const successMsg = document.getElementById('successMessage');
            const errorMsg = document.getElementById('errorMessage');

            if (type === 'success') {
                // successMsg.classList.remove('hidden');
                setTimeout(() => {
                    successMsg.classList.add('hidden');
                }, 4000);
            } else {
                // errorMsg.classList.remove('hidden');
                setTimeout(() => {
                    errorMsg.classList.add('hidden');
                }, 4000);
            }
        }

        // Auto-hide error alert
        setTimeout(function() {
            const errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                errorAlert.style.display = 'none';
            }
        }, 5000);
    </script>
</x-app-layout>