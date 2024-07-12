<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MatchOldPassword;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ComplainantChangePasswordRequest extends FormRequest
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
            'current_password' => ['required',new MatchOldPassword],
            'new_password' => 'required|min:6|max:20',
            'new_confirm_password' => 'required|same:new_password|min:6|max:20',
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
                'new_password.required'                      => 'The New Password field is required.',
                'pasnew_passwordsword.min'                   => 'The New Password must be at least :min characters.',
                'new_password.max'                           => 'The New Password must not be greater than :max characters.',
                #confirm_password
                'new_confirm_password.required'              => 'The Confirm Password field is required.',
                'new_confirm_password.same'                  => 'The Confirm Password and Password must match.',
                'new_confirm_password.min'                   => 'The Confirm Password must be at least :min characters.',
                'new_confirm_password.max'                   => 'The Confirm Password must not be greater than :max characters.',
                       
         ];
    }

    

    /**
        * Get the error messages for the defined validation rules.*
        * @return array
        */
        protected function failedValidation(Validator $validator)
        {
            //return response()->json(['errors' => $validator->errors()->all()]);
            throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => true
            ]));
        }

}
