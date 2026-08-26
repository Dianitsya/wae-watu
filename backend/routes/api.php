<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\UserAuthController;

/*
|--------------------------------------------------------------------------
| Wae Watu Reef Resort API Routes
|--------------------------------------------------------------------------
*/

// Full CMS Live Content Endpoint for Frontend
Route::get('/content', [ContentController::class, 'index']);

// Guest User Auth Endpoints
Route::post('/auth/register', [UserAuthController::class, 'register']);
Route::post('/auth/login', [UserAuthController::class, 'login']);
Route::post('/auth/logout', [UserAuthController::class, 'logout']);
Route::get('/auth/me', [UserAuthController::class, 'me']);
Route::get('/auth/my-bookings', [UserAuthController::class, 'myBookings']);

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
