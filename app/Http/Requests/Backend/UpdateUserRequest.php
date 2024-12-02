<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        // Let's get the route param by name to get the User object value
        $user = request()->route('user');

        $passwordCondition          = 'nullable|min:6|max:20';
        if(!empty($this->request->get('password')) && !is_null($this->request->get('password')))
        {
            $confirmPasswordCondition   = 'required|same:password|min:6|max:20';
        }else{
            $confirmPasswordCondition   = 'nullable|same:password|min:6|max:20';
        }

        return [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email,'.$user->id,
            'username' => 'required|unique:users,username,'.$user->id,
            'password' => $passwordCondition,
            'confirm_password'=>$confirmPasswordCondition
        ];
    }
}
