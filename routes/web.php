<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\LoanController;
use App\Http\Controllers\Backend\RepaymentController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ActivityLogController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\LoanProductController;
use App\Http\Controllers\Backend\GuarantorController;
use App\Http\Controllers\Backend\LoanApplicationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login.index');
    Route::get('/forgot-password', 'forgot_password')->name('login.forgot_password');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::controller(DashboardController::class)->group(function () {
    Route::get('/', 'index')->name('dashboard.index');
    Route::get('/dashboard', 'index')->name('dashboard.index');
});

/*
|--------------------------------------------------------------------------
| Customers
|--------------------------------------------------------------------------
*/
Route::controller(CustomerController::class)->group(function () {
    Route::get('/customer', 'index')->name('customer.index');
    Route::get('/customer/create', 'create')->name('customer.create');
    Route::post('/customer', 'store')->name('customer.store');
    Route::get('/customer/show/{customer}', 'show')->name('customer.show');
    Route::get('/customer/edit/{customer}', 'edit')->name('customer.edit');
    Route::put('/customer/{customer}', 'update')->name('customer.update');
    Route::delete('/customer/{customer}', 'destroy')->name('customer.destroy');
});

/*
|--------------------------------------------------------------------------
| Loans
|--------------------------------------------------------------------------
*/
Route::controller(LoanController::class)->group(function () {
    Route::get('/loans', 'index')->name('loans.index');
    Route::get('/loans/create', 'create')->name('loans.create');
    Route::post('/loans', 'store')->name('loans.store');
    Route::get('/loans/defaulted', 'defaulted')->name('loans.defaulted');
    Route::get('/loans/customer-eligibility', 'checkCustomerEligibility')->name('loans.customer_eligibility');
    Route::get('/loans/{id}', 'show')->name('loans.show');
    Route::get('/loans/edit/{id}', 'edit')->name('loans.edit');
    Route::put('/loans/{id}', 'update')->name('loans.update');
    Route::delete('/loans/{id}', 'destroy')->name('loans.destroy');
    Route::post('/loans/{id}/submit-review', 'submitForReview')->name('loans.submit_review');
    Route::get('/loans/review/{id}', 'review')->name('loans.review');
    Route::post('/loans/{id}/approve', 'approve')->name('loans.approve');
    Route::post('/loans/{id}/reject', 'reject')->name('loans.reject');
    Route::post('/loans/{id}/disburse', 'disburse')->name('loans.disburse');
    Route::get('/loans/payments/{id}', 'payments')->name('loans.payments');
});

/*
|--------------------------------------------------------------------------
| Repayments
|--------------------------------------------------------------------------
*/
Route::controller(RepaymentController::class)->group(function () {
    Route::get('/repayments', 'index')->name('repayments.index');
    Route::post('/repayments/store', 'store')->name('repayments.store');
    Route::get('/repayments/overdue', 'overdue')->name('repayments.overdue');
    Route::get('/repayments/loan/{id}', 'show')->name('repayments.show');
    Route::get('/repayments/create/{loan_id}', 'create')->name('repayments.create');
    Route::get('/repayments/{id}/edit', 'edit')->name('repayments.edit');
});

/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/
Route::controller(ReportController::class)->group(function () {
    Route::get('/report', 'index')->name('report.index');
});

/*
|--------------------------------------------------------------------------
| Notifications
|--------------------------------------------------------------------------
*/
Route::controller(NotificationController::class)->group(function () {
    Route::get('/notification', 'index')->name('notification.index');
});

/*
|--------------------------------------------------------------------------
| Role
|--------------------------------------------------------------------------
*/
Route::controller(RoleController::class)->group(function () {
    Route::get('/role', 'index')->name('role.index');
});

/*
|--------------------------------------------------------------------------
| Activity
|--------------------------------------------------------------------------
*/
Route::controller(ActivityLogController::class)->group(function () {
    Route::get('/activity-log', 'index')->name('activity_log.index');
});

/*
|--------------------------------------------------------------------------
| Setting
|--------------------------------------------------------------------------
*/
Route::controller(SettingController::class)->group(function () {
    Route::get('/settings/company-profile', 'company_profile')->name('settings.company_profile');
    Route::get('/settings/exchange-rate', 'exchange_rate')->name('settings.exchange_rate');
    Route::get('/settings/exchange-rate/insert', 'exchange_rate_insert')->name('settings.exchange_rate.insert');
    Route::post('/settings/exchange-rate/store', 'exchange_rate_store')->name('settings.exchange_rate.store');
    Route::get('/settings/exchange-rate/edit/{id}', 'exchange_rate_edit')->name('settings.exchange_rate.edit');
    Route::put('/settings/exchange-rate/update/{id}', 'exchange_rate_update')->name('settings.exchange_rate.update');
    Route::delete('/settings/exchange-rate/delete/{id}', 'exchange_rate_delete')->name('settings.exchange_rate.delete');
});

/*
|--------------------------------------------------------------------------
| Loan Products
|--------------------------------------------------------------------------
*/
Route::controller(LoanProductController::class)->group(function () {
    Route::get('/loan-products', 'index')->name('loan_products.index');
    Route::get('/loan-products/create', 'create')->name('loan_products.create');
    Route::post('/loan-products', 'store')->name('loan_products.store');
    Route::get('/loan-products/{loanProduct}', 'show')->name('loan_products.show');
    Route::get('/loan-products/edit/{loanProduct}', 'edit')->name('loan_products.edit');
    Route::put('/loan-products/{loanProduct}', 'update')->name('loan_products.update');
    Route::delete('/loan-products/{loanProduct}', 'destroy')->name('loan_products.destroy');
    Route::post('/loan-products/{loanProduct}/toggle-status', 'toggleStatus')->name('loan_products.toggle_status');
});

/*
|--------------------------------------------------------------------------
| Guarantors
|--------------------------------------------------------------------------
*/
Route::controller(GuarantorController::class)->group(function () {
    Route::get('/guarantors', 'index')->name('guarantors.index');
    Route::get('/guarantors/create', 'create')->name('guarantors.create');
    Route::post('/guarantors', 'store')->name('guarantors.store');
    Route::get('/guarantors/{id}', 'show')->name('guarantors.show');
    Route::get('/guarantors/edit/{id}', 'edit')->name('guarantors.edit');
    Route::put('/guarantors/{id}', 'update')->name('guarantors.update');
    Route::delete('/guarantors/{id}', 'destroy')->name('guarantors.destroy');
});

/*
|--------------------------------------------------------------------------
| Loan Applications
|--------------------------------------------------------------------------
*/
Route::controller(LoanApplicationController::class)->group(function () {
    Route::get('/loan-applications', 'index')->name('loan_applications.index');
    Route::get('/loan-applications/create', 'create')->name('loan_applications.create');
    Route::post('/loan-applications', 'store')->name('loan_applications.store');
    Route::get('/loan-applications/{id}', 'show')->name('loan_applications.show');
    Route::get('/loan-applications/edit/{id}', 'edit')->name('loan_applications.edit');
    Route::put('/loan-applications/{id}', 'update')->name('loan_applications.update');
    Route::delete('/loan-applications/{id}', 'destroy')->name('loan_applications.destroy');
    Route::post('/loan-applications/{id}/status', 'updateStatus')->name('loan_applications.update_status');
});
