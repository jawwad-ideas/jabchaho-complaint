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
            'attachments'       => 'required', // Ensure the array itself is present
            'attachments.*'     => 'image|mimes:jpeg,png,jpg|max:2048', 
            'machine_id'        => 'required|exists:machines,id',                    // Type must be a string, max 255 characters
            'barcode'           => 'required|string',                         // Barcode is required, accept a string
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
            'attachments.required' => 'The machine image is required.',
            //'attachments.*.required' => 'The machine image is required.',
            'attachments.*.image' => 'The file must be a valid image.',
            'attachments.*.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'attachments.*.max' => 'The image size must not exceed 2MB.',
            
            'machine_id.required' => 'The machine type is required.',
            'machine_id.exists' => 'The machine type is not valid.',
           
            'barcode.required' => 'The barcode is required.',
            'barcode.string' => 'The barcode must be a valid string.',
        ];
    }
}
