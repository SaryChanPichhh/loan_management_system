<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\LoanController;
use App\Http\Controllers\Backend\RepaymentController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ActivityLogController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\LoanProductController;
use App\Http\Controllers\Backend\GuarantorController;
use App\Http\Controllers\Backend\LoanApplicationController;
use App\Http\Controllers\Backend\LoanCollateralController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend / Public Homepage
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/admin/v1/login', 'login')->name('login.index');
    Route::post('/admin/v1/login', 'store_login')->name('login.store');
    Route::get('/admin/v1/forgot-password', 'forgot_password')->name('login.forgot_password');
    Route::get('/admin/v1/logup', 'logup')->name('logup.index');
    Route::post('/admin/v1/logup', 'store_logup')->name('logup.store');
});

Route::post('/admin/v1/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'permission'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/admin/v1/', 'index')->name('dashboard.index');
        Route::get('/admin/v1/dashboard', 'index')->name('dashboard.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/admin/v1/customer', 'index')->name('customer.index');
        Route::get('/admin/v1/customer/create', 'create')->name('customer.create');
        Route::post('/admin/v1/customer', 'store')->name('customer.store');
        Route::get('/admin/v1/customer/show/{customer}', 'show')->name('customer.show');
        Route::get('/admin/v1/customer/edit/{customer}', 'edit')->name('customer.edit');
        Route::put('/admin/v1/customer/{customer}', 'update')->name('customer.update');
        Route::delete('/admin/v1/customer/{customer}', 'destroy')->name('customer.destroy');
    });

