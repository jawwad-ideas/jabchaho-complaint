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

        \Log::info('Review API Payloads');
        \Log::info(print_r($_POST,true));
        return [
            'order_id'              => 'required|unique:reviews,order_id|integer|min:1',
            'name'                  => 'required|string|max:100',
            'email'                 => 'nullable',
            'mobile_number'         => 'required|string|min:10|max:15',
            'pricing_value'         => 'required',
            'service_quality'       => 'required',
            'timelines_convenience' => 'required',
            'comments'              => 'required|string|max:300',
        ];
    }

    public function messages()
    {
        return [

            'order_id.required'             => 'The order ID is required.',
            'order_id.integer'              => 'The order ID must be an integer.',
            'order_id.min'                  => 'The order ID must be a positive integer.',
            'order_id.unique'               => 'Review has already been submitted for this order.',

            'name.required'                 => 'The Name is required.',
            'name.string'                   => 'The Name must be a string.',
            'name.max'                      => 'The Name may not be greater than :max characters.',

            'mobile_number.required'        => 'The Mobile number is required.',
            'mobile_number.string'          => 'The Mobile number must be a string.',
            'mobile_number.min'             => 'The Mobile number must be at least 10 characters.',
            'mobile_number.max'             => 'The Mobile number may not be greater than 15 characters.',


            'pricing_value.required'        => 'Picing Value is required.',
            'service_quality.required'      => 'Srvice Quality is required.',
            'timelines_convenience.required'=> 'Timelines Convenience is required.',
            
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
