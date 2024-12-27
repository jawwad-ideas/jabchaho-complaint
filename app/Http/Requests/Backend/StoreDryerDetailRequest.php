<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

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
        if(!empty($this->segment(2)))
        {
            return 
            [
                'after_barcodes'           => 'required|string',                         // Barcode is required, accept a string
            ];
        }
        else
        {
            return 
            [
                'before_barcodes'           => 'required|string',                         // Barcode is required, accept a string
            ];
        }
       
    }

     /**
     * Customize the error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        
        if(!empty($this->segment(2)))
        {
            return 
            [
                'before_barcodes.required'  => 'After the dryer barcodes is required.',
                'before_barcodes.string'    => 'After the dryer barcodes must be a valid string.',
            ];
        }
        else
        {
            return 
            [
                'before_barcodes.required'  => 'Before the dryer barcodes is required.',
                'before_barcodes.string'    => 'Before the dryer barcodes must be a valid string.',
            ];
        }
    }
}
