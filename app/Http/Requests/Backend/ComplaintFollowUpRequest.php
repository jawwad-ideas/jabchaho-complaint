<?php

namespace App\Http\Requests\backend;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintFollowUpRequest extends FormRequest
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
            'complaint_status_id'   => 'required|exists:complaint_statuses,id', ////65000
            'description'           => 'required|max:65000',
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
            
            //Status
            'complaint_status_id.required'          => 'The Status field is required!',
            'complaint_status_id.exists'             => 'The Status is invalid!',

            //comment
            'description.required'            => 'The Description field is required!',
            'description.max'                 => 'The Description must not be greater than :max characters.!',

        ];
    }
}
