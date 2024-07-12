<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class CmsRequest extends FormRequest
{
    /**
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
        $id =(int) \Request::segment(4);

        return [
            'page'                  => 'required|regex:/^[a-zA-Z\s]*$/|max:100|unique:cms,page,'.$id,
            'title'                 => 'nullable|max:200',
            'content'               => 'required|max:4294967200',
            'meta_keywords'         => 'nullable|max:65000',
            'meta_description'      => 'nullable|max:65000',      
            'is_enabled'            => 'required|in:' . implode(',', array_keys(config('constants.boolean_options'))),  
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
            'page.required'                      => 'The Page field is required!',
            'page.regex'                         => 'The Page must only contain letters and space.',
            'page.max'                           => 'The Page must not be greater than :max characters.!',
            'page.unique'                        => 'The Page has already been taken.',

            'title.max'                          => 'The Title must not be greater than :max characters.!',

            'content.required'                   => 'The Content field is required!',
            'content.max'                        => 'The Content must not be greater than :max characters.!',

            'meta_keywords.max'                  => 'The Meta keywords must not be greater than :max characters.!',

            'meta_description.max'               => 'The Meta description must not be greater than :max characters.!',

            'is_enabled.required'                => 'Please select Enable Option?',
            'is_enabled.in'                      => 'Invalid Enable Option? !',

         ];
    }     

}
