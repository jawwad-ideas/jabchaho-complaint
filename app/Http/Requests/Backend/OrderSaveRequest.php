<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class OrderSaveRequest extends FormRequest
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
            'order_no' => 'required',
            'pickup_images' => 'image|mimes:jpeg,png,jpg|max:1024', // Validation rules for image
            'delivery_images' => 'image|mimes:jpeg,png,jpg|max:1024', // Validation rules for image
    ];
    }

    public function messages()
    {
        return [
            'order_no.required' => 'Order Number is required!',
            'pickup_images.image'      => 'Please select a Pick Up Image!',
            'pickup_images.mimes'      => 'The Profile Image must be a file of type: jpg,png,jpeg!',
            'pickup_images.max'        => 'The Profile Image must not be greater than 1 Mb!',
            'delivery_images.image'      => 'Please select a Pick Up Image!',
            'delivery_images.mimes'      => 'The Profile Image must be a file of type: jpg,png,jpeg!',
            'delivery_images.max'        => 'The Profile Image must not be greater than 1 Mb!',
        ];
    }
}
