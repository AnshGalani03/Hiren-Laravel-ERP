<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UpadController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectExpenseController;
use App\Http\Controllers\ProjectIncomeController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\OutgoingController;
use App\Http\Controllers\IncomingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SubContractorController;
use App\Http\Controllers\SubContractorBillController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\RABillController;

//Add Login Route in main page
Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dealer Routes
    Route::resource('dealers', DealerController::class);
    Route::get('dealers/{dealer}/invoices-data', [DealerController::class, 'invoicesData'])->name('dealers.invoices-data');
    Route::get('dealers/{dealer}/transactions-data', [DealerController::class, 'transactionsData'])->name('dealers.transactions-data');

    // Recovery Routes for Dealers
    Route::get('dealers-trashed', [DealerController::class, 'trashed'])->name('dealers.trashed');
    Route::post('dealers/{id}/restore', [DealerController::class, 'restore'])->name('dealers.restore');
    Route::delete('dealers/{id}/force-delete', [DealerController::class, 'forceDelete'])->name('dealers.force-delete');

    // Invoice Routes
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::patch('invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('invoices/summary', [InvoiceController::class, 'summary'])->name('invoices.summary');

    // Employee Routes
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/{employee}/monthly-upads', [UpadController::class, 'monthlyView'])->name('employees.monthly-upads');
    Route::get('employees/{employee}/monthly-overview', [EmployeeController::class, 'monthlyOverview'])->name('employees.monthly-overview');

    // Upad Routes
    Route::resource('upads', UpadController::class);
    Route::get('upads/monthly-report', [UpadController::class, 'monthlyReport'])->name('upads.monthly-report');
    Route::patch('upads/{upad}/payment-status', [UpadController::class, 'updatePaymentStatus'])->name('upads.updatePaymentStatus');

    // Project Routes
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/assign-employee', [ProjectController::class, 'assignEmployee'])->name('projects.assign-employee');
    Route::post('projects/{project}/remove-employee', [ProjectController::class, 'removeEmployee'])->name('projects.remove-employee');
    Route::get('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
    Route::get('projects/{project}/transactions-data', [ProjectController::class, 'transactionsData'])->name('projects.transactions-data');

    // Project Expense & Income Routes
    Route::resource('project-expenses', ProjectExpenseController::class);
    Route::resource('project-incomes', ProjectIncomeController::class);

    // Tender Routes
    Route::resource('tenders', TenderController::class);

    // Daily Kharcha Routes
    Route::resource('outgoings', OutgoingController::class);
    Route::resource('incomings', IncomingController::class);

    // Transaction Routes
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::patch('transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('transactions/summary', [TransactionController::class, 'summary'])->name('transactions.summary');

    // Sub-contractor Routes
    Route::resource('sub-contractors', SubContractorController::class);
    Route::get('sub-contractors/{subContractor}/bills-data', [SubContractorController::class, 'billsData'])->name('sub-contractors.bills-data');

    // Sub-contractor Bill Routes (Fixed: Use proper resource naming)
    Route::resource('sub-contractor-bills', SubContractorBillController::class)->except(['index', 'show']);

    // Product Routes
    Route::resource('products', ProductController::class);

    // Customer Routes
    Route::resource('customers', CustomerController::class);

    // Bills Routes (Fixed: Moved to end to avoid conflicts)
    Route::resource('bills', BillController::class);
    Route::get('bills/{bill}/pdf', [BillController::class, 'generatePDF'])->name('bills.pdf');
    Route::post('bills/{bill}/update-status', [BillController::class, 'updateStatus'])->name('bills.updateStatus');

    // Sub-contractor specific bill deletion (Fixed: Use proper URL structure)
    Route::delete('sub-contractors/{subContractor}/bills/{bill}', [SubContractorController::class, 'deleteBill'])
        ->name('sub-contractors.bills.destroy');

    // Exports report route
    Route::get('exports', [ExportController::class, 'index'])->name('exports.index');
    Route::post('exports/upad-report', [ExportController::class, 'exportUpadReport'])->name('exports.upad-report');
    Route::post('exports/transactions-report', [ExportController::class, 'exportTransactionsReport'])->name('exports.transactions-report');

    // RA Bill Routes
    Route::resource('ra-bills', RABillController::class);
    Route::get('ra-bills/{id}/download-pdf', [RABillController::class, 'downloadPdf'])
        ->name('ra-bills.download-pdf');
    Route::post('ra-bills/calculate', [RABillController::class, 'calculateAmounts'])
        ->name('ra-bills.calculate');

    // RA Bill Recovery Routes
    Route::get('ra-bills-trashed', [RABillController::class, 'trashed'])->name('ra-bills.trashed');
    Route::post('ra-bills/{id}/restore', [RABillController::class, 'restore'])->name('ra-bills.restore');
    Route::delete('ra-bills/{id}/force-delete', [RABillController::class, 'forceDelete'])->name('ra-bills.force-delete');
});

require __DIR__ . '/auth.php';
