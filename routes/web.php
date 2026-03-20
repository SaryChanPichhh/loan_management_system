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
    Route::get('/loans/defaulted', 'defaulted')->name('loans.defaulted');
    Route::get('/loans/{id}', 'show')->name('loans.show');
    Route::get('/loans/edit/{id}', 'edit')->name('loans.edit');
    Route::get('/loans/review/{id}', 'review')->name('loans.review');
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
