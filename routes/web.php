<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DailyCollectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// This will handle role-based redirection after authentication
// Admin routes
Route::middleware(['auth'])->group(function () {
    // This will call the redirectToDashboard function in UserController
    Route::get('/dashboard', [UserController::class, 'redirectToDashboard'])->name('dashboard');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admindashboard', [UserController::class, 'adminDashboard'])->name('admin.dashboard');

    // Other admin-related routes
    Route::get('/adduser', [AdminController::class, 'add_user'])->name('add.user');
    Route::get('/users', [AdminController::class, 'view_users'])->name('users');
    Route::post('/adduser', [AdminController::class, 'store']);
    Route::get('/users/{id}/edit', [AdminController::class, 'edit_user'])->name('edit.user');
    Route::post('/users/{id}/update', [AdminController::class, 'update_user'])->name('update.user');
    Route::delete('/users/{id}', [AdminController::class, 'delete_user'])->name('delete.user');
    Route::get('/assign-shop', [AdminController::class, 'assignShopForm'])->name('assign.shop.form');
    Route::post('/assign-shop', [AdminController::class, 'assignShopStore'])->name('assign.shop');
    Route::post('/assign-multiple-shops', [AdminController::class, 'assignMultipleShops'])->name('assign.multiple.shops');
    Route::get('/user-shops', [AdminController::class, 'viewUserShops'])->name('view.user.shops');
    Route::put('/shops/{shop}', [AdminController::class, 'updateShop'])->name('shops.update');
    Route::delete('/shops/{shop}', [AdminController::class, 'deleteShop'])->name('shops.delete');
    Route::get('/user-shops/export', [AdminController::class, 'exportUserShops'])->name('user.shops.export');
});

// User routes
Route::middleware(['auth', 'user'])->group(function () {
    // User Dashboard
    Route::get('/userdashboard', [UserController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/user/shops', [UserController::class, 'userShops'])->name('user.shops');
    Route::get('/user/shop-collection', [DailyCollectionController::class, 'create'])->name('daily.collection.create');
    Route::post('/user/shop-collection', [DailyCollectionController::class, 'store'])->name('daily.collection.store');
    Route::get('/user/collectionsreport', [UserController::class, 'shopCollections'])->name('user.shop.collections');
});

require __DIR__.'/auth.php';
