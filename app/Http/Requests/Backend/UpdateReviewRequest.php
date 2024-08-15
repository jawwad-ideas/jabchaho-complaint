<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
        return [
            'name'     => 'required',
            'status'   => 'required|in:' . implode(',', array_keys(config('constants.review_statues'))), ////65000
            'comments'  => 'required|max:65000',
        ];
    }

    
     /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    { 
        return [
            
            //Name
            'name.required'             => 'The Name field is required!',
            
            //Status
            'status.required'           => 'The Status field is required!',
            'status.in'                 => 'The Status is invalid!',

            //comment
            'comments.required'          => 'The Review field is required!',
            'comments.max'               => 'The Review must not be greater than :max characters.!',

        ];
    }
}
