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
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SubContractorController;
use App\Http\Controllers\SubContractorBillController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dealer Routes
    Route::resource('dealers', DealerController::class);

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

    // Upad Routes
    Route::get('upads/create', [UpadController::class, 'create'])->name('upads.create');
    Route::post('upads', [UpadController::class, 'store'])->name('upads.store');
    Route::get('upads/{upad}/edit', [UpadController::class, 'edit'])->name('upads.edit');
    Route::patch('upads/{upad}', [UpadController::class, 'update'])->name('upads.update');
    Route::delete('upads/{upad}', [UpadController::class, 'destroy'])->name('upads.destroy');

    // Project Routes
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/assign-employee', [ProjectController::class, 'assignEmployee'])->name('projects.assign-employee');
    Route::post('projects/{project}/remove-employee', [ProjectController::class, 'removeEmployee'])->name('projects.remove-employee');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    // Project Expense & Income Routes
    Route::resource('project-expenses', ProjectExpenseController::class);
    Route::resource('project-incomes', ProjectIncomeController::class);

    // Add this route for toggling project status
    Route::get('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');


    // Tender Routes
    Route::resource('tenders', TenderController::class);
    Route::get('tenders/{tender}', [TenderController::class, 'show'])->name('tenders.show');

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

    // Sub-contractor Bill Routes
    Route::get('sub-contractor-bills/create', [SubContractorBillController::class, 'create'])->name('sub-contractor-bills.create');
    Route::post('sub-contractor-bills', [SubContractorBillController::class, 'store'])->name('sub-contractor-bills.store');
    Route::get('sub-contractor-bills/{subContractorBill}/edit', [SubContractorBillController::class, 'edit'])->name('sub-contractor-bills.edit');
    Route::patch('sub-contractor-bills/{subContractorBill}', [SubContractorBillController::class, 'update'])->name('sub-contractor-bills.update');
    Route::delete('sub-contractor-bills/{subContractorBill}', [SubContractorBillController::class, 'destroy'])->name('sub-contractor-bills.destroy');

    // Project Transactions Data in Data table
    Route::get('projects/{project}/transactions-data', [ProjectController::class, 'transactionsData'])->name('projects.transactions-data');

    // Dealer Invoices and Transactions Data in Data table
    Route::get('dealers/{dealer}/invoices-data', [DealerController::class, 'invoicesData'])->name('dealers.invoices-data');
    Route::get('dealers/{dealer}/transactions-data', [DealerController::class, 'transactionsData'])->name('dealers.transactions-data');
});

require __DIR__ . '/auth.php';
