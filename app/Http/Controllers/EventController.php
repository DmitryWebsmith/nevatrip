<?php

namespace App\Http\Controllers;

use App\Models\EventList;
use App\Services\EventService;
use App\Services\GuzzleHttpService;
use App\Services\UniqueBarcodeService;
use Illuminate\Http\JsonResponse;


class EventController extends Controller
{
    protected EventService $eventService;
    protected UniqueBarcodeService $uniqueBarcodeService;
    protected GuzzleHttpService $guzzleHttpService;

    public function __construct(
        EventService $eventService,
        UniqueBarcodeService $uniqueBarcodeService,
        GuzzleHttpService $guzzleHttpService
    ) {
        $this->eventService = $eventService;
        $this->uniqueBarcodeService = $uniqueBarcodeService;
        $this->guzzleHttpService = $guzzleHttpService;
    }

    public function index(): JsonResponse
    {
        $event = $this->eventService->createEvent();
        $event['barcode'] = $this->uniqueBarcodeService->generateUniqueBarcode();

        $bookingOrderUrl = config('booking_order_api.booking_order_url');
        $approveOrderUrl = config('booking_order_api.approve_order_url');

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // отправка запроса на бронь
        $response = $this->guzzleHttpService->sendRequest($bookingOrderUrl, $event, $headers, [], 'GET');

        // в случае неудачи повторная отправка на бронь
        if ($response->getStatusCode() !== 200) {
            $event['barcode'] = $this->uniqueBarcodeService->generateUniqueBarcode();
            $response = $this->guzzleHttpService->sendRequest($bookingOrderUrl, $event, $headers, [], 'GET');
        }

        // в случае успеха отправки на бронь, отправка подтверждения брони
        if ($response->getStatusCode() == 200) {
            $barcode = $this->uniqueBarcodeService->generateUniqueBarcode();
            $response = $this->guzzleHttpService->sendRequest(
                $approveOrderUrl,
                ['barcode' => $barcode],
                $headers,
                [],
                'GET'
            );
        }

        // в случае успешного подтверждения брони, событие сохраняется в базу
        if ($response->getStatusCode() == 200) {
            EventList::query()->insert($event);
            return response()->json(["message" => "Событие успешно добавлено в базу"]);
        }

        $decodedResponse = json_decode($response->getBody()->getContents(), true);

        return response()->json($decodedResponse, $response->getStatusCode());
    }
}
