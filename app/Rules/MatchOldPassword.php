<?php
  
namespace App\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
  
class MatchOldPassword implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    private $error = '';
    public function passes($attribute, $value)
    {
        $postData = request()->all();

        $currentPassword = Arr::get($postData, 'current_password');
        $newPassword = Arr::get($postData, 'new_password');

        $hashedPassword = Auth::guard('complainant')->user()->password;
        
        #check Current password is valid or not
        if(Hash::check($currentPassword,$hashedPassword)){
            
            #check Current password and new password are not same
            if(Hash::check($newPassword,$hashedPassword)){
                $this->error = ' Current and New Passwords are same';
            }
            else{
                return true;
            }
            
        }
        else
        {
            $this->error = 'The :attribute is Invalid!.';
        }
    }
   
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error;
    }
}