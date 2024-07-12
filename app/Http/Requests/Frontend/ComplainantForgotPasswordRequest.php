<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ComplainantForgotPasswordRequest extends FormRequest
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
            'email'                         => 'required|email:rfc,dns|max:50', 
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
                     
                #email
                'email.required'                         => 'The Email Address is required!',
                'email.email'                            => 'The Email Address field must be a valid email address!',
                'email.max'                              => 'Email Address must not be greater than :max characters.',
                       
         ];
    }
}
