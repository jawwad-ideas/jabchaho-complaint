<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReAssignRequest extends FormRequest
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
            'mnaId'     => 'required|exists:users,id',
            'mpaId'     => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            #
            'mnaId.required'   => 'Select a valid MNA to assign this complaint to.',
            'mnaId.exists'     => 'Invalid ID selected.',
            'mpaId.required'   => 'Select a valid MPA to assign this complaint to.',
            'mpaId.exists'     => 'Invalid ID selected.',
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
        if($this->ajax()){
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors(),
                'status' => true,
            ]));
        }
        
    }
}
