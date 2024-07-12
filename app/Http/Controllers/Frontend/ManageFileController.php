<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requests\Frontend\UploadCompalintFilesRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Session;

class ManageFileController extends Controller
{
        /**
     * upload Compalint Files 
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadCompalintFiles(UploadCompalintFilesRequest $request){
      
        try
		{
            $complaintFiles = array();
            $savedFilePath  = array();
            $allFilesData   = array();

            $validateValues = $request->validated();
           
            if (!empty($validateValues))
            {
                $loggedInId  =0;
                if(!empty(Auth::guard('complainant')->user()->id))
                {
                    $loggedInId                      = "c-".Auth::guard('complainant')->user()->id;
                }
                else
                {
                    $loggedInId                      = "u-".Auth::guard('web')->user()->id;
                }
                
                $files                              = Arr::get($validateValues,'attachment');
                $documentName                       = Arr::get($validateValues,'selected');

                $uploadFolderPath       = config('constants.files.temp'); //CHANGE
                $filetypesIconPath      = config('constants.files.filetypes');
                $fileExtensionForIcon   = config('constants.file_extension_for_icon');

                #get value from session
                if(\Session::get('complaintFiles'))
                    $complaintFiles = \Session::get('complaintFiles');

                // if (count($complaintFiles) + count($files) > config('constants.max_files')) 
                // {
                //     $errors[0]  = "More than ".config('constants.max_files')." files cannot be uploaded";
                //     return response()->json(['errors'=>$errors]);
                // }
                // else
                // {
                    if(!empty($files))
                    {
                        $counter =count($complaintFiles)+1;
                        foreach ($files as $file) 
                        {
                            $savedFilePath = array();
                            $filename = $file->getClientOriginalName(); // Get original filename
                            $fileExtension = strtolower($file->guessExtension()?$file->guessExtension():$file->getClientOriginalExtension());
                            $uniqueName = uniqid().'-'.time().'-'.$counter.'-'.$loggedInId;
                            $tempFilename = $uniqueName. '.' . $fileExtension; // Generate unique name
                            $file->storeAs('temp', $tempFilename); // Store in temp disk

                            //Store files name in array
                            
                            $savedFilePath['id']          = $uniqueName;
                            $savedFilePath['name']        = $filename;
                            $savedFilePath['temp_name']   = $tempFilename;
                            $savedFilePath['file_path']   = asset($uploadFolderPath.$tempFilename);
                            if(in_array($fileExtension,$fileExtensionForIcon))
                            {
                                $savedFilePath['diaplay_image_path'] = asset($filetypesIconPath).'/'.$fileExtension.'.png';

                            }else{
                                
                                $savedFilePath['diaplay_image_path'] = asset($filetypesIconPath).'/file.png';
                            }
                        
                            $complaintFiles[$tempFilename] =array('file'=>$tempFilename,'original_file'=>$filename);

                            $allFilesData[] = $savedFilePath;

                            $counter++;
                        
                        }

                        //Store files name in session
                        \Session::put('complaintFiles', $complaintFiles);
                    }
              
                    return response()->json($allFilesData);
                //}
                
            }else{
                return response()->json($allFilesData);
            }

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
            
        }
    }   
    
    public function removeCompalintFiles(Request $request)
    {
        $fileToRemove = $request->segment(2);

        if(!empty($fileToRemove))
        {
            $uploadFolderPath       = config('constants.files.temp');
            
            //unset from session
            if(!empty(\Session::get('complaintFiles')))
            {
                $complaintFiles = \Session::get('complaintFiles');   
                # get file index    
                
                if(array_key_exists($fileToRemove,$complaintFiles)){
                    // Unset the file name at the found index
                    unset($complaintFiles[$fileToRemove]);
                    
                    // Update the session with the modified array
                    session(['complaintFiles' => $complaintFiles]);
                }
            }

            //Remove file 
            $fileNameWithPath = "temp/".$fileToRemove;
            $this->removeTempFile($fileNameWithPath);

        }

        return true;
    }
}
