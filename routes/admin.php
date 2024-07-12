<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\PermissionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers','middleware' => ['auth.role.base']], function()
{
    #ajax-data
    Route::get('complaints/ajax-data/{className?}/{fieldName?}/{fieldId?}/{fieldName2?}/{fieldId2?}/{fieldName3?}/{fieldId3?}', 'AjaxController@getData')->name('get.data');
    Route::get('complaints/report-data/reports/get', 'AjaxController@getReport')->name('get.report');

    #new area grid
    Route::get('complaints/get-new-area-grid-data/{newAreaId?}', 'AjaxController@getNewAreaGridData')->name('get.new.area.grid.data');


    #File upload
    Route::post('/upload-compalint-files', 'Frontend\ManageFileController@uploadCompalintFiles')->name('upload.compalint.files');
    #remove File
    Route::delete('/remove-compalint-files/{file}', 'Frontend\ManageFileController@removeCompalintFiles')->name('remove.compalint.files');

});



Route::group(['namespace' => 'App\Http\Controllers\Backend','prefix' => config('constants.admin_url_prefix')], function()
{
    /**
     * Home Routes
     */

    Route::group(['middleware' => ['guest']], function() {

        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');

    });

    Route::group(['middleware' => ['auth', 'permission']], function() {
        /**
         * Logout Routes
         */
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
        Route::get('/', 'HomeController@index')->name('home.index');
        Route::post('/get-jobs-graph-data', 'HomeController@getComplaintsGraphData')->name('complaints.graph.data');
        Route::post('/get-count-data', 'HomeController@getCountData')->name('get.count.data');


        /**
         * User Routes
         */
        Route::group(['prefix' => 'users'], function() {
            Route::get('/', 'UsersController@index')->name('users.index');
            Route::get('/create', 'UsersController@create')->name('users.create');
            Route::post('/create', 'UsersController@store')->name('users.store');
            Route::get('/{user}/show', 'UsersController@show')->name('users.show');
            Route::get('/{user}/edit', 'UsersController@edit')->name('users.edit');
            Route::patch('/{user}/update', 'UsersController@update')->name('users.update');
            Route::delete('/{user}/delete', 'UsersController@destroy')->name('users.destroy');
        });

        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);


         //CMS Pages
         Route::group(['prefix' => 'cms'], function() {
            Route::get('/', 'CMSController@index')->name('cms.index');
            Route::get('/create', 'CMSController@create')->name('cms.create');
            Route::post('/create', 'CMSController@store')->name('cms.store');
            Route::get('/{cms}/show', 'CMSController@show')->name('cms.show');
            Route::get('/{cms}/edit', 'CMSController@edit')->name('cms.edit');
            Route::patch('/{cms}/update', 'CMSController@update')->name('cms.update');
            Route::delete('/{cms}/delete', 'CMSController@destroy')->name('cms.destroy');
        });


        // Complaints
        Route::group(['prefix' => 'complaints'], function() {
            Route::get('/', 'ComplaintController@index')->name('complaints.index');
            Route::delete('/{complaintId}/delete', 'ComplaintController@destroy')->name('complaints.destroy');
            Route::post('/{complaintId}/approve', 'ComplaintController@approve')->name('can.approve.complaints');
            Route::get('/{complaintId}/show', 'ComplaintController@show')->name('complaints.show');
            Route::post('/assign-form','ComplaintController@assignComplaintForm')->name('assign.complaint.form');
            Route::post('/assign','ComplaintController@assignComplaint')->name('assign.complaint');
            Route::get('/{complaintId}/show', 'ComplaintController@show')->name('complaints.show');
            Route::get('/track', 'ComplaintController@trackComplaint')->name('complaints.track');

            Route::get('/create', 'ComplaintController@create')->name('complaints.create.form');
            Route::post('/store', 'ComplaintController@store')->name('rolebase.complaints.store');

            Route::get('{complaint}/follow-up/', 'ComplaintController@followUp')->name('complaints.follow.up');
            Route::post('{complaint}/follow-up', 'ComplaintController@followUpSaved')->name('complaints.follow.up.saved');
            Route::delete('/{complaintFollowUp}/delete-follow-up', 'ComplaintController@followUpDestroy')->name('follow.up.Destroy');

            Route::post('/re-assign-form','ComplaintController@reAssignComplaintForm')->name('re-assign.complaint.form');
            Route::post('/re-assign','ComplaintController@reAssignComplaint')->name('re-assign.complaint');

            Route::post('/get-mna-details', 'ComplaintController@getMNADetails')->name('get.mna.details');
            Route::post('/get-mna-wise-mpa', 'ComplaintController@getMnaWiseMpa')->name('get.mna.wise.mpa');

            #ajax-data
            //Route::get('/ajax-data/{className?}/{fieldName?}/{fieldId?}', 'AjaxController@getData')->name('get.ajax.data');

        });

        //profile
        Route::group(['prefix' => 'profile'], function() {
            Route::get('/', 'ProfileController@index')->name('profile.index');
            Route::post('/update', 'ProfileController@update')->name('profile.update');
        });



            //Categories
        Route::group(['prefix' => 'categories'], function() {
            Route::get('/', 'CategoryController@index')->name('categories.index');
            Route::delete('/{categoryId}/delete', 'CategoryController@destroy')->name('categories.destroy');
            // Route::get('/{categoryId}/show', 'CategoryController@show')->name('categories.show');
            Route::get('/{categoryId}/edit-form', 'CategoryController@editForm')->name('categories.editForm');
            Route::post('/edit', 'CategoryController@edit')->name('categories.edit');
            Route::get('/add-category-form', 'CategoryController@addForm')->name('categories.addForm');
            Route::post('/add-category', 'CategoryController@createCategory')->name('categories.create');
        });


        #Reports
        Route::get('/report-assets', 'AssetsController@report_index')->name('report-assets');
        Route::get('/report-categories', 'CategoryController@report_index')->name('report-categories');
        Route::get('/report-tickets', 'ComplaintController@report_index')->name('report-tickets');
        Route::get('/report-complains', 'ComplaintController@report_index')->name('report-complains');
        Route::get('/report-by-complains', 'ComplaintController@reportByComplaints')->name('report-by-complaints');


        //Complainants
        Route::group(['prefix' => 'complainants'], function() {
            Route::get('/', 'ComplainantController@index')->name('complainants.index');
            Route::get('/{complainant}/show', 'ComplainantController@show')->name('complainants.show');
            Route::delete('/{complainant}/delete', 'ComplainantController@destroy')->name('complainants.destroy');
            Route::get('/{complainant}/edit', 'ComplainantController@edit')->name('complainants.edit');
            Route::patch('/{complainant}/update', 'ComplainantController@update')->name('complainants.update');
        });

        //Complaint Status
        Route::group(['prefix' => 'complaint-status'], function() {
            Route::get('/','ComplaintStatusController@index')->name('complaints.status.index');
            Route::delete('/{complaintStatus}/delete', 'ComplaintStatusController@destroy')->name('complaints.status.destroy');
            Route::get('/createForm', 'ComplaintStatusController@addComplaintStatusForm')->name('complaints.status.form');
            Route::post('/create', 'ComplaintStatusController@create')->name('complaints.status.create');
            Route::get('/{complaintStatus}/edit', 'ComplaintStatusController@edit')->name('complaints.status.edit');
            Route::post('/{complaintStatus}/update', 'ComplaintStatusController@update')->name('complaints.status.update');
        });

        //NewAreaGrid
        Route::group(['prefix' => 'new-grid-area'], function() {
            Route::get('/','NewAreaGridController@index')->name('area.grid.index');
            Route::delete('/{areaGrid}/delete', 'NewAreaGridController@destroy')->name('area.grid.destroy');
            // Route::get('/createForm', 'ComplaintStatusController@addComplaintStatusForm')->name('complaints.status.form');
            // Route::post('/create', 'ComplaintStatusController@create')->name('complaints.status.create');
            // Route::get('/{complaintStatus}/edit', 'ComplaintStatusController@edit')->name('complaints.status.edit');
            // Route::post('/{complaintStatus}/update', 'ComplaintStatusController@update')->name('complaints.status.update');
        });

        //NewArea
        Route::group(['prefix' => 'new-area'], function() {
            Route::get('/','NewAreaController@index')->name('new.area.index');
            Route::delete('/{newArea}/delete', 'NewAreaController@destroy')->name('new.area.destroy');
            Route::get('/createForm', 'NewAreaController@addAreaForm')->name('new.area.form');
            Route::post('/create', 'NewAreaController@create')->name('new.area.create');
            Route::get('/{newArea}/edit', 'NewAreaController@edit')->name('new.area.edit');
            Route::patch('/{newArea}/update', 'NewAreaController@update')->name('new.area.update');
        });

        //District
        Route::group(['prefix' => 'district'], function() {
            Route::get('/','DistrictController@index')->name('district.index');
            Route::delete('/{district}/delete', 'DistrictController@destroy')->name('district.destroy');
            Route::get('/createForm', 'DistrictController@addDistrictForm')->name('district.form');
            Route::post('/create', 'DistrictController@create')->name('district.create');
            Route::get('/{district}/edit', 'DistrictController@edit')->name('district.edit');
            Route::patch('/{district}/update', 'DistrictController@update')->name('district.update');
        });

        //SubDivisions
        Route::group(['prefix' => 'sub-division'], function() {
            Route::get('/','SubDivisionController@index')->name('sub.division.index');
            Route::delete('/{subDivision}/delete', 'SubDivisionController@destroy')->name('sub.division.destroy');
            Route::get('/createForm', 'SubDivisionController@addSubDivisionForm')->name('sub.division.form');
            Route::post('/create', 'SubDivisionController@create')->name('sub.division.create');
            Route::get('/{subDivision}/edit', 'SubDivisionController@edit')->name('sub.division.edit');
            Route::patch('/{subDivision}/update', 'SubDivisionController@update')->name('sub.division.update');
        });

        //UnionCouncils
        Route::group(['prefix' => 'union-council'], function() {
            Route::get('/','UnionCouncilsController@index')->name('union.council.index');
            Route::delete('/{unionCouncil}/delete', 'UnionCouncilsController@destroy')->name('union.council.destroy');
            Route::get('/createForm', 'UnionCouncilsController@addUnionCouncilForm')->name('union.council.form');
            Route::post('/create', 'UnionCouncilsController@create')->name('union.council.create');
            Route::get('/{unionCouncil}/edit', 'UnionCouncilsController@edit')->name('union.council.edit');
            Route::patch('/{unionCouncil}/update', 'UnionCouncilsController@update')->name('union.council.update');
        });

        //Charges
        Route::group(['prefix' => 'charge'], function() {
            Route::get('/','ChargeController@index')->name('charge.index');
            Route::delete('/{charge}/delete', 'ChargeController@destroy')->name('charge.destroy');
            Route::get('/createForm', 'ChargeController@addChargeForm')->name('charge.form');
            Route::post('/create', 'ChargeController@create')->name('charge.create');
            Route::get('/{charge}/edit', 'ChargeController@edit')->name('charge.edit');
            Route::patch('/{charge}/update', 'ChargeController@update')->name('charge.update');
        });

        //Ward
        Route::group(['prefix' => 'ward'], function() {
            Route::get('/','WardController@index')->name('ward.index');
            Route::delete('/{ward}/delete', 'WardController@destroy')->name('ward.destroy');
            Route::get('/createForm', 'WardController@addWardForm')->name('ward.form');
            Route::post('/create', 'WardController@create')->name('ward.create');
            Route::get('/{ward}/edit', 'WardController@edit')->name('ward.edit');
            Route::patch('/{ward}/update', 'WardController@update')->name('ward.update');
        });

        //PA
        Route::group(['prefix' => 'pa'], function() {
            Route::get('/','ProvincialAssemblyController@index')->name('pa.index');
            Route::delete('/{pa}/delete', 'ProvincialAssemblyController@destroy')->name('pa.destroy');
            Route::get('/createForm', 'ProvincialAssemblyController@addProvincialAssemblyForm')->name('pa.form');
            Route::post('/create', 'ProvincialAssemblyController@create')->name('pa.create');
            Route::get('/{pa}/edit', 'ProvincialAssemblyController@edit')->name('pa.edit');
            Route::patch('/{pa}/update', 'ProvincialAssemblyController@update')->name('pa.update');
        });

        //NA
        Route::group(['prefix' => 'na'], function() {
            Route::get('/','NationalAssemblyController@index')->name('na.index');
            Route::delete('/{na}/delete', 'NationalAssemblyController@destroy')->name('na.destroy');
            Route::get('/createForm', 'NationalAssemblyController@addNationalAssemblyForm')->name('na.form');
            Route::post('/create', 'NationalAssemblyController@create')->name('na.create');
            Route::get('/{na}/edit', 'NationalAssemblyController@edit')->name('na.edit');
            Route::patch('/{na}/update', 'NationalAssemblyController@update')->name('na.update');
        });

        //User Wise Area Mappings
        Route::group(['prefix' => 'user_wise_area_mapping'], function() {
            Route::get('/','UserWiseAreaMappingController@index')->name('area.mapping.index');
            Route::delete('/{area}/delete', 'UserWiseAreaMappingController@destroy')->name('area.mapping.destroy');
            Route::get('/createForm', 'UserWiseAreaMappingController@addUserWiseAreaMappingForm')->name('area.mapping.form');
            Route::post('/create', 'UserWiseAreaMappingController@create')->name('area.mapping.create');
            Route::get('/{area}/edit', 'UserWiseAreaMappingController@edit')->name('area.mapping.edit');
            Route::patch('/{area}/update', 'UserWiseAreaMappingController@update')->name('area.mapping.update');
        });

    });
});

