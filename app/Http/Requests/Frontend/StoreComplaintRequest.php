<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreComplaintRequest extends FormRequest
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
        $levelOne                          =  $this->request->get('level_one');
        $levelTwo                          =  $this->request->get('level_two');
        $cityId                            =  $this->request->get('city_id');
       
        $roleHaveAccess                    =  $this->request->get('roleHaveAccess');
        $flag = $this->request->get('area_flag');
        //die($flag);
        if(!empty($roleHaveAccess))
        {
            $priorityIdCondition    = ['required','exists:complaint_priorities,id'];
            $fullNameCondition      = 'required|max:50|regex:/^[\pL\s\-]+$/u';
            $genderCondition        = 'required|in:' . implode(',', array_keys(config('constants.gender_options')));
            $cnicCondition          = 'required|max:15|min:15|regex:/^\d{5}-\d{7}-\d{1}$/';
            $emailCondition         = 'required|email:rfc,dns|max:50';
            $phoneNumber            = 'required';
            
        }else
        {
            $priorityIdCondition    = 'nullable';
            $fullNameCondition      = 'nullable';
            $genderCondition        = 'nullable';
            $cnicCondition          = 'nullable';
            $emailCondition         = 'nullable';
            $phoneNumber         = 'nullable';
        }

       
        if ($flag) {

            return [
                'roleHaveAccess'                    => 'nullable',
                'level_one'                         => ['required','exists:categories,id,level,1'], // Table: 'categories', Column: 'id', Condition: 'level_one = 1'],
                'level_two'                         => ['required','exists:categories,id,parent_id,'.$levelOne],
                'level_three'                       => ['required','exists:categories,id,parent_id,'.$levelTwo],
                'city_id'                           => ['required','exists:cities,id'],
                'district_input'                       => ['required'],
                'national_assembly_input'              => ['required'],
                'provincial_assembly_input'            => ['required'],
                'new_area_id'                       => ['required','exists:new_areas,id,city_id,'.$cityId],
                'title'                             => 'required|max:255',
                'nearby'                            => 'required|max:200',
                'description'                       => 'required|max:65000',
                'address'                           => 'required|max:65000',
                
                #admin side complaint Fields
                'priorityId'                        => $priorityIdCondition,
                'full_name'                         => $fullNameCondition,
                'gender'                            => $genderCondition, 
                'cnic'                              => $cnicCondition,
                'email'                             => $emailCondition,
                'mobile_number' => $phoneNumber,
            ];
        
        }else{

        
            return [
                'roleHaveAccess'                    => 'nullable',
                'level_one'                         => ['required','exists:categories,id,level,1'], // Table: 'categories', Column: 'id', Condition: 'level_one = 1'],
                'level_two'                         => ['required','exists:categories,id,parent_id,'.$levelOne],
                'level_three'                       => ['required','exists:categories,id,parent_id,'.$levelTwo],
                'city_id'                           => ['required','exists:cities,id'],
                'district_id'                       => ['required','exists:districts,id'],
                // 'sub_division_id'                   => ['required','exists:sub_divisions,id'],
                // 'charge_id'                         => ['required','exists:charges,id'],
                // 'union_council_id'                  => ['required','exists:union_councils,id'],
                // 'ward_id'                           => ['required','exists:wards,id'],
                'national_assembly_id'              => ['required','exists:national_assemblies,id'],
                'provincial_assembly_id'            => ['required','exists:provincial_assemblies,id'],
                'new_area_id'                       => ['required','exists:new_areas,id,city_id,'.$cityId],
                'title'                             => 'required|max:255',
                'nearby'                            => 'required|max:200',
                'description'                       => 'required|max:65000',
                'address'                           => 'required|max:65000',
                
                #admin side complaint Fields
                'priorityId'                        => $priorityIdCondition,
                'full_name'                         => $fullNameCondition,
                'gender'                            => $genderCondition, 
                'cnic'                              => $cnicCondition,
                'email'                             => $emailCondition,
                'mobile_number' => $phoneNumber,
            ];
        }
    }


    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        $flag = $this->request->get('area_flag');
        if ($flag) {
         return [

                //level_one
                'level_one.required'                        => 'The Level one field is required!',
                'level_one.exists'                          => 'The Level one is invalid!',
                     
                #province
                'province.required'                         => 'The Province field is required!',
                'province.max'                              => 'The Province field must not be greater than :max characters.',

                ##City
                'city_id.required'                          => 'The City field is required!',
                'city_id.exists'                            => 'The City field is invalid!',
                
                #district
                'district_input.required'                      => 'The District field is required!',

                #national_assembly_id
                'national_assembly_input.required'             => 'The NA field is required!',

                #provincial_assembly_id
                'provincial_assembly_input.required'           => 'The PS field is required!',
                
                #new_area_id
                'new_area_id.required'                      => 'The New Area field is required!',
                'new_area_id.exists'                        => 'The New Area field is invalid!',
                
                #title
                'title.required'                           => 'The Complaint Title field is required!',
                'title.max'                                => 'The Complaint Title field must not be greater than :max characters.',

                #description
                'description.required'                     => 'The Complaint Description field is required!',
                'description.max'                          => 'The Complaint Description field must not be greater than :max characters.', 
                
                #admin side complaint Fields

                //priorityId
                'priorityId.required'                     => 'The Priority field is required!',
                'priorityId.exists'                       => 'The Priority is invalid!',
                
                #full_name
                'full_name.required'                      => 'The Full Name field is required!',
                'full_name.max'                           => 'The Full Name must not be greater than :max characters.',
                'full_name.regex'                         => 'The Full Name format is invalid',

                #gender
                'gender.required'                         => 'The Gender field is required!',
                'gender.in'                               => 'The Gender is not valid!',
                
                #cnic
                'cnic.max'                                => 'The CNIC must not be greater than :max characters.!',
                'cnic.min'                                => 'The CNIC must not be less than :min characters.!',
                'cnic.regex'                              => 'The CNIC must be numeric!',
                'cnic.unique'                             => 'Sorry, This CNIC is already used by another user.!',
                
                #email
                'email.required'                          => 'The Email Address is required!',
                'email.email'                             => 'The Email Address field must be a valid email address!',
                'email.unique'                            => 'Sorry, This Email Address is already used by another user. Please try with different one, thank you.',
                'email.max'                               => 'Email Address must not be greater than :max characters.',

                #address
                'address.required'                        => 'The Address field is required!',
                'address.max'                             => 'The Address field must not be greater than :max characters.', 

                #nearby
                'nearby.required'                           => 'The Nearby field is required!',
                'nearby.max'                                => 'The Nearby field must not be greater than :max characters.',
                'mobile_number.required' => 'The Mobile Number field is required!',
         ];
        }else{
            return [

                //level_one
                'level_one.required'                        => 'The Level one field is required!',
                'level_one.exists'                          => 'The Level one is invalid!',
                     
                #province
                'province.required'                         => 'The Province field is required!',
                'province.max'                              => 'The Province field must not be greater than :max characters.',

                ##City
                'city_id.required'                          => 'The City field is required!',
                'city_id.exists'                            => 'The City field is invalid!',
                
                #district
                'district_id.required'                      => 'The District field is required!',
                'district_id.exists'                        => 'The District field is invalid!',

                #sub_division
                // 'sub_division_id.required'                  => 'The Sub Division field is required!',
                // 'sub_division_id.exists'                    => 'The Sub Division field is invalid!',

                // #charge_id
                // 'charge_id.required'                        => 'The Charge field is required!',
                // 'charge_id.exists'                          => 'The Charge field is invalid!',

                // #union_council_id
                // 'union_council_id.required'                 => 'The UC field is required!',
                // 'union_council_id.exists'                   => 'The UC field is invalid!',

                // #ward_id
                // 'ward_id.required'                          => 'The Ward field is required!',
                // 'ward_id.exists'                            => 'The Ward field is invalid!',

                #national_assembly_id
                'national_assembly_id.required'             => 'The NA field is required!',
                'national_assembly_id.exists'               => 'The NA field is invalid!',

                #provincial_assembly_id
                'provincial_assembly_id.required'           => 'The PS field is required!',
                'provincial_assembly_id.exists'             => 'The PS field is invalid!',
                
                #new_area_id
                'new_area_id.required'                      => 'The New Area field is required!',
                'new_area_id.exists'                        => 'The New Area field is invalid!',
                
                #title
                'title.required'                           => 'The Complaint Title field is required!',
                'title.max'                                => 'The Complaint Title field must not be greater than :max characters.',

                #description
                'description.required'                     => 'The Complaint Description field is required!',
                'description.max'                          => 'The Complaint Description field must not be greater than :max characters.', 
                
                #admin side complaint Fields

                //priorityId
                'priorityId.required'                     => 'The Priority field is required!',
                'priorityId.exists'                       => 'The Priority is invalid!',
                
                #full_name
                'full_name.required'                      => 'The Full Name field is required!',
                'full_name.max'                           => 'The Full Name must not be greater than :max characters.',
                'full_name.regex'                         => 'The Full Name format is invalid',

                #gender
                'gender.required'                         => 'The Gender field is required!',
                'gender.in'                               => 'The Gender is not valid!',
                
                #cnic
                'cnic.max'                                => 'The CNIC must not be greater than :max characters.!',
                'cnic.min'                                => 'The CNIC must not be less than :min characters.!',
                'cnic.regex'                              => 'The CNIC must be numeric!',
                'cnic.unique'                             => 'Sorry, This CNIC is already used by another user.!',
                
                #email
                'email.required'                          => 'The Email Address is required!',
                'email.email'                             => 'The Email Address field must be a valid email address!',
                'email.unique'                            => 'Sorry, This Email Address is already used by another user. Please try with different one, thank you.',
                'email.max'                               => 'Email Address must not be greater than :max characters.',

                #address
                'address.required'                        => 'The Address field is required!',
                'address.max'                             => 'The Address field must not be greater than :max characters.', 

                #nearby
                'nearby.required'                           => 'The Nearby field is required!',
                'nearby.max'                                => 'The Nearby field must not be greater than :max characters.',
                'mobile_number.required' => 'The Mobile Number field is required!',
         ];
        }
    }


    /**
    * Get the error messages for the defined validation rules.*
    * @return array
    */
    protected function failedValidation(Validator $validator)
    {
        if($this->ajax())
        {
            throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => true
            ]));
        }

        return parent::failedValidation($validator);
    }
}
