<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StoreMachineDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set to true if all users are allowed to make this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image'     => 'required|image|mimes:jpeg,png,jpg|max:2048', // Image must be jpeg, png, jpg and max 2MB
            'type'      => 'required|string|max:255',                    // Type must be a string, max 255 characters
            'barcode'   => 'required|string',                         // Barcode is required, accept a string
        ];
    }

     /**
     * Customize the error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'image.required' => 'The machine image is required.',
            'image.image' => 'The file must be a valid image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.max' => 'The image size must not exceed 2MB.',
            'type.required' => 'The machine type is required.',
            'type.string' => 'The machine type must be a valid string.',
            'type.max' => 'The machine type must not exceed 255 characters.',
            'barcode.required' => 'The barcode is required.',
            'barcode.string' => 'The barcode must be a valid string.',
        ];
    }
}
