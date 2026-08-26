<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect('/admin');
});

/*
|--------------------------------------------------------------------------
| Wae Watu Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/villas', [AdminController::class, 'villas']);
    Route::put('/villas/{id}', [AdminController::class, 'updateVilla']);
    Route::get('/bookings', [AdminController::class, 'bookings']);
    Route::post('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus']);
    Route::get('/promotions', [AdminController::class, 'promotions']);
    Route::put('/promotions/{id}', [AdminController::class, 'updatePromotion']);
});
