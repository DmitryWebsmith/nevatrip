<?php

namespace App\Services;

use App\Models\EventList;
use Illuminate\Support\Str;

class UniqueBarcodeService
{
    public function generateUniqueBarcode()
    {
        do {
            $barcode = Str::random(12); // Генерация случайной строки длиной 12 символов
        } while (EventList::query()->where('barcode', $barcode)->exists()); // Проверяем уникальность

        return $barcode;
    }
}
