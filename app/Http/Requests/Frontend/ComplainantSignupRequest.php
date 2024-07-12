<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ComplainantSignupRequest extends FormRequest
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
            'full_name'                     => 'required|max:50|regex:/^[\pL\s\-]+$/u',
            'gender'                        => 'required|in:' . implode(',', array_keys(config('constants.gender_options'))), 
            'cnic'                          => 'required|unique:complainants,cnic|max:15|min:15|regex:/^\d{5}-\d{7}-\d{1}$/',
            'mobile_number'                 => 'required|unique:complainants,mobile_number|max:11|min:11|regex:/^[0-9]+$/',
            'email'                         => 'required|email:rfc,dns|unique:complainants,email|max:50',
            'password'                      => 'required|min:6|max:20',
            'confirm_password'              => 'required|same:password|min:6|max:20',
            'g-recaptcha-response'          => 'required|captcha',
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
                       #full_name
                       'full_name.required'                     => 'The Full Name field is required!',
                       'full_name.max'                          => 'The Full Name must not be greater than :max characters.',
                       'full_name.regex'                        => 'The Full Name format is invalid',

                       #gender
                       'gender.required'                        => 'The Gender field is required!',
                       'gender.in'                              => 'The Gender is not valid!',
                       
                       #cnic
                       'cnic.required'                          => 'The CNIC field is required.',
                       'cnic.max'                               => 'The CNIC must not be greater than :max characters.!',
                       'cnic.min'                               => 'The CNIC must not be less than :min characters.!',
                       'cnic.regex'                             => 'The CNIC must be numeric!',
                       'cnic.unique'                            => 'Sorry, This CNIC is already used by another user.!',

                       'mobile_number.required'                 => 'The Mobile Number field is required.',
                       'mobile_number.max'                      => 'The Mobile must not be greater than :max !',
                       'mobile_number.min'                      => 'The Mobile must not be less than :min !',
                       'mobile_number.regex'                    => 'The Mobile must be numeric!',
                       
                       #email
                       'email.required'                         => 'The Email Address is required!',
                       'email.email'                            => 'The Email Address field must be a valid email address!',
                       'email.unique'                           => 'Sorry, This Email Address is already used by another user. Please try with different one, thank you.',
                       'email.max'                              => 'Email Address must not be greater than :max characters.',
                        #password
                       'password.required'                      => 'The Password field is required.',
                       'password.min'                           => 'The Password must be at least 6 characters.',
                       'password.max'                           => 'The Password must not be greater than :max characters.',
                       #confirm_password
                       'confirm_password.required'              => 'The Confirm Password field is required.',
                       'confirm_password.same'                  => 'The Confirm Password and Password must match.',
                       'confirm_password.min'                   => 'The Confirm Password must be at least :min characters.',
                       'confirm_password.max'                   => 'The Confirm Password must not be greater than :max characters.',

                       #g-recaptcha-response
                       'g-recaptcha-response.required'          => 'The Captcha field is required!',// 
                       'g-recaptcha-response.captcha'           => 'Failed to validate the Captcha.',// 
        ];
    }
}
