<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Illuminate\Support\Arr;
use GuzzleHttp\Client as GClient;

class Helper
{
    public static function shout(string $string)
    {
        return strtoupper($string);
    }
	
	public static function debug($array=array())
    {
       echo "<pre>";print_r($array);echo "<pre>"; exit;
    }

    	//generate alpha numeric code
	static function generateAlphaNumeric($limit = 0)
	{

		$num = '';
		for ($i = 0; $i < $limit; $i++) {
			$d = rand(1, 30) % 2;
			$num .= $d ? chr(rand(48, 57)) : chr(rand(97, 122));
		}
		return $num;

	}

	static function generateRandomCapitalAlphabets($limit = 0)
	{

		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $limit; $i++) {
            $randstring.= $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;

	}

	#check file extension is exist in file_extension_for_icon
	static function isFileExtensionForIcon($fileName=''){
		$fileExtensionForIcon = config('constants.file_extension_for_icon');
		$explodedFileName = explode('.', $fileName);
		$fileExtension = end($explodedFileName);
		if(in_array($fileExtension,$fileExtensionForIcon))
        {
			return $fileExtension.'.png';
		}	
		
		return false;
	}

	
   //Replace underscore with space and upper case first character in array
   public static function replaceUnderscoreWithSpaceAndUpperCaseFirstCharacter($str) 
   {
	return ucwords(str_replace("_", " ", $str));
   }

    public static function sluggify($str, $limit = null)
	{
		$str = strtolower($str);
		$str = strip_tags($str);
		$str = stripslashes($str);

		if ($limit) {
			$str = mb_substr($str, 0, $limit, "utf-8");
		}
		$text = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		// trim
		$text = trim($text, '-');
		return $text;
	}

	    #check number is zero then return one else return number
	public static function retunOneIfzero($number='')
	{
	
		if($number==0 && !is_null($number))
		{
			$number = 1;
		}
		return $number;
	}

	public static function replaceHomeContent($homeContent=array(),$userData=array())
	{
		$pattern =  config('constants.home_page_content_pattern');
		$replace = array(
							ucwords( Arr::get($userData, 'full_name')  )
						);
		return $view = str_replace($pattern, $replace, Arr::get($homeContent, 'content'));
	}

