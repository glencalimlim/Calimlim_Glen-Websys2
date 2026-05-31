<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SettingsController;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/logout',    [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
// Sales
Route::post('/sales',[SaleController::class, 'store'])->name('sales.store');





    Route::get('/items/create',          [ItemController::class, 'create'])->name('items.create');
    Route::get('/items/history',         [ItemController::class, 'history'])->name('items.history');
    Route::get('/items/report',          [ItemController::class, 'report'])->name('items.report');

    Route::get('/items',                 [ItemController::class, 'index'])->name('items.index');
    Route::post('/items/store',          [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/edit/{id}',       [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/update/{id}',     [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}',         [ItemController::class, 'destroy'])->name('items.destroy');
    Route::post('/items/stock-in/{id}',  [ItemController::class, 'stockIn'])->name('items.stock-in');
    Route::post('/items/stock-out/{id}', [ItemController::class, 'stockOut'])->name('items.stock-out');

    Route::get('/suppliers',             [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers',            [SupplierController::class, 'store'])->name('suppliers.store');
    Route::delete('/suppliers/{id}',     [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('/settings',              [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings',             [SettingsController::class, 'update'])->name('settings.update');
});