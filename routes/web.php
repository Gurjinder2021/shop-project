<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DatabaseAdminController;
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
    Route::get('/user-shops/allcollection', [AdminController::class, 'collectionReport'])->name('user.collectionreport');

    Route::get('/user-shops/export', [AdminController::class, 'exportUserShops'])->name('user.shops.export');
    Route::get('/collections/export', [AdminController::class, 'exportShopCollections'])->name('admin.collections.export');

});

Route::middleware(['auth', 'dbadmin'])->group(function () {
    Route::get('/database-admin', [DatabaseAdminController::class, 'index'])->name('admin.database.index');
    Route::get('/database-admin/create-table', [DatabaseAdminController::class, 'createTableView'])->name('admin.database.create-table.view');
    Route::post('/database-admin/create-table', [DatabaseAdminController::class, 'createTable'])->name('admin.database.create-table');
    Route::delete('/database-admin/drop-table/{table}', [DatabaseAdminController::class, 'dropTable'])->name('admin.database.drop-table');
    Route::post('/database-admin/{table}', [DatabaseAdminController::class, 'store'])->name('admin.database.store');
    Route::put('/database-admin/{table}/{id}', [DatabaseAdminController::class, 'update'])->name('admin.database.update');
    Route::delete('/database-admin/{table}/{id}', [DatabaseAdminController::class, 'destroy'])->name('admin.database.destroy');
});

// User routes
Route::middleware(['auth', 'user'])->group(function () {
    // User Dashboard
    Route::get('/userdashboard', [UserController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/user/shops', [UserController::class, 'userShops'])->name('user.shops');
    Route::get('/user/shop-collection', [DailyCollectionController::class, 'create'])->name('daily.collection.create');
    Route::post('/user/shop-collection', [DailyCollectionController::class, 'store'])->name('daily.collection.store');
    Route::put('/user/shop-collection/{id}', [DailyCollectionController::class, 'update'])->name('daily.collection.store1');
    Route::get('/user/collectionsreport', [UserController::class, 'shopCollections'])->name('user.shop.collections');
    Route::put('/collections/{collection}', [DailyCollectionController::class, 'update'])->name('collections.update');
});

require __DIR__.'/auth.php';
