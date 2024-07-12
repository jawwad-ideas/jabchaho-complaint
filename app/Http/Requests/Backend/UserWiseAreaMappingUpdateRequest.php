<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UserWiseAreaMappingUpdateRequest extends FormRequest
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
            'user_id'                         => 'required|int',
            'new_area_id'                     => 'required',
            'district_id'                     => 'required',
            'national_assembly_id'            => 'required|int',
            'provincial_assembly_id'          => 'required|int',
        ];
    }
    public function messages()
    {
        return [
            'user_id.required'                  => 'User is required!',
            'new_area_id.required'              => 'New Area is required!',
            'national_assembly_id.required'     => 'NA is required!',
            'provincial_assembly_id.required'   => 'PS is required!',
            'district_id.required'              => 'District is required',
        ];
    }
}
