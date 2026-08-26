<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PromotionController;

/*
|--------------------------------------------------------------------------
| Wae Watu Reef Resort API Routes
|--------------------------------------------------------------------------
*/

// Villa endpoints
Route::get('/villas', [VillaController::class, 'index']);
Route::get('/villas/{slug}', [VillaController::class, 'show']);

// Promotion & Banner endpoints
Route::get('/promotions', [PromotionController::class, 'index']);

// Booking endpoints
Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{code}', [BookingController::class, 'show']);

// Inquiry endpoints
Route::post('/inquiries', [InquiryController::class, 'store']);
