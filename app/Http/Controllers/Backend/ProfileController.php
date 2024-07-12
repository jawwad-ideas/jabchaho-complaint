<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller; 
use App\Http\Controllers\ComplaintBaseController;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\AssignToRequest;
use App\Models\ComplaintDocument;
use App\Models\City;
use App\Models\District;
use App\Models\Category;
use App\Http\Requests\Frontend\StoreComplaintRequest;
use Illuminate\Support\Arr;
use App\Helpers\Helper;
use App\Models\Complainant;
use App\Models\ComplaintStatus;
use App\Models\ComplaintFollowUp;
use App\Http\Requests\Backend\ComplaintFollowUpRequest;
use App\Models\Charge;
use App\Models\ComplaintPriority;
use App\Models\NationalAssembly;
use App\Models\NewArea;
use App\Models\ProvincialAssembly;
use App\Models\SubDivision;
use App\Models\UnionCouncil;
use App\Models\Ward;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Backend\ProfileUpdateRequest;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    //

    public function index(Request $request)
    {
        if(!empty(Auth::guard('web')->user()->id))
        {
            //web user id as loggedin 
            $loggedInUserId = Auth::guard('web')->user()->id;
            $userData = User::where(['id' => $loggedInUserId])->first();
        }
        else{
            //complainant user id as loggedin 
            $loggedInUserId = Auth::guard('complainant')->user()->id;
            $userData = Complainant::where(['id' => $loggedInUserId])->first();
        }
        $data = [
            'id'   => $loggedInUserId,
            'userData' => $userData
        ];
        return view('backend.profiles.index')->with($data);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $userId = $request->input('user_id');

        // Handle profile image upload (if any)
        if ($request->hasFile('profile_image')) {
            $uploadFolderPath = config('constants.files.profile');
            $filePath = public_path($uploadFolderPath);
            $image = $request->file('profile_image');

            $newName = rand().'-'.$userId. '.' . $image->getClientOriginalExtension();
            $image->move($filePath, $newName);

            $fieldName='profile_image';
            $this->updateImage($filePath,$fieldName,$newName);

            $validateValues['profile_image'] = $newName;
        }

        $updated = User::where(['id'=>$userId])->update($validateValues);


        if($updated)
        {
            return redirect()->route('profile.index')->with('success', 'Profile has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }
    }

    public function updateImage($filePath='', $fieldName='',$fieldValue=''){
        try{
            #remove old image
            $userId = Auth::guard('web')->user()->id;
            $userData = User::where('id', $userId)->first();
            $image = Arr::get($userData, $fieldName);
            $fileNameWithPath = $filePath.'/'.$image;
            $this->removeFile($fileNameWithPath);

            #update new file name
            $updated = $userData->update([$fieldName=>$fieldValue]);
            return $updated;

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
            
        }	
    }
}
