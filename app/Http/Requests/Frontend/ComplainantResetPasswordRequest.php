<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ComplainantResetPasswordRequest extends FormRequest
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
            'password'                      => 'required|min:6|max:20',
            'confirm_password'              => 'required|same:password|min:6|max:20',
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
                     
                #password
                'password.required'                      => 'The Password field is required.',
                'password.min'                           => 'The Password must be at least :min characters.',
                'password.max'                           => 'The Password must not be greater than :max characters.',
                #confirm_password
                'confirm_password.required'              => 'The Confirm Password field is required.',
                'confirm_password.same'                  => 'The Confirm Password and Password must match.',
                'confirm_password.min'                   => 'The Confirm Password must be at least :min characters.',
                'confirm_password.max'                   => 'The Confirm Password must not be greater than :max characters.',
                       
         ];
    }
}
