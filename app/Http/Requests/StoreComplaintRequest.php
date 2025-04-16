<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all users, adjust if needed
    }

    public function rules(): array
    {
        return [
            'order_id'              => 'required|unique:complaints,order_id|integer|min:1',
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:150',
            'mobile_number'         => 'required|string|min:13',
            'complaint_type'        => 'required|in:' . implode(',', array_keys(config('constants.complaint_type'))),
            'service_id'            => 'required|exists:services,id',
            'user_id'               => 'required|exists:users,id',
            'complaint_priority_id' => 'required|exists:complaint_priorities,id', 
            'comments'              => 'required|string|max:1000',
            'attachments'           => 'required',
            'attachments.*'         => 'image|mimes:jpeg,png,jpg,image/jpeg,image/png,image/jpg|max:1048576',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required'             => 'The order is required.',
            'order_integer'                 => 'The order must be an integer.',
            'order_min'                     => 'The order must be a positive integer.',
            'order_id.unique'               => 'Complaint has already been submitted for this order.',
            
            'name.required'                 => 'The Name is required.',
            'name.string'                   => 'The Name must be a string.',
            'name.max'                      => 'The Name may not be greater than :max characters.',
            
            'email.required'                => 'The Email is required.',
            'email.email'                   => 'The Email must be a valid email address.',
            'email.max'                     => 'The Email may not be greater than :max characters.',
            
            'mobile_number.required'        => 'The Mobile number is required.',
            'mobile_number.string'          => 'The Mobile number must be a string.',
            'mobile_number.min'             => 'The Mobile number must be at least 13 characters.',
            #'mobile_number.max'             => 'The Mobile number may not be greater than 15 characters.',

            'complaint_type.required'       => 'The Complaint/Inquiry Type is required.',
            'complaint_type.in'             => 'The selected Complaint/Inquiry Type is invalid.',

            'service_id.required'           => 'Please select a Service.',
            'service_id.exists'             => 'The selected Service is invalid.',

            
            'user_id.required'              => 'Please select a Assign To.',
            'user_id.exists'                => 'The selected Assign To: is invalid.',

            'complaint_priority_id.required' => 'Please select a Priority.',
            'complaint_priority_id.exists'   => 'The selected Priority is invalid.',

            'comments.required'              => 'Comments is required.',
            'comments.max'                   => 'Comments cannot exceed 1000 characters.',
            'comments.string'                => 'Comments must be a string.',

            'attachments.required'          => 'Please upload at least one file.',
            'attachments.*.image'           => 'The Attachments must be an image.',
            'attachments.*.mimes'           => 'The Attachments must be a file of type: jpeg, png, jpg',
            'attachments.*.max'             => 'The Attachments may not be greater than :max kilobytes.',
        ];
    }
}
