<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login.index');
});

Route::controller(DashboardController::class)->group(function () {
    Route::get('/', 'index')->name('index.index');
});

Route::controller(CustomerController::class)->group(function () {
    Route::get('/customer', 'index')->name('customer.index');
});
