<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cms;

class CMSController extends Controller
{
    public function listOfPages()
    {
        $cmsObject = new Cms();
        $cmsPages  = $cmsObject->getPages();
    }


    public function index($pageUrl='')
    {
        if(empty($pageUrl))
        {
            $pageUrl = 'home';
        }
        
        $cmsObject = new Cms();
        $cmsDetail  = $cmsObject->getCmsDetail($pageUrl);
        
        if(!empty($cmsDetail))
        {
            $data['cmsDetail'] = $cmsDetail;
            return view('frontend.cms.index')->with($data);
        }else{
            abort(404);
        }
        
    }
}
