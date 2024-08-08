<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReviewRequest extends FormRequest
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
            'order_id'              => 'nullable',
            'name'                  => 'nullable',
            'email'                 => 'nullable',
            'mobile_number'         => 'nullable',
            'rating'                => 'required',
            'comments'              => 'required|string|max:300',
        ];
    }

    public function messages()
    {
        return [

            'rating.required'               => 'Rating is required.',
            
            'comments.required'             => 'The Comments are required.',
            'comments.string'               => 'The Comments must be a string.',
            'comments.max'                  => 'The Comments may not be greater than :max characters.',

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
