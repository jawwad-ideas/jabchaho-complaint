<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Complainant;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use App\Http\Requests\Backend\ComplainantUpdateRequest;
use Illuminate\Support\Facades\Auth;

 

class ComplainantController extends Controller
{
    /**
     * Display all users
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        $filterData                     = array();
        $dashboardFilter                = $request->query('dashboard_filter');

        $complainants = Complainant::select('*');
        //dashboardFilter [all, day ,week and etc]
        if(!empty($dashboardFilter))
        {
            $datesArray = Helper::getDateByFilterValue($dashboardFilter);

            $startDate              = Arr::get($datesArray, 'startDate');
            $endDate                = Arr::get($datesArray, 'endDate');

            $complainants->whereBetween('created_at', [$startDate, $endDate]);
        }
        // Apply filters
        $complainant_id = $request->input('complainant_id');
        $name           = $request->input('name');
        $email          = $request->input('email');
        $mobile_number  = $request->input('mobile_number');
        $cnic           = $request->input('cnic');
        $gender         = $request->input('gender');

        if (!empty($complainant_id)) {
            $complainants->where('complainants.id', 'like', '%' . $complainant_id . '%');
        }

        if (!empty($name)) {
            $complainants->where('complainants.full_name', 'like', '%' . $name . '%');
        }

        if (!empty($email)) {
            $complainants->where('complainants.email', 'like', '%' . $email . '%');
        }

        if (!empty($mobile_number)) {
            $complainants->where('complainants.mobile_number', 'like', '%' . $mobile_number . '%');
        }

        if (!empty($cnic)) {
            $complainants->where('complainants.cnic', 'like', '%' . $cnic . '%');
        }

        if (isset($gender)) {
            $complainants->where('complainants.gender', $gender);
        }

        $filterData = [
            'complainant_id' => $complainant_id,
            'name'           => $name,
            'email'          => $email,
            'mobile_number'  => $mobile_number,
            'cnic'           => $cnic,
            'gender'         => $gender,
        ];

        $userWise = true;
        if(Auth::user()->hasRole('admin'))
        {
            $userWise = false;
        }
        else if(Auth::user()->hasRole('call-center'))
        {
            $userWise = false;
        }

        // Paginate the results
        if($userWise)
        {
            $userId= Auth::guard('web')->user()->id;
            $query = $complainants->where(['user_id' =>$userId]);
        }
    
        $complainantsResult            = $complainants->orderBy('id', 'DESC')->paginate(config('constants.per_page'));
        $data['complainants']          = $complainantsResult;
        $data['genderOptions']         = config('constants.gender_options');
        $data['filterData']            = $filterData;

        return view('backend.complainants.index')->with($data);
    }

    /**
     * Show complainant data
     * 
     * @param Complainant $Complainant
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Complainant $complainant) 
    {
        return view('backend.complainants.show', [
            'complainant' => $complainant
        ]);
    }

    public function destroy($complainantId) 
    {
        $complainantObject = new Complainant;
        $deleted           = $complainantObject->deleteComplainant($complainantId);

        if($deleted)
        {
            return redirect()->back()->with('success', 'Complainant has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }

    }

    public function edit(Complainant $complainant) 
    {
        return view('backend.complainants.edit', [
            'complainant' => $complainant,
        ]);
    }

    public function update(ComplainantUpdateRequest $request)
    {
        $complainantObject = new Complainant;
        $complainantId     = $request->input('complainant_id');
        $validateValues = $request->validated();
        $name           = Arr::get($validateValues,'full_name');
        $email          = Arr::get($validateValues,'email');
        $gender         = Arr::get($validateValues,'gender');
        $cnic           = Arr::get($validateValues,'cnic');
        $mobile         = Arr::get($validateValues,'mobile_number');

        $data = 
        [
            'full_name'     => $name,
            'email'         => $email,
            'gender'        => $gender,
            'cnic'          => $cnic,
            'mobile_number' => $mobile
        ];

        $complainant = Complainant::where(['id'=>$complainantId]);
        $updated     = $complainant->update($data);

        if($updated)
        {
            return redirect()->back()->with('success', 'Complainant has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }
}
