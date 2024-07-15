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
        return 
        [
            
            'query_type'            => 'required|in:' . implode(',', array_keys(config('constants.query_type'))),
            'complaint_type'        => 'required|in:' . implode(',', array_keys(config('constants.complaint_type'))),
            'inquiry_type'          => 'nullable|in:' . implode(',', array_keys(config('constants.inquiry_type'))),
            'order_id'              => 'required|unique:complaints,order_id|integer|min:1',
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:150',
            'mobile_number'         => 'required|string|min:10|max:15',
            'comments'              => 'required|string|max:300',
            'invoice'               => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'picture_1'             => 'required|image|mimes:jpeg,png,jpg|max:2048',  // For multiple pictures
            'picture_2'             => 'image|mimes:jpeg,png,jpg|max:2048',  // For multiple pictures
            'picture_3'             => 'image|mimes:jpeg,png,jpg|max:2048',  // For multiple pictures
            'picture_4'             => 'image|mimes:jpeg,png,jpg|max:2048',  // For multiple pictures
            'picture_5'             => 'image|mimes:jpeg,png,jpg|max:2048',  // For multiple pictures
        ];
    }

    public function messages()
    {
        return [
            'query_type.required' => 'The Query Type is required.',
            'query_type.in' => 'The selected Query Type is invalid.',

            'complaint_type.required' => 'The Complaint Type is required.',
            'complaint_type.in' => 'The selected Complaint Type is invalid.',
            
            'inquiry_type.in' => 'The selected Inquiry Type is invalid.',
            
            'order_id.required' => 'The order ID is required.',
            'order_id.integer' => 'The order ID must be an integer.',
            'order_id.min' => 'The order ID must be a positive integer.',
            'order_id.unique'  => 'Complaint has already been submitted for this order.',
            
            'name.required' => 'The Name is required.',
            'name.string' => 'The Name must be a string.',
            'name.max' => 'The Name may not be greater than :max characters.',
            
            'email.required' => 'The Email is required.',
            'email.email' => 'The Email must be a valid email address.',
            'email.max' => 'The Email may not be greater than :max characters.',
            
            'mobile_number.required' => 'The Mobile number is required.',
            'mobile_number.string' => 'The Mobile number must be a string.',
            'mobile_number.min' => 'The Mobile number must be at least 10 characters.',
            'mobile_number.max' => 'The Mobile number may not be greater than 15 characters.',
            
            'comments.required' => 'The Comments are required.',
            'comments.string' => 'The Comments must be a string.',
            'comments.max' => 'The Comments may not be greater than :max characters.',
            
            'invoice.required' => 'The Invoice is required.',
            'invoice.image' => 'The Invoice must be an image.',
            'invoice.mimes' => 'The Invoice must be a file of type: jpeg, png, jpg, gif.',
            'invoice.max' => 'The Invoice may not be greater than 2048 kilobytes.',

            'picture_1.required' => 'Picture 1 is required.',
            'picture_1.image' => 'Picture 1 must be an image.',
            'picture_1.mimes' => 'Picture 1 must be a file of type: jpeg, png, jpg, gif.',
            'picture_1.max' => 'Picture 1 may not be greater than 2048 kilobytes.',
            
            'picture_2.image' => 'Picture 2 must be an image.',
            'picture_2.mimes' => 'Picture 2 must be a file of type: jpeg, png, jpg, gif.',
            'picture_2.max' => 'Picture 2 may not be greater than 2048 kilobytes.',
            
            'picture_3.image' => 'Picture 3 must be an image.',
            'picture_3.mimes' => 'Picture 3 must be a file of type: jpeg, png, jpg, gif.',
            'picture_3.max' => 'Picture 3 may not be greater than 2048 kilobytes.',
           
            'picture_4.image' => 'Picture 4 must be an image.',
            'picture_4.mimes' => 'Picture 4 must be a file of type: jpeg, png, jpg, gif.',
            'picture_4.max' => 'Picture 4 may not be greater than 2048 kilobytes.',
            
            'picture_5.image' => 'Picture 5 must be an image.',
            'picture_5.mimes' => 'Picture 5 must be a file of type: jpeg, png, jpg, gif.',
            'picture_5.max' => 'Picture 5 may not be greater than 2048 kilobytes.',
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
            'message' => $validator->errors(),
        ]));
    }
}
