<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect('/admin');
});

/*
|--------------------------------------------------------------------------
| Wae Watu Admin Dashboard & Auth Routes
|--------------------------------------------------------------------------
*/
// Unprotected Admin Login Routes
Route::get('/admin/login', [AdminController::class, 'loginForm'])->name('login');
Route::post('/admin/login', [AdminController::class, 'login']);

// Protected Admin Panel Routes (Requires Admin Auth)
Route::middleware(['web', \App\Http\Middleware\EnsureAdmin::class])->prefix('admin')->group(function () {
    Route::post('/logout', [AdminController::class, 'logout']);
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/cms', [AdminController::class, 'cms']);
    Route::post('/cms/update', [AdminController::class, 'updateCms']);
    Route::get('/villas', [AdminController::class, 'villas']);
    Route::put('/villas/{id}', [AdminController::class, 'updateVilla']);
    Route::get('/bookings', [AdminController::class, 'bookings']);
    Route::post('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus']);
    Route::get('/promotions', [AdminController::class, 'promotions']);
    Route::put('/promotions/{id}', [AdminController::class, 'updatePromotion']);
});
