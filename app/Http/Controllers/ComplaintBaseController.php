<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ComplaintPriority;
use App\Models\City;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\ComplaintDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Http\Requests\Frontend\StoreComplaintRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;
use App\Models\Complainant;
use App\Models\UserWiseAreaMapping;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\NationalAssembly;
use App\Models\ProvincialAssembly;
use Illuminate\Support\Facades\URL;

class ComplaintBaseController extends Controller
{
    protected $complainantId = 0;
    protected $complaintPriorityId = NULL;
    protected $userId = NULL;

    public function create(Request $request)
    {
        $data = array();
        $categoryObject = new Category();
        $cityObject = new city();

        if (count($request->all()) === 0) {
            if (!empty(\Session::get('complaintFiles'))) {
                $complaintFiles = \Session::get('complaintFiles');

                foreach ($complaintFiles as $fileToRemove => $complaintFile) {
                    //get Files from session and remove
                    $fileNameWithPath = "temp/" . $fileToRemove;
                    $this->removeTempFile($fileNameWithPath);
                }

                $this->unsetcomplaintFilesSession();
            }
        }

        $complaintPriorities = ComplaintPriority::get()->toArray();

        $data['complaintPriorities'] = $complaintPriorities;
        $data['leveOne'] = $categoryObject->getFirstLevel();
        $data['cities'] = $cityObject->getCities();

        return $data;
    }

    public function unsetcomplaintFilesSession()
    {
        \Session::forget('complaintFiles');
    }

