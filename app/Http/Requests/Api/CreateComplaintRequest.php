<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateComplaintRequest extends FormRequest
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
        \Log::channel('request')->info('Complaint API Payloads');
        \Log::channel('request')->info(print_r($this->all(),true));
        
        return 
        [
            'complaint_type'        => 'required|in:' . implode(',', array_keys(config('constants.complaint_type'))),
            'order_id'              => 'required|unique:complaints,order_id|integer|min:1',
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:150',
            'mobile_number'         => 'required|string|min:11|max:15',
            'service_id'            => 'required|exists:services,id',
            'comments'              => 'required|string|max:300',
            'attachments'           => 'required',
            'attachments.*'         => 'image|mimes:jpeg,png,jpg,image/jpeg,image/png,image/jpg|max:1048576',
        ];
    }

    public function messages()
    {
        return [
            'complaint_type.required'       => 'The Complaint Nature is required.',
            'complaint_type.in'             => 'The selected Complaint Nature is invalid.',
            
            'order_id.required'             => 'The order ID is required.',
            'order_id.integer'              => 'The order ID must be an integer.',
            'order_id.min'                  => 'The order ID must be a positive integer.',
            'order_id.unique'               => 'Complaint has already been submitted for this order.',
            
            'name.required'                 => 'The Name is required.',
            'name.string'                   => 'The Name must be a string.',
            'name.max'                      => 'The Name may not be greater than :max characters.',
            
            'email.required'                => 'The Email is required.',
            'email.email'                   => 'The Email must be a valid email address.',
            'email.max'                     => 'The Email may not be greater than :max characters.',
            
            'mobile_number.required'        => 'The Mobile number is required.',
            'mobile_number.string'          => 'The Mobile number must be a string.',
            'mobile_number.min'             => 'The Mobile number must be at least 10 characters.',
            'mobile_number.max'             => 'The Mobile number may not be greater than 15 characters.',

            'service_id.required'           => 'The Service field is required!',
            'service_id.exists'             => 'The Service field is Invalid!',
            
            'comments.required'             => 'The Comments are required.',
            'comments.string'               => 'The Comments must be a string.',
            'comments.max'                  => 'The Comments may not be greater than :max characters.',
            
            'attachments.required'          => 'Please upload at least one file.',
            'attachments.*.image'           => 'The Attachments must be an image.',
            'attachments.*.mimes'           => 'The Attachments must be a file of type: jpeg, png, jpg',
            'attachments.*.max'             => 'The Attachments may not be greater than :max kilobytes.',

        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @param  Validator  $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]));
    }
}