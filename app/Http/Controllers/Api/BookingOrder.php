<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BookingApproveRequest;
use App\Http\Requests\Api\BookingOrderRequest;
use Illuminate\Http\JsonResponse;

class BookingOrder extends Controller
{
    public function bookingOrder(BookingOrderRequest $request): JsonResponse
    {
        $randomNumber = rand(0, 1);

        if ($randomNumber === 0) {
            return response()->json(['message' => 'order successfully booked'], 200);
        }

        return response()->json(['error' => 'barcode already exists'], 400);
    }

    public function bookingApprove(BookingApproveRequest $request): JsonResponse
    {
        return match ($this->checkOrderStatus()) {
            'success' => response()->json(['message' => 'order successfully approved'], 200),
            'event cancelled' => response()->json(['error' => 'event cancelled'], 400),
            'no tickets' => response()->json(['error' => 'no tickets'], 400),
            'no seats' => response()->json(['error' => 'no seats'], 400),
            'fan removed' => response()->json(['error' => 'fan removed'], 400),
            default => response()->json(['error' => 'unknown error'], 400),
        };
    }

    private function checkOrderStatus(): string
    {
        return match (random_int(0, 5)) {
            0 => 'success',
            1 => 'event cancelled',
            2 => 'no tickets',
            3 => 'no seats',
            4 => 'fan removed',
            default => 'unknown',
        };
    }
}
