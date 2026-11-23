<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Storefront\AccountController;
use App\Http\Controllers\Storefront\Auth\LoginController as StorefrontLoginController;
use App\Http\Controllers\Storefront\Auth\RegisterController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', LocaleController::class)
    ->whereIn('locale', array_keys(config('app.available_locales', ['en' => []])))
    ->name('locale.switch');

Route::get('/', HomeController::class)->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [StorefrontLoginController::class, 'create'])->name('login');
    Route::post('/login', [StorefrontLoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('register.store');
});

Route::post('/logout', [StorefrontLoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::post('/logout', [AdminLoginController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    Route::middleware(['auth', 'staff'])->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('roles', RoleController::class)->except(['show']);
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
});
