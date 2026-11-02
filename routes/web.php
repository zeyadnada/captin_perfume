<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('brands', BrandController::class)->except(['show']);
});
