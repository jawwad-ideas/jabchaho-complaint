<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\UploadDocumentNameValidation;

class UploadCompalintFilesRequest extends FormRequest
{/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {       
        return [
            'attachment.*'                => 'nullable|mimes:pdf,doc,docx,mp3,mp4,txt,png,jpeg,zip,webp,avif|min:1|max:15360', 
        ];       
    }


      /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
         return [
                     
                #attachment
                //'attachment.*.required'   => 'The File is required!',
                'attachment.*.mimes'      => 'The File must be a file of type: pdf,doc,docx,mp3,mp4,txt,png,jpeg,webp,avif,zip!',
                'attachment.*.min'        => 'The File must be at least 1 kilobytes!',
                'attachment.*.max'        => 'The File must not be greater than 5 Mb!',
         ];
    }


    /**
    * Get the error messages for the defined validation rules.*
    * @return array
    */
    protected function failedValidation(Validator $validator)
    { 
        throw new HttpResponseException(response()->json([
        'errors' => $validator->errors()->all(),
        'status' => true
        ]));
    }
}
