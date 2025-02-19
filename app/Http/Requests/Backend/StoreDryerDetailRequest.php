<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MatchBarcode;

class StoreDryerDetailRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {   
        return 
        [
            'barcode' => ['required', 'string'], 
        ];
    }

     /**
     * Customize the error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return 
        [
            'barcode.required'  => 'The dryer barcodes is required.',
            'barcode.string'    => 'The dryer barcodes must be a valid string.',
        ];
    }
}
