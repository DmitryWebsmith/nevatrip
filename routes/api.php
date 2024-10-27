<?php

use App\Http\Controllers\Api\BookingOrder;
use Illuminate\Support\Facades\Route;

Route::get('/v1/booking/order', [BookingOrder::class, 'bookingOrder']);
Route::get('/v1/booking/approve', [BookingOrder::class, 'bookingApprove']);
