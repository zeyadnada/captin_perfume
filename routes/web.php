<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('home');

Route::get('/locale/{locale}', LocaleController::class)
    ->whereIn('locale', array_keys(config('app.available_locales', ['en' => []])))
    ->name('locale.switch');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('brands', BrandController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/products/{product}/variants/{variant}/stock/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/products/{product}/variants/{variant}/stock', [InventoryController::class, 'update'])->name('inventory.update');
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('/stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
    Route::resource('products.variants', ProductVariantController::class)->except(['show']);
    Route::resource('products.images', ProductImageController::class)
        ->except(['create', 'show', 'edit']);
});
