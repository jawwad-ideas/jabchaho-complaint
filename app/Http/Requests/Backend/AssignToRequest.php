<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssignToRequest extends FormRequest
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
            'userId'     => 'required|exists:users,id',
            'priorityId' =>  'required|exists:complaint_priorities,id',
        ];
    }

    public function messages()
    {
        return [
            
            #
            'userId.required'   => 'Select a user to assign this complaint to.',
            'userId.exists'     => 'Invalid ID selected.',
            #
            'priorityId.required'   => 'Priority is required',
            'priorityId.exists'     => 'Priority is invalid!',
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
