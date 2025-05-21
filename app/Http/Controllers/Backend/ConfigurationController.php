<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Configuration\ConfigurationTrait;
use App\Models\Configuration; 
use App\Models\ComplaintStatus;

class ConfigurationController extends Controller
{
    use ConfigurationTrait;
    public function form(Request $request)
    {
        $data = array();

        $objectComplaintStatus                  = new ComplaintStatus;
        //get configurations
        $configurations                         = $this->getConfigurations();
        $data['enableDisableSmsApi']            = config('constants.complaint_sms_api_enable');
        $data['configurations']                 = $configurations;
        $data['complaintStatuses']              = $objectComplaintStatus->getComplaintStatuses();//
        $data['complaintStatusNotifyType']      = config('constants.complaint_status_notify_type');
        $data['booleanOptions']                 = config('constants.boolean_options'); 
        $data['hours']                         = config('constants.hours'); 

        return view('backend.configurations.form')->with($data);
    }

    public function save(Request $request)
    {
        $input = $request->input();

        if(!empty($input))
        {
            unset($input['_token']);
            unset($input['submit']);

            foreach($input as $name=>$value)
            {
                if (is_array($value)) 
                {
                    $value = implode(',',$value);
                }
                
                $updateConfiguration = Configuration::updateOrCreate(['name' => $name], [ 
                    'value' => $value
                ]);
            }

            #cache clear
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');

        }

        return redirect(route('configurations.form'))->with('success', "Saved Changes Successfully.");

    }
}
