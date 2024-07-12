<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class NewAreaUpdateRequest extends FormRequest
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
            'name'    => 'required',
            'city_id' => 'required|int',
        ];
    }

    public function messages()
    {
        return [
            'name.required'    => 'Please enter a name for this New Area!',
            'city_id.required' => 'Please select a City!',
            'city_id.int'      => 'Please enter a valid value!',
        ];
    }
}
