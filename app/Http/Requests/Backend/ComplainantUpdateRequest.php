<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComplainantUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $complainant = request()->route('complainant');

        return [
            'full_name'     => 'required',
            'email'         => 'required|email:rfc,dns|unique:complainants,email,'.$complainant,
            'mobile_number' => 'required|max:11|min:11|unique:complainants,mobile_number,'.$complainant,
            'gender'        => 'required|in:' . implode(',', array_keys(config('constants.gender_options'))), 
            'cnic'          => [
                'required',
                'max:15',
                'min:15',
                'regex:/^\d{5}-\d{7}-\d{1}$/',
                Rule::unique('complainants', 'cnic')->ignore($complainant),
            ],
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
            'cnic.max'                               => 'The CNIC must not be greater than :max characters.!',
            'cnic.min'                               => 'The CNIC must not be less than :min characters.!',
            'cnic.regex'                             => 'The CNIC must be numeric!',
            'cnic.unique'                            => 'Sorry, This CNIC is already used by another user.!',
            
            #email
            'email.required'                         => 'The Email Address is required!',
            'email.email'                            => 'The Email Address field must be a valid email address!',
            'email.unique'                           => 'Sorry, This Email Address is already used by another user. Please try with different one, thank you.',
            'email.max'                              => 'Email Address must not be greater than :max characters.',
            
            #mobile_number
            'mobile_number.max'                      => 'The Mobile Number must not be greater than :max characters.!',
            'mobile_number.min'                      => 'The Mobile Number must not be less than :min characters.!',
            'mobile_number.unique'                   => 'Sorry, This Mobile Number is already used by another user.!',
            'mobile_number.required'                 => 'The Mobile Number is required!',
        ];
    }
}
