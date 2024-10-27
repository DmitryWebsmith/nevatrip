<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookingOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'integer'],
            'event_date' => ['required', 'string', 'max:20'],
            'ticket_adult_price' => ['required', 'integer'],
            'ticket_adult_quantity' => ['required', 'integer'],
            'ticket_kid_price' => ['required', 'integer'],
            'ticket_kid_quantity' => ['required', 'integer'],
            'barcode' => ['required', 'string', 'max:120'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
