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
            'image.*.pickup_images.*' => 'image|mimes:jpeg,png,jpg|max:10240',
            'image.*.delivery_images.*' => 'image|mimes:jpeg,png,jpg|max:10240',
            'remarks_attachment' => 'image|mimes:jpeg,png,jpg|max:10240'
        ];
    }

    public function messages()
    {
        return [
            'image.*.pickup_images.*.image' => 'Each Pick Up image must be a valid image file.',
            'image.*.pickup_images.*.mimes' => 'Each Pick Up image must be of type: jpg, png, jpeg.',
            'image.*.pickup_images.*.max' => 'Each Pick Up image must not exceed 10 MB.',
            'image.*.delivery_images.*.image' => 'Each Delivery image must be a valid image file.',
            'image.*.delivery_images.*.mimes' => 'Each Delivery image must be of type: jpg, png, jpeg.',
            'image.*.delivery_images.*.max' => 'Each Delivery image must not exceed 10 MB.',


            'remarks_attachment.*.image' => 'Each Delivery image must be a valid image file.',
            'remarks_attachment.*.mimes' => 'Each Delivery image must be of type: jpg, png, jpeg.',
            'remarks_attachment.*.max' => 'Each Delivery image must not exceed 10 MB.',
        ];
    }
}
