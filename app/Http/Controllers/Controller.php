<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    #excetion response
    public function getCustomExceptionMessage($exception='')
    {
        
        \Log::info($exception);
        //return '******Whoops, looks like something went wrong*****';
        return view('errors.500');
    }


    #Remove image
    public function removeFile($fileNameWithPath='')
    {
        try{

            if(\File::exists($fileNameWithPath)){
                \File::delete($fileNameWithPath);
            }

            return true;

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
            
        }	
    }

    #Remove image
    public function removeTempFile($fileNameWithPath='')
    {
        try{

			if (Storage::disk('local')->exists($fileNameWithPath)) 
            {
                $deleted = Storage::disk('local')->delete($fileNameWithPath);
            }

            return true;

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
            
        }	
    }


    //Function for queue worker start and termination
    protected function queueWorker()
    { 
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
        {
            $artisanPath = str_replace('\\', '/', realpath(base_path('artisan')));
            $command = "start /B php ".$artisanPath." queue:work --stop-when-empty";
            pclose(popen($command, "r"));
        } 
        else 
        {
            exec('php ' . base_path('artisan') . ' queue:work --stop-when-empty --daemon --timeout=60 > /dev/null 2>&1 &');
        }
        
    }
}
