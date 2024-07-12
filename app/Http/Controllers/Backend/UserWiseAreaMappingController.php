<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UserWiseAreaMappingAddRequest;
use App\Http\Requests\Backend\UserWiseAreaMappingUpdateRequest;
use App\Models\District;
use App\Models\NationalAssembly;
use App\Models\NewArea;
use Illuminate\Support\Arr;
use App\Models\ProvincialAssembly;
use App\Models\User;
use App\Models\UserWiseAreaMapping;
use App\Models\NewAreaGrid;
use Illuminate\Http\Request;

class UserWiseAreaMappingController extends Controller
{
    public function index(Request $request)
    {
        $query = UserWiseAreaMapping::with('user', 'newArea', 'nationalAssembly', 'provincialAssembly')->groupBy('user_id')->orderBy('id','desc');

        $userId     = $request->input('user_id');
        $newArea    = $request->input('new_area_id');
        $na         = $request->input('national_assembly_id');
        $ps         = $request->input('provincial_assembly_id');

        if($userId) {
            $query->where('user_wise_area_mappings.user_id', '=' ,$userId);
        }

        if($newArea)
        {
            $query->where('user_wise_area_mappings.new_area_id', '=' , $newArea);
        }
        if($na) {
            $query->where('user_wise_area_mappings.national_assembly_id', '=' , $na);
        }

        if($ps)
        {
            $query->where('user_wise_area_mappings.provincial_assembly_id', '=' , $ps);
        }

        $areas = $query->paginate(config('constants.per_page'));

        $users      = User::all()->whereNull('deleted_at');
        $newAreas   = NewArea::all()->whereNull('deleted_at');
        $nAs        = NationalAssembly::all()->whereNull('deleted_at');
        $pAs        = ProvincialAssembly::all()->whereNull('deleted_at');
        // $finalArray = [];
        // foreach ($query->get() as $record) {
        //     $model = new UserWiseAreaMapping;
        //     $areasArray = $model->getAreas($record['user_id']);
        //     $record['area_names'] = $areasArray;
        // }

        $data  = [
            'areas'     => $areas,
            'users'     => $users,
            'newAreas'  => $newAreas,
            'nAs'       => $nAs,
            'pAs'       => $pAs,
            'UserWiseAreaMapping' => new UserWiseAreaMapping
        ];
        return view('backend.new_area_grid.user_wise_area_mapping.index')->with($data);
    }

    public function addUserWiseAreaMappingForm()
    {
        $users      = User::all();
        $new_areas  = NewArea::all();
        $nas        = NationalAssembly::all();
        $pas        = ProvincialAssembly::all();
        $districts   = District::all();
        return view('backend.new_area_grid.user_wise_area_mapping.create',[
            'users'     =>$users,
            'new_areas' =>$new_areas,
            'districts' => $districts,
            'nas'       =>$nas,
            'pas'       =>$pas
        ]);
    }

    public function create(UserWiseAreaMappingAddRequest $request)
    {
        $validateValues = $request->validated();
        $selectedAreaIds = $request->input('new_area_id');
        $na = $request->input('national_assembly_id');
        $ps = $request->input('provincial_assembly_id');
        $districtId = $request->input('district_id');
        $getUsers = UserWiseAreaMapping::select("user_id")->where('national_assembly_id', $na)
        ->where('provincial_assembly_id', $ps)
        ->groupBy('user_id')->get()->toArray();
        
        try {
            //working to add data in user_wise_area_mappings
            if(!empty($getUsers)){
                foreach ($getUsers as $user) {
                    //delete previous data
                    UserWiseAreaMapping::where(['user_id'=>$user['user_id']])->delete();
                    foreach ($selectedAreaIds as $areaId) {
                        $data = [
                            'user_id' => $user['user_id'],
                            'new_area_id' => $areaId,
                            'district_id' => $districtId,
                            'national_assembly_id' => $na,
                            'provincial_assembly_id' => $ps
                        ];
                        $created = UserWiseAreaMapping::insert($data);
                    }
                }
            }else{

            }
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong error : ".$th]);
        }

        if (!empty($created)) {
            return redirect()->route('area.mapping.index')->with('success', 'Area Mapping has been created successfully.');
        } else {
            return redirect()->back()->withErrors(['error' => "Whoops, please verify NA/PS Assigned to any user or not"]);
        }
    }

    public function edit($id)
    {
        $users      = User::all();
        $new_areas  = NewArea::all();
        $nas        = NationalAssembly::all();
        $pas        = ProvincialAssembly::all();
        $areaMapping = UserWiseAreaMapping::where('user_id', $id)->groupBy('user_id');
        //$newArea = NewAreaGrid::where(['new_area_id' => $areaMapping->first()->new_area_id,'national_assembly_id' => $areaMapping->first()->national_assembly_id,'provincial_assembly_id' => $areaMapping->first()->provincial_assembly_id]);
        $districtId = $areaMapping->first()->district_id;

        $districts   = District::all();
        $data = [
            'users'         =>$users,
            'new_areas'     =>$new_areas,
            'districts' => $districts,
            'district_id' => $districtId,
            'nas'           =>$nas,
            'pas'           =>$pas,
            'area_mappings' => $areaMapping->first(),
            'UserWiseAreaMapping' => new UserWiseAreaMapping
        ];
        return view('backend.new_area_grid.user_wise_area_mapping.edit')->with($data);
    }

    public function update(UserWiseAreaMappingUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $userId = $request->input('id');
        //delete all reocrds of the user
        UserWiseAreaMapping::where(['user_id'=>$userId])->delete();
        $selectedAreaIds = $request->input('new_area_id');
        foreach ($selectedAreaIds as $areaId) {
            $data = [
                'user_id' => $userId,
                'new_area_id' => $areaId,
                'district_id' => $request->input('district_id'),
                'national_assembly_id' => $request->input('national_assembly_id'),
                'provincial_assembly_id' => $request->input('provincial_assembly_id')
            ];
            $created = UserWiseAreaMapping::insert($data);
        }
        if ($created) {
            return redirect()->route('area.mapping.index')->with('success', 'Area Mapping has been updated successfully.');
        } else {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }

    public function destroy($area_id)
    {
        $area_mapping = new UserWiseAreaMapping();
        $deleted = $area_mapping->deleteAreaMapping($area_id);

        if ($deleted) {
            return redirect()->back()->with('success', 'Area has been deleted successfully.');
        } else {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }
    }
}
