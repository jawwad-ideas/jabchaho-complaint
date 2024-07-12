<?php
//Checkout Constants  markOrderPaid
return [
	'admin_url_prefix'=> 'complaint/admin',
	'complainant_landing_route' => 'complaints',
    'per_page'=>20,
	'report_per_page'=>50,
	'js_version'=>'1.0',
	'css_version'=>'1.0',
	'max_files' => 3,
    // 'files_supported'=>'psd, doc, docx, xls, xlsx, zip, rar, ppt, pptx, pdf, mp3, mp4, webp, avif',
    'files_supported'=>'png, jpg, jpeg, doc, docx, xls, xlsx, pdf',

	'boolean_options' => [1=>'Yes', 0 =>'No'],
	'gender_options' => [1=>'Male', 0 =>'Female',3 =>'Others'],
	'files' =>[
		'filetypes' 				=> 'assets/images/icons/filetypes',
		'temp'						=> 'storage/app/temp/',
		'complaint_documents' 		=> 'assets/uploads/complaint_documents/',
		'profile'					=> 'assets/uploads/profile/',
    ],
	'file_extension_for_icon' =>['psd','doc','docx','xls','xlsx','zip','rar','ppt','pptx','pdf','mp3','mp4', 'webp', 'avif','jpg','jpeg','png'],
	'admin_user_default_action'=>[
		'logout.perform' => 'logout.perform',
		//'home.index'     => 'home.index'
	],

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
];
