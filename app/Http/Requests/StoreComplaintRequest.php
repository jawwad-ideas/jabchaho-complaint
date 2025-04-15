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
            'order_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile_number' => 'required',
            'complaint_type' => 'required|string',
            'services' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'complaint_priority_id' => 'required|exists:complaint_priorities,id',
            'comments' => 'required|string|max:1000',
            'attachments' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Order is required.',
            'order_id.numeric' => 'Order must be a valid number.',
            
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',

            'mobile_number.required' => 'Mobile No is required.',
            #'mobile_number.digits_between' => 'Mobile number must be 13 digits.',

            'complaint_type.required' => 'Please select a complaint type.',
            'services.required' => 'Please select a service.',

            'user_id.exists' => 'The selected user is invalid.',
            'complaint_priority_id.exists' => 'The selected priority is invalid.',

            'comments.required' => 'Comments is required.',
            'comments.max' => 'Comments cannot exceed 1000 characters.',

            'attachments.file' => 'The attachment must be a valid file.',
            'attachments.max' => 'The attachment must not be greater than 2MB.',
            'attachments.mimes' => 'Attachment must be a file of type: jpg, jpeg, png, pdf, doc, docx.',
        ];
    }
}
