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
        $id = (int) $this->segment(2);
        
        if(!empty($id))
        {
            return 
            [
                'lot_number'     => ['required','unique:dryer,lot_number,'.$id],
                'after_barcodes' => ['required', 'string', new MatchBarcode($id)], 
                //'after_barcodes'           => 'required|string',                         // Barcode is required, accept a string
            ];
        }
        else
        {
            return 
            [
                'lot_number'                => ['required','unique:dryer,lot_number'],
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
        
        if(!empty($id ))
        {
            return 
            [
                'lot_number'               => 'Lot is required',
                'lot_number.unique'        => 'The Lot has already been taken.',
                'after_barcodes.required'  => 'After the dryer barcodes is required.',
                'after_barcodes.string'    => 'After the dryer barcodes must be a valid string.',
            ];
        }
        else
        {
            return 
            [
                'lot_number'                => 'Lot is required',
                'lot_number.unique'         => 'The Lot has already been taken.',
                'before_barcodes.required'  => 'Before the dryer barcodes is required.',
                'before_barcodes.string'    => 'Before the dryer barcodes must be a valid string.',
            ];
        }
    }
}