/*
|--------------------------------------------------------------------------
| Loans
|--------------------------------------------------------------------------
*/
Route::controller(LoanController::class)->group(function () {
    Route::get('/admin/v1/loans', 'index')->name('loans.index');
    Route::get('/admin/v1/loans/create', 'create')->name('loans.create');
    Route::post('/admin/v1/loans', 'store')->name('loans.store');
    Route::get('/admin/v1/loans/defaulted', 'defaulted')->name('loans.defaulted');
    Route::get('/admin/v1/loans/customer-eligibility', 'checkCustomerEligibility')->name('loans.customer_eligibility');
    Route::get('/admin/v1/loans/{id}', 'show')->name('loans.show');
    Route::get('/admin/v1/loans/edit/{id}', 'edit')->name('loans.edit');
    Route::put('/admin/v1/loans/{id}', 'update')->name('loans.update');
    Route::delete('/admin/v1/loans/{id}', 'destroy')->name('loans.destroy');
    Route::post('/admin/v1/loans/{id}/submit-review', 'submitForReview')->name('loans.submit_review');
    Route::get('/admin/v1/loans/review/{id}', 'review')->name('loans.review');
    Route::post('/admin/v1/loans/{id}/approve', 'approve')->name('loans.approve');
    Route::post('/admin/v1/loans/{id}/reject', 'reject')->name('loans.reject');
    Route::get('/admin/v1/loans/{id}/disburse', 'showDisburseForm')->name('loans.disburse.form');
    Route::post('/admin/v1/loans/{id}/disburse', 'disburse')->name('loans.disburse');
    Route::post('/admin/v1/loans/{id}/early-settle', 'earlySettle')->name('loans.early_settle');
    Route::post('/admin/v1/loans/{id}/mark-default', 'markDefault')->name('loans.mark_default');
    Route::post('/admin/v1/loans/{id}/write-off', 'writeOff')->name('loans.write_off');
    Route::get('/admin/v1/loans/payments/{id}', 'payments')->name('loans.payments');
    Route::get('/admin/v1/loans/{id}/schedule/print', 'printSchedule')->name('loans.schedule.print');
});

    /*
    |--------------------------------------------------------------------------
    | Loan Collaterals
    |--------------------------------------------------------------------------
    */
    Route::controller(LoanCollateralController::class)->group(function () {
        Route::get('/admin/v1/loans/{loan}/collaterals',                'index')      ->name('loans.collaterals.index');
        Route::post('/admin/v1/loans/{loan}/collaterals',               'store')      ->name('loans.collaterals.store');
        Route::put('/admin/v1/loans/{loan}/collaterals/{collateral}',   'update')     ->name('loans.collaterals.update');
        Route::delete('/admin/v1/loans/{loan}/collaterals/{collateral}','destroy')    ->name('loans.collaterals.destroy');
        Route::post('/admin/v1/collaterals/{collateral}/docs',          'uploadDoc')  ->name('loans.collaterals.docs.upload');
        Route::delete('/admin/v1/collaterals/docs/{doc}',               'deleteDoc')  ->name('loans.collaterals.docs.delete');
        Route::get('/admin/v1/collaterals/docs/{doc}/download',         'downloadDoc')->name('loans.collaterals.docs.download');
    });

    /*
    |--------------------------------------------------------------------------
    | Repayments
    |--------------------------------------------------------------------------
    */
    Route::controller(RepaymentController::class)->group(function () {
        Route::get('/admin/v1/repayments', 'index')->name('repayments.index');
        Route::get('/admin/v1/repayments/overdue', 'overdue')->name('repayments.overdue');
        Route::get('/admin/v1/repayments/loan/{id}', 'show')->name('repayments.show');
        Route::get('/admin/v1/repayments/create/{loan_id}', 'create')->name('repayments.create');
        Route::post('/admin/v1/repayments/{loan_id}/store', 'store')->name('repayments.store');
        Route::get('/admin/v1/repayments/{id}/edit', 'edit')->name('repayments.edit');
        Route::get('/admin/v1/repayments/{id}/receipt', 'receipt')->name('repayments.receipt');
    });

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::controller(ReportController::class)->group(function () {
        Route::get('/admin/v1/report', 'index')->name('report.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/admin/v1/notification', 'index')->name('notification.index');
        Route::get('/admin/v1/notification/create', 'create')->name('notification.create');
        Route::post('/admin/v1/notification', 'store')->name('notification.store');
        Route::delete('/admin/v1/notification/{notification}', 'destroy')->name('notification.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Roles & Permissions
    |--------------------------------------------------------------------------
    */
    Route::controller(RoleController::class)->group(function () {
        Route::get('/admin/v1/roles', 'index')->name('role.index');
        Route::post('/admin/v1/roles/{id}/permissions', 'updatePermissions')->name('role.permissions.update');
        Route::get('/admin/v1/users/{id}/permissions/edit', 'editUserPermissions')->name('user.permissions.edit');
        Route::post('/admin/v1/users/{id}/permissions', 'updateUserPermissions')->name('user.permissions.update');
    });

    Route::controller(PermissionController::class)->group(function () {
        Route::get('/admin/v1/permissions', 'index')->name('permission.index');
        Route::get('/admin/v1/permissions/create', 'create')->name('permission.create');
        Route::post('/admin/v1/permissions', 'store')->name('permission.store');
        Route::get('/admin/v1/permissions/{permission}/edit', 'edit')->name('permission.edit');
        Route::put('/admin/v1/permissions/{permission}', 'update')->name('permission.update');
        Route::delete('/admin/v1/permissions/{permission}', 'destroy')->name('permission.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */
    Route::controller(\App\Http\Controllers\Backend\ProfileController::class)->group(function () {
        Route::get('/admin/v1/profile', 'edit')->name('profile.edit');
        Route::put('/admin/v1/profile', 'update')->name('profile.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Activity
    |--------------------------------------------------------------------------
    */
    Route::controller(ActivityLogController::class)->group(function () {
        Route::get('/admin/v1/activity-log', 'index')->name('activity_log.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Setting
    |--------------------------------------------------------------------------
    */
    Route::controller(SettingController::class)->group(function () {
        Route::get('/admin/v1/settings/company-profile', 'company_profile')->name('settings.company_profile');
        Route::get('/admin/v1/settings/exchange-rate', 'exchange_rate')->name('settings.exchange_rate');
        Route::get('/admin/v1/settings/exchange-rate/insert', 'exchange_rate_insert')->name('settings.exchange_rate.insert');
        Route::post('/admin/v1/settings/exchange-rate/store', 'exchange_rate_store')->name('settings.exchange_rate.store');
        Route::get('/admin/v1/settings/exchange-rate/edit/{id}', 'exchange_rate_edit')->name('settings.exchange_rate.edit');
        Route::put('/admin/v1/settings/exchange-rate/update/{id}', 'exchange_rate_update')->name('settings.exchange_rate.update');
        Route::delete('/admin/v1/settings/exchange-rate/delete/{id}', 'exchange_rate_delete')->name('settings.exchange_rate.delete');
    });

/*
|--------------------------------------------------------------------------
| Loan Products
|--------------------------------------------------------------------------
*/
Route::controller(LoanProductController::class)->group(function () {
    Route::get('/admin/v1/loan-products', 'index')->name('loan_products.index');
    Route::get('/admin/v1/loan-products/create', 'create')->name('loan_products.create');
    Route::post('/admin/v1/loan-products', 'store')->name('loan_products.store');
    Route::get('/admin/v1/loan-products/{loanProduct}', 'show')->name('loan_products.show');
    Route::get('/admin/v1/loan-products/edit/{loanProduct}', 'edit')->name('loan_products.edit');
    Route::put('/admin/v1/loan-products/{loanProduct}', 'update')->name('loan_products.update');
    Route::delete('/admin/v1/loan-products/{loanProduct}', 'destroy')->name('loan_products.destroy');
    Route::post('/admin/v1/loan-products/{loanProduct}/toggle-status', 'toggleStatus')->name('loan_products.toggle_status');
});

/*
|--------------------------------------------------------------------------
| Guarantors
|--------------------------------------------------------------------------
*/
Route::controller(GuarantorController::class)->group(function () {
    Route::get('/admin/v1/guarantors', 'index')->name('guarantors.index');
    Route::get('/admin/v1/guarantors/create', 'create')->name('guarantors.create');
    Route::post('/admin/v1/guarantors', 'store')->name('guarantors.store');
    Route::get('/admin/v1/guarantors/{id}', 'show')->name('guarantors.show');
    Route::get('/admin/v1/guarantors/edit/{id}', 'edit')->name('guarantors.edit');
    Route::put('/admin/v1/guarantors/{id}', 'update')->name('guarantors.update');
    Route::delete('/admin/v1/guarantors/{id}', 'destroy')->name('guarantors.destroy');
});

/*
|--------------------------------------------------------------------------
| Loan Applications
|--------------------------------------------------------------------------
*/
Route::controller(LoanApplicationController::class)->group(function () {
    Route::get('/admin/v1/loan-applications', 'index')->name('loan_applications.index');
    Route::get('/admin/v1/loan-applications/create', 'create')->name('loan_applications.create');
    Route::post('/admin/v1/loan-applications', 'store')->name('loan_applications.store');
    Route::get('/admin/v1/loan-applications/{id}', 'show')->name('loan_applications.show');
    Route::get('/admin/v1/loan-applications/edit/{id}', 'edit')->name('loan_applications.edit');
    Route::put('/admin/v1/loan-applications/{id}', 'update')->name('loan_applications.update');
    Route::delete('/admin/v1/loan-applications/{id}', 'destroy')->name('loan_applications.destroy');
    Route::post('/admin/v1/loan-applications/{id}/status', 'updateStatus')->name('loan_applications.update_status');
});

});