	public static function time_elapsed_string($datetime, $full = false) {
		$now = new \DateTime;
		$ago = new \DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	#get data as array w.r.t month number if there is no data then 0 
    public static function monthDataMergeWithDefaultMonthArray($param=array())
    {
        $result = array();
        for($i=1; $i<=12; $i++)
        {
            if(!empty($param) && !empty($param[$i]))
            {
                $result[] = $param[$i];
            }
            else
            {
                $result[] = 0;
            }
           
        }

        return $result;
    }
	public static function defaultRemainingDocumentName($defaultDocumentArray=array())
	{
		$defaultDocuments = config('constants.default_document');

		if(!empty($defaultDocuments))
		{
			foreach ($defaultDocumentArray as $item) 
			{
				if (($key = array_search($item['document_name'], $defaultDocuments)) !== false) {
					unset($defaultDocuments[$key]);
				}
			}
		}
		return $defaultDocuments;
	}


	public static function getDateByFilterValue($filterValue='')
    {
        $dateArray = array();

        $startDate =  date("Y-m-d 00:00:00");
        $endDate = date("Y-m-d 23:59:59");

        if($filterValue == 'day')
        {
            $startDate =  date("Y-m-d 00:00:00");
        }
        else if($filterValue == 'week')
        {
            $startDate =  date("Y-m-d 00:00:00", strtotime('-7 days'));
        }
        else if($filterValue == 'month')
        {
            $startDate =  date("Y-m-d 00:00:00", strtotime('-1 month'));
        }
        else if($filterValue == '3-months')
        {
            $startDate =  date("Y-m-d 00:00:00", strtotime('-3 month'));;
        }

        $dateArray['startDate']    = $startDate;
        $dateArray['endDate']      = $endDate;
        return $dateArray;
    }

	public static function select2Fields($data=null,$errors=null,$params=array())
	{
		$fieldName = Arr::get($params,'name');
		$fieldLabel  = Helper::replaceUnderscoreWithSpaceAndUpperCaseFirstCharacter($fieldName);

		$html = 
		'    <div class="row mb-3">
				<label for="'.$fieldName.'" class="col-sm-3 col-form-label col-form-label-sm text-left">'.$fieldLabel.'<span style="color: red"> * </span></label>
				<div class="col-sm-9 text-left">
					<select class="form-control form-control '.$fieldName.'" id="'.$fieldName.'" name="'.$fieldName.'">';
					
					$select2FieldName ='';	
					if(!empty($data) && empty(old('_token')))
							{
								$select2FieldName = Arr::get($data,$fieldName);
								
							}
							
							else if(!empty(old($fieldName)))
							{
								$select2FieldName = old($fieldName);
							}

							$html.= '<option value="'.$select2FieldName.'" selected="selected">'.$select2FieldName.'</option>';
							
					$html.= '</select>';
					if($errors->has($fieldName))
					{
						$html.= '<div class="text-danger">'.$errors->first($fieldName).'</div>';
					}
			$html.='</div>
    		</div>';

		return $html;
	}

	#sendRequestToGateway
	public static function sendRequestToGateway($params=array())
	{
		$response = $returnRespone = array();

		$apiUrl	 		 = Arr::get($params, 'apiUrl');
		$headerParams	 = Arr::get($params, 'headerParams');	
		$input		 	 = Arr::get($params, 'input'); //input fields 
		$httpMethod	 	 = Arr::get($params, 'httpMethod');
		$apiType	 	 = Arr::get($params, 'apiType');
		$sslVerify		 = env('SSL_VERIFY');

		//Guzzle Object Instance
		$client = new GClient(); 

		//For Post Method
		if($httpMethod == config('constants.http_methods.post'))
		{
			if($apiType==config('constants.content_type.formUrlencoded'))
			{
				$response = $client->post($apiUrl, [
					'headers' 		=> $headerParams,
					'form_params' 	=> $input,
					'verify' 		=> $sslVerify,
					'timeout' 		=> 120
				]);
			}	
			else if($apiType==config('constants.content_type.json'))
			{
				$response = $client->post($apiUrl, [ 
					'headers' 	=> $headerParams,
					'verify'	=> $sslVerify,
					'timeout' 	=> 120,
					'body' 		=> json_encode($input) 
				]);
			}
		}
		
		//For Get Method
		if($httpMethod == config('constants.http_methods.get'))
		{
			//get response
			$response = $client->get($apiUrl,['verify' => $sslVerify,'timeout' => 120,'query'=>$input]);
		}
			
		//Respond is 200 , All ok .
		if(!empty($response))
		{
			if(!empty($response->getStatusCode()) && $response->getStatusCode() == 200) 
			{
				if(!empty($response->getBody()) && $apiType==config('constants.content_type.json')){
					$returnRespone = json_decode($response->getBody(),true);
				}
				else if(!empty($response->getBody()) && $apiType==config('constants.content_type.xml'))
				{
					//XML response parsing here
					$body = $response->getBody();
					$xmlContent = $body->getContents();

					// Parse the XML response
					$xmlObject = simplexml_load_string($xmlContent);
					if($xmlObject)
					{
						// Convert the SimpleXMLElement object to JSON
						$jsonContent = json_encode($xmlObject);
						// Convert the JSON to an associative array
						$returnRespone = json_decode($jsonContent, true);
					}					
					
				}
			}
		}
		
		return $returnRespone; 

	}

	public static function addDotAfterWords($limit=0,$text) {
		// Split the text into an array of words
		$words = str_word_count($text, 1);

		// Select the first 10 words
		$limitedWords = array_slice($words, 0, $limit);
	
		// Join the words back into a string
		$result = implode(' ', $limitedWords);

		if(count($words) > $limit )
		{
			$result = $result.'....';
		}
	
		return $result;
	}

	public static function customErrorMessage()
    {
         $responsearray = array();
         $responsearray['status'] 	= false;
         $responsearray['message']	= 'Something Went Wrong Try Again';  
         return response()->json($responsearray, 401);
    }
 
    public static function returnError($validator=null)
    {
         if ($validator->fails()) 
         {
             $errorMessages = array();
             $errors = $validator->errors();
             
             if(!empty($errors))
             {
                 foreach ($errors->all() as $message) {
                     $errorMessages[] = $message;
                 }
             }
 
             return $errorMessages;
         }
         else
         {
             return false;
         }
    }

	#excetion response
	public static function getCustomExceptionMessage($exception='')
	{
		\Log::info($exception);
	}

	#Remove image
	public static function removeFile($fileNameWithPath='')
	{

		try{

			if(\File::exists($fileNameWithPath)){
				\File::delete($fileNameWithPath);
			}

			return true;

		}catch(\Exception $e)
		{
			return Helper::getCustomExceptionMessage($e);
			
		}	
	}

	#replace sms template
	public static function replaceSmsTemplate($template, $data) 
	{
		if(!empty($data))
		{
			foreach ($data as $key => $value) {
				// Replace placeholder with value
				$template = str_replace('{{$' . $key . '}}', $value, $template);
			}
		}
		
		return $template;
	}

	public static function formatPhoneNumber($phoneNumber) {
		// Remove any non-digit characters from the phone number
		$phoneNumber = preg_replace('/\D/', '', $phoneNumber);
		
		// Check if the phone number starts with '0'
		if (strpos($phoneNumber, '0') === 0) {
			// Replace the starting '0' with '92'
			$phoneNumber = '92' . substr($phoneNumber, 1);
		} 
		else if (strpos($phoneNumber, '92') === 0) {

			$phoneNumber = $phoneNumber;
		}
		else {
			// If it does not start with '0', just prepend '92'
			$phoneNumber = '92' . $phoneNumber;
		}
		
		return $phoneNumber;
	}

	public static function getdevice($request=null)
	{
		$userAgent = $request->header('User-Agent');

		if (stripos($userAgent, 'Postman') !== false) {
            $deviceType = 'Postman';
        } elseif (stripos($userAgent, 'Insomnia') !== false) {
            $deviceType = 'Insomnia';
        } elseif (stripos($userAgent, 'curl') !== false) {
            $deviceType = 'cURL';
        } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
            $deviceType = 'iOS';
        } elseif (stripos($userAgent, 'Android') !== false) {
            $deviceType = 'Android';
        } else {
            $deviceType = 'Web';
        }

		return $deviceType;

	}

	/**
     * Handle response after user authenticated
     * 
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */

	public static function authenticated($user=null) 
    {
        $redirectUrl = 'home.index';
        $permissionIndexPagesArray = array();
        
        //get all users permission
        if(!empty($user))
		{
			if(!empty($user->getAllPermissions()))
			{
				//check .index and list of .index
				foreach($user->getAllPermissions() as $row)
				{
					if(str_contains($row->name, '.index'))
					{
						$permissionIndexPagesArray[] = $row->name;
					}
				}
			}
		}

        //get list of .index permisson
        if(!empty($permissionIndexPagesArray))
        {
            asort($permissionIndexPagesArray);
            //check if cms.index exist then url move to cms.index
            if(in_array('home.index',$permissionIndexPagesArray))
            {
                $redirectUrl = 'home.index';
            }
            elseif(reset($permissionIndexPagesArray)) //first .index url
            {
                $redirectUrl = reset($permissionIndexPagesArray);
            }
        }

        return redirect(route($redirectUrl));
    }

	public static function transformString($str) {
		// Convert the string to lowercase and replace spaces with underscores
		return str_replace(' ', '_', strtolower($str));
	}
	
	
}

