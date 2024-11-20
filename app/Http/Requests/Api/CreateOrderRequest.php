<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateOrderRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'orders' => 'required|array|min:1',
            'orders.*.order_id' => 'required|integer|min:1',
            'orders.*.name' => 'required|string|max:100',
            'orders.*.mobile_number' => 'required|string|min:11|max:15',
            'orders.*.email' => 'required|email|max:150',
            'orders.*.order_at' => 'required|date',
            'orders.*.order_pickup_date' => 'required|date',
            'orders.*.delivery_date' => 'required|date',
            'orders.*.items' => 'required|array|min:1',
            'orders.*.items.*.item_id' => 'required|integer|min:1',
            'orders.*.items.*.item_name' => 'required|string|max:255',
            'orders.*.items.*.item_barcode' => 'required|string|max:50',
            'orders.*.items.*.item_qty' => 'required|integer|min:1',
            'orders.*.items.*.service_id' => 'required|exists:services,id',
        ];
    }

    public function messages()
    {
        return [
            'order_id.required'             => 'The order ID is required.',
            'name.required'                 => 'The name is required.',
            'email.required'                => 'The email address is required.',
            'mobile_number.required'        => 'The mobile number is required.',
            'order_at.required'             => 'The order date and time are required.',
            'order_pickup_date.required'    => 'The order pickup date is required.',
            'delivery_date.required'        => 'The delivery date is required.',

            // Item-level validations
            'service_id.required'           => 'The service ID is required.',
            'item_barcode.required'         => 'The item barcode is required.',
            'item_name.required'            => 'The item name is required.',
            'item_qty.required'             => 'The item quantity is required.',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @param  Validator  $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]));
    }
}