    public function store(StoreComplaintRequest $request)
    {
        try {
            $jsonData = [];
            $jsonData['status'] = false;
            $jsonData['message'] = '';
            $validateValues = $request->validated();

            $extras = [
                'mobile_number' => Arr::get($validateValues, 'mobile_number'),
                'cnic' => Arr::get($validateValues, 'cnic'),
                'email' => Arr::get($validateValues, 'email'),
                'full_name' => Arr::get($validateValues, 'full_name'),
            ];

            $flag = $request->get('area_flag');

            if (!$flag) {
                $nationalAssemblyId = Arr::get($validateValues, 'national_assembly_id');
                $provincialAssemblyId = Arr::get($validateValues, 'provincial_assembly_id');
                $newAreaId = Arr::get($validateValues, 'new_area_id');
            } else {
                $nationalAssemblyId = Arr::get($validateValues, 'national_assembly_input');
                $provincialAssemblyId = Arr::get($validateValues, 'provincial_assembly_input');
            }

            if (!empty($validateValues)) {
                $complainantId = $this->complainantId;

                $loggedInUserId = 0;
                // if(!empty(Auth::guard('web')->user()->id) && !empty($this->userId))
                // {
                //     // $loggedInUserId = Auth::guard('web')->user()->id;
                // }else{
                //     //get id for call center users etc
                //     //$loggedInUserId = Auth::guard('web')->user()->id;
                // }

                if (!empty(Auth::guard('web')->user()->id)) {
                    //web user id as loggedin
                    $loggedInUserId = Auth::guard('web')->user()->id;
                } else {
                    //complainant user id as loggedin
                    $loggedInUserId = Auth::guard('complainant')->user()->id;
                }

                if (!$flag) {
                    // Auto Complaint assign to MPA
                    $userWiseAreaMappingData = UserWiseAreaMapping::select('user_wise_area_mappings.user_id', 'model_has_roles.model_id', 'roles.name')
                        ->join('model_has_roles', 'model_has_roles.model_id', '=', 'user_wise_area_mappings.user_id')
                        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->where([
                            'user_wise_area_mappings.new_area_id' => $newAreaId,
                            'user_wise_area_mappings.national_assembly_id' => $nationalAssemblyId,
                            'user_wise_area_mappings.provincial_assembly_id' => $provincialAssemblyId,
                        ])
                        ->get()->toArray();

                    $assignToMpa = null;
                    $assignToMna = null;

                    if (!empty($userWiseAreaMappingData)) {
                        foreach ($userWiseAreaMappingData as $mapKey => $mapValue) {
                            if (strtolower($mapValue['name']) == 'mpa') {
                                // assign mpa_id
                                $assignToMpa = $mapValue['user_id'];
                            }
                            if (strtolower($mapValue['name']) == 'mna') {
                                // assign mna_id as user_id
                                $assignToMna = $mapValue['user_id'];
                            }
                        }
                    }
                } else {
                    $assignToMpa = null;
                    $assignToMna = null;
                }

                // update complaint number

                if (!$flag) {
                    $nationalAssembly = new NationalAssembly;
                    $nationalAssemblyName = $nationalAssembly->where('id', $nationalAssemblyId)->first()->name;
                    $provisionalAssembly = new ProvincialAssembly;
                    $provisionalAssemblyName = $provisionalAssembly->where('id', $provincialAssemblyId)->first()->name;
                    $prefix = $nationalAssemblyName . '|' . $provisionalAssemblyName;
                } else {
                    $prefix = $nationalAssemblyId . '|' . $provincialAssemblyId;
                }
                $complaintLastNum = Complaint::select("*")->orderBy('id', 'desc')->limit(1)->first();
                if ($complaintLastNum) {
                    // Increment the numeric part and prepend the prefix
                    $explodeLast = explode("|", $complaintLastNum->complaint_num);
                    $num = (int) $explodeLast[count($explodeLast) - 1];
                    $complaintNum = $prefix . '|' . ($num + 1);
                    Log::info('Complaint g=here with last id exist complaint number' . $complaintNum); // Informational message
                } else {
                    // Handle the case where no users exist yet
                    $complaintNum = $prefix . "|1001";
                    Log::info('Complaint g=here with no id exist' . $complaintNum); // Informational message
                }

                $validateValues['complainant_id'] = $complainantId;
                $validateValues['complaint_num'] = $complaintNum;
                $validateValues['complaint_priority_id'] = $this->complaintPriorityId;

                // Check if mna or mpa is empty and set it to null
                $validateValues['user_id'] = !empty($assignToMna) ? $assignToMna : null;
                $validateValues['mpa_id'] = !empty($assignToMpa) ? $assignToMpa : null;

                $validateValues['created_by'] = $loggedInUserId;
                $validateValues['extras'] = json_encode($extras);

                if (!Auth::user() && $flag) {
                    $validateValues['is_approved'] = 0;
                }

                //if mpa or mna is null set is approved is 0
                if ($assignToMna == null || $assignToMpa == null) {
                    $validateValues['is_approved'] = 0;
                }

                $complaintData = Complaint::create($validateValues);

                $complaintDocumnet = [];
                // insert Document table
                if (!empty($complaintData->id)) {
                    $complaintFiles = \Session::get('complaintFiles');
                    $complaintId = $complaintData->id;

                    // Send email to Complainant and user
                    $this->sendEmailToComplainant($complainantId, $complaintId);
                    if (!$flag) {
                        $this->sendEmailToAssignedUser($complainantId, $complaintId, $assignToMpa);
                        $this->sendEmailToAssignedUser($complainantId, $complaintId, $assignToMna);
                    }

                    if (!empty($complaintFiles)) {
                        $complaintDocumnet['complaint_id'] = $complaintId;
                        $complaintDocumnet['document_name'] = 'complaints';

                        foreach ($complaintFiles as $complaintFile) {
                            $fileName = Arr::get($complaintFile, 'file');
                            $complaintDocumnet['file'] = $fileName;
                            $complaintDocumnet['original_file'] = Arr::get($complaintFile, 'original_file');

                            $complaintDocument = ComplaintDocument::create($complaintDocumnet);

                            // Define the source file path (temporary file path)
                            $sourceFile = 'temp' . DIRECTORY_SEPARATOR . $fileName;
                            $sourceFilePath = Storage::disk('local')->path($sourceFile);

                            // Destination path for uploaded file
                            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'complaint_documents' . DIRECTORY_SEPARATOR . $fileName;

                            // Move file from temporary location to destination path
                            if (Storage::disk('local')->exists($sourceFile)) {
                                File::move($sourceFilePath, $destinationPath);
                            }
                        }

                        $this->unsetcomplaintFilesSession();
                    }
                }

                $jsonData['status'] = true;
                $jsonData['message'] = 'Complaint registered successfully.';
            } else {
                $jsonData['status'] = false;
                $jsonData['message'] = 'Failed to register the complaint. Please try again.';
            }

            return response()->json($jsonData);
        } catch (\Exception $e) {
            return $this->getCustomExceptionMessage($e);
        }
    }



