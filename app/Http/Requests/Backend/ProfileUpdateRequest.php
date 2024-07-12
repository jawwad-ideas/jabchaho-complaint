<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
        return [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'mobile_number' => 'required',
            'profile_image' => 'image|mimes:jpeg,png,jpg|max:2048', // Validation rules for image
    ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'username.required' => 'User Name is required!',
            'email.required' => 'Email is required!',
            'mobile_number.required' => 'Mobile Number is required!',
            'profile_image.image'      => 'Please select a Profile Image!',
            'profile_image.mimes'      => 'The Profile Image must be a file of type: jpg,png,jpeg!',
            'profile_image.max'        => 'The Profile Image must not be greater than 1 Mb!',
        ];
    }
}
