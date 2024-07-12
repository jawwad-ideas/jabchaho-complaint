<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintStatusCreateRequest extends FormRequest
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
            'name'       => 'required',
            'is_enabled' => 'required|min:1|max:1|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The Name field is required!',
            'is_enabled'    => 'The is enabled field is required!',
        ];
    }
}
