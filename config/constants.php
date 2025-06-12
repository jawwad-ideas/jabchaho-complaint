<?php
//Checkout Constants  markOrderPaid
return [
	'admin_url_prefix'=> '',//complaint/admin
	'complainant_landing_route' => 'complaints',
    'per_page'=>20,
	'report_per_page'=>50,
	'js_version'=>'1.5.1',
	'css_version'=>'1.0',
	'max_files' => 3,
	'complaint_number_starting_index' =>1000,
	'date_time_format'  => 'F d, Y \a\t h:i A',
    'files_supported'=>'png, jpg, jpeg, doc, docx, xls, xlsx, pdf',
	'complaint_type' =>[1=>'Damaged', 2=>'Incomplete Component', 3=>'Missing Article', 4=>'Stains', 5=>'Shrinkage issue', 6=>'Others'],
	'services' => [
		1 => "Dry Cleaning",
		2 => "Iron Only",
		3 => "Wash & Iron",
		4 => "Wash Only",
		5 => "Shoe Cleaning"
	],
	'boolean_options' => [1=>'Yes', 0 =>'No'],
	'gender_options' => [1=>'Male', 0 =>'Female',3 =>'Others'],
	'document_name'  => ['complaint'=>'Complaint'],
	'files' =>[
		'filetypes' 				=> 'assets/images/icons/filetypes',
		'complaint_documents' 		=> 'assets/uploads/complaint_documents/',
		'profile'					=> 'assets/uploads/profile/',
        'orders'                    => 'assets/uploads/orders/',
		'machines'                  => 'assets/uploads/machines/',
		'pricing'					=> 'assets/pricing'
    ],
	'file_extension_for_icon' =>['psd','doc','docx','xls','xlsx','zip','rar','ppt','pptx','pdf','mp3','mp4', 'webp', 'avif','jpg','jpeg','png'],
	'admin_user_default_action'=>[
		'logout.perform' => 'logout.perform',
		//'home.index'     => 'home.index'
	],
	'roles'=>['admin'=>'admin','complaint_management_team' => 'complaint management team'],

	'mobile_code' =>[
		'0300', '0301', '0302', '0303', '0304', '0305', '0306', '0307', '0308', '0309', '0310', '0311', '0312', '0313', '0314', '0315', '0316', '0317', '0318', '0320',
		'0321', '0322', '0323', '0324', '0325', '0331', '0332', '0333', '0334', '0335', '0336', '0337', '0340', '0341', '0342', '0343', '0344', '0345', '0346', '0347',
		'0348', '0349', '0355', '0364', '0365', '0581', '03000', '03555'
	],

	'provinces' =>[
		 1	=> 'Sindh',
		 2 	=> 'Punjab',
		 3  => 'Khyber Pakhtunkhwa',
		 4  => 'Balouchistan',
		 5  => 'Azad Kashmir',
		 6  => 'Gilgit Baltistan'
	],

	'countries' => [
		'PK' => 'Pakistan',
	],
	'complaint_tracking_status' => ['initiated' => 'Initiated', 'in_progress' =>'In Progress', 'completed'=>'Completed'],
	'complaint_sms_api_enable' => [1=>'Enable', 0 =>'Disable'],
	'complaint_status_notify_type'  =>[0=>'Disabled',1=>'SMS',2=>'Email', 3=>'Both'],
	'complaint_status_notify_type_id'  =>['disabled'=>0,'sms'=>1 ,'email'=>2, 'both'=>3],

	'complaint_reported_from'  		=>[1=>'Frontend',2=>'Backend'],
	'complaint_reported_from_id'  	=>['website'=>1 ,'complaint_portal'=>2],

	'admin_action_with_description' =>[
			'logout.perform'				 			=> 'Logout',
			'home.index'				     			=> 'Dashboard',
			'users.index'					 			=> 'Users List',
			'users.create'					 			=> 'Add User Form',
			'users.store'					 			=> 'Add User Form Submit',
			'users.show'					 			=> 'Show User',
			'users.edit'					 			=> 'Update User',
			'users.update'					 			=> 'Update User Form Submit',
			'users.destroy'					 			=> 'Delete User',
			'cms.index'									=> 'CMS Pages List',
			'cms.create'								=> 'Add CMS Page Form',
			'cms.store'									=> 'Add CMS Page Form Submit',
			'cms.show'									=> 'Show CMS Page',
			'cms.edit'									=> 'Update CMS Page Form',
			'cms.update'								=> 'Update CMS Page Form Submit',
			'cms.destroy'								=> 'Delete CMS Page',
			'roles.index'								=> 'Roles List',
			'roles.create'								=> 'Add Role Form',
			'roles.store'								=> 'Add Role Form Submit',
			'roles.show'								=> 'Show Role',
			'roles.edit'								=> 'Update Role Form',
			'roles.update'								=> 'Update Role Form Submit',
			'roles.destroy'								=> 'Delete Role',
			'permissions.index'							=> 'Permissions List',
			'permissions.create'						=> 'Add Permission Form',
			'permissions.store'							=> 'Add Permission Form Submit',
			'permissions.show'							=> 'Show Permission',
			'permissions.edit'							=> 'Update Permission Form',
			'permissions.update'						=> 'Update Permission Form Submit',
			'permissions.destroy'						=> 'Delete Permission',
	],

	'complaint_status_id' =>
	[
		'registered'		=> 1,
		'in_process'		=> 2,
		'pending'			=> 3,
		'resolved'			=> 4,
		'closed'			=> 5,
	],
	'content_type'=>['formUrlencoded'=>'formUrlencoded','json'=>'json','xml' => 'xml'],
	'http_methods'=>[
		'get'  		=> 'GET',
		'post'		=> 'POST',
		'put'  		=> 'PUT',
		'delete'	=> 'DELETE',
		'patch'		=> 'PATCH'
	],
	'review_statues' => [1=>'Pending', 2=>'Approved', 3=>'Not Approved'],
	'review_statues_code' => ['pending'=>1, 'approved'=>2, 'not_approved'=>3],
	'laundry_location_type' =>
	[
		 'store'		=> 'Store',
		 'facility' 	=> 'Facility',
	],
	'issues' => [
		1 => 'color fading',
		2 => 'iron shine',
		3 => 'burn',
		4 => 'shrinkage',
		5 => 'tears and torn',
		6 => 'holes',
		7 => 'missed button',
		8 => 'stitching',
		9 => 'embroidery',
		10 => 'missed label/logo',
		11 => 'lint',
		12 => 'rexine',
		13 => 'sole damaged', // missed in given array
		14 => 'snagging',
		15 => 'rust',
		16 => 'food stains',
		17 => 'ink stains',
		18 => 'paint stains', // missed in given array
		19 => 'oil stains',
		20 => 'hard stains',
		21 => 'color stains',
		22 => 'beverage stains',
		23 => 'already repaired',
		24 => 'fusing damage',
		25 => 'zip damage',
		26 => 'stone missed'
	],
	'dryer_statues' 	=> [1=>'Pending', 2=>'Completed'],
	'dryer_statues_id'  => ['pending'=>1, 'completed'=>2],
	'jabchaho_service' =>
	[
		'regular'	=> 'regular',
		'express' 	=> 'express',
	],
	'email_settings' =>
	[
		'from_title'	=> 'Jab Chaho Support',
		'from' 			=> 'support@jabchaho.com',
		'bcc'   	    => 'irfan.ullah@jabchaho.com'
	],
	'hours' =>
	[
		0 => '12:00 AM',
		1 => '01:00 AM',
		2 => '02:00 AM',
		3 => '03:00 AM',
		4 => '04:00 AM',
		5 => '05:00 AM',
		6 => '06:00 AM',
		7 => '07:00 AM',
		8 => '08:00 AM',
		9 => '09:00 AM',
		10 => '10:00 AM',
		11 => '11:00 AM',
		12 => '12:00 PM',
		13 => '01:00 PM',
		14 => '02:00 PM',
		15 => '03:00 PM',
		16 => '04:00 PM',
		17 => '05:00 PM',
		18 => '06:00 PM',
		19 => '07:00 PM',
		20 => '08:00 PM',
		21 => '09:00 PM',
		22 => '10:00 PM',
		23 => '11:00 PM'
	],

	'order_type' =>[
			'before_whatsapp' => 'Whatsapp Hold Order Before Wash',
			'after_whatsapp' => 'Whatsapp Hold Order After Wash',
	]


];