    public function sendEmailToComplainant($complainantId = 0, $complaintId)
    {
        try {
            //Mail on complaint generation
            $complainantData = Complainant::select('*')->where(['id' => $complainantId])->first();

            $complaintObject = new Complaint;
            $complaintData = $complaintObject->getComplaintDataById($complaintId);

            $mail = Mail::send(
                'frontend.emails.complaintGenerated',
                [
                    'fullName' => $complainantData->full_name,
                    'title' => $complaintData->title,
                    'complaint_num' => $complaintData->complaint_num,
                    'levelOneCategory' => $complaintData->levelOneCategory->name,
                    'levelTwoCategory' => $complaintData->levelTwoCategory->name,
                    'levelThreeCategory' => $complaintData->levelThreeCategory->name,
                    'city' => $complaintData->city->name,
                    'district' => $complaintData->district->name,
                    'unionCouncil' => $complaintData->unionCouncil->name,
                    'newArea' => $complaintData->newArea->name,
                    'complaintStatus' => $complaintData->complaintStatus->name,
                    'app_url' => URL::to('/'),
                ],
                function ($message) use ($complainantData, $complaintData) {
                    $message->to(trim($complainantData->email));
                    $message->subject('Complaint Registered Successfully');
                }
            );

            return true;

        } catch (\Exception $e) {
            return false;
        }


    }

    public function sendEmailToNewCreatedComplainant($complainantData)
    {
        try {
            Mail::send(
                'frontend.emails.newCreatedComlainant',
                [
                    'fullName' => $complainantData['full_name'],
                    'email' => $complainantData['email'],
                    'password' => $complainantData['password'],
                    'app_url' => URL::to('/'),
                ],
                function ($message) use ($complainantData) {
                    $message->to($complainantData['email']);
                    $message->subject('New Complaint Account Created');
                }
            );

            return true;

        } catch (\Exception $e) {
            return false;
        }


    }


    public function sendEmailToIsNotifyCustomer($complainantData)
    {
        try {
            Mail::send(
                'frontend.emails.followupNotifyCustomer',
                [
                    'fullName' => $complainantData['full_name'],
                    'email' => $complainantData['email'],
                    'complainId' => $complainantData['complainId'],
                    'description' => $complainantData['description'],
                    'app_url' => URL::to('/'),
                ],
                function ($message) use ($complainantData) {
                    $message->to(trim($complainantData['email']));
                    $message->subject('Complaint Notification Added');
                }
            );

            return true;

        } catch (\Exception $e) {
            return false;
        }


    }

    public function sendEmailToAssignedUser($complainantId = 0, $complaintId, $assignTo)
    {
        try {
            //Mail on complaint generation
            $complainantData = Complainant::select('*')->where(['id' => $complainantId])->first();
            $userData = User::select('*')->where(['id' => $assignTo])->first();
            $complaintObject = new Complaint;
            $complaintData = $complaintObject->getComplaintDataById($complaintId);

            $mail = Mail::send(
                'frontend.emails.complaintGenerated',
                [
                    'fullName' => $complainantData->full_name,
                    'title' => $complaintData->title,
                    'complaint_num' => $complaintData->complaint_num,
                    'levelOneCategory' => $complaintData->levelOneCategory->name,
                    'levelTwoCategory' => $complaintData->levelTwoCategory->name,
                    'levelThreeCategory' => $complaintData->levelThreeCategory->name,
                    'city' => $complaintData->city->name,
                    'district' => $complaintData->district->name,
                    'unionCouncil' => $complaintData->unionCouncil->name,
                    'newArea' => $complaintData->newArea->name,
                    'complaintStatus' => $complaintData->complaintStatus->name,
                    'app_url' => URL::to('/'),
                ],
                function ($message) use ($complainantData, $complaintData, $userData) {
                    $message->to(trim($userData->email));
                    $message->subject('' . $complainantData->full_name . ' Has Created An Complaint To You');
                }
            );

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

}
