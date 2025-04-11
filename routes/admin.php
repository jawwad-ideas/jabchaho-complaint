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


Route::get('/assets/uploads/orders/{orderNo}/{folder}/{imageName}', 'App\Http\Controllers\Backend\OrderController@showImage')->name('order.showImage');
Route::get('/download-images/{orderId}/{folderName}/{token}', 'App\Http\Controllers\Backend\OrderController@downloadImages')->name('download.images');

Route::group(['namespace' => 'App\Http\Controllers\Backend','prefix' => config('constants.admin_url_prefix')], function()
{
    /**
     * Home Routes
     */

    Route::group(['middleware' => ['guest']], function() {

        /**
         * Login Routes
         */
        Route::get('/', 'LoginController@show')->name('login.form');
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

        Route::get('dashboard/', 'HomeController@jabchahoDashboard')->name('jabchaho-dashboard.index');
        Route::post('/get-jabchaho-dashboardcount-data', 'HomeController@getJabchahoDashboardCountData')->name('get.jabchaho.dashboard.count.data');


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

        });

        //profile
        Route::group(['prefix' => 'profile'], function() {
            Route::get('/', 'ProfileController@index')->name('profile.index');
            Route::post('/update', 'ProfileController@update')->name('profile.update');
        });


        //report
        Route::get('/report-by-user', 'ReportController@getReportByUser')->name('report.by.user');
            //Categories
        // Route::group(['prefix' => 'categories'], function() {
        //     Route::get('/', 'CategoryController@index')->name('categories.index');
        //     Route::delete('/{categoryId}/delete', 'CategoryController@destroy')->name('categories.destroy');
        //     Route::get('/{categoryId}/edit-form', 'CategoryController@editForm')->name('categories.editForm');
        //     Route::post('/edit', 'CategoryController@edit')->name('categories.edit');
        //     Route::get('/add-category-form', 'CategoryController@addForm')->name('categories.addForm');
        //     Route::post('/add-category', 'CategoryController@createCategory')->name('categories.create');
        // });


        #Reports
        // Route::get('/report-assets', 'AssetsController@report_index')->name('report-assets');
        // Route::get('/report-categories', 'CategoryController@report_index')->name('report-categories');
        // Route::get('/report-tickets', 'ComplaintController@report_index')->name('report-tickets');
        // Route::get('/report-complains', 'ComplaintController@report_index')->name('report-complains');
        // Route::get('/report-by-complains', 'ComplaintController@reportByComplaints')->name('report-by-complaints');


        //Complaint Status
        Route::group(['prefix' => 'complaint-status'], function() {
            Route::get('/','ComplaintStatusController@index')->name('complaints.status.index');
            Route::delete('/{complaintStatus}/delete', 'ComplaintStatusController@destroy')->name('complaints.status.destroy');
            Route::get('/createForm', 'ComplaintStatusController@addComplaintStatusForm')->name('complaints.status.form');
            Route::post('/create', 'ComplaintStatusController@create')->name('complaints.status.create');
            Route::get('/{complaintStatus}/edit', 'ComplaintStatusController@edit')->name('complaints.status.edit');
            Route::post('/{complaintStatus}/update', 'ComplaintStatusController@update')->name('complaints.status.update');
        });


        #Configurations
        Route::get('/configurations','ConfigurationController@form')->name('configurations.form');
        Route::post('/configuration','ConfigurationController@save')->name('configurations.save');


        #Reviews
        Route::get('/reviews','ReviewController@index')->name('reviews');
        Route::get('/{review}/edit', 'ReviewController@edit')->name('reviews.edit');
        Route::patch('/{review}/update', 'ReviewController@update')->name('reviews.update');
        Route::delete('/{review}/delete', 'ReviewController@destroy')->name('reviews.destroy');

        #Orders
        Route::group(['prefix' => 'orders'], function() {
            Route::post('/save', 'OrderController@save')->name('orders.save');
            Route::get('/index/{status?}', 'OrderController@index')->name('orders.index');
            Route::get('/{order_id}/edit', 'OrderController@edit')->name('orders.edit');
            Route::post('/delete', 'OrderController@delete')->name('orders.delete');
            Route::post('/complete-order', 'OrderController@completeOrder')->name('orders.complete');
            Route::post('/sync-order', 'OrderController@syncOrder')->name('orders.sync');
            Route::post('/send-email', 'OrderController@sendEmail')->name('send.email');
            Route::get('/barcode-image', 'OrderController@itemImage')->name('orders.barcode.images');
            //Barcode Image Upload
            Route::get('/barcode-image-upload', 'OrderController@barcodeImageUpload')->name('barcode.image.upload');
        });

        Route::post('/upload-order-image', 'OrderController@uploadOrderImage')->name('upload.order.image');
        Route::post('/upload-order-image-whithoutbase64', 'OrderController@uploadOrderImageWithoutBase64')->name('upload.order.image.whithoutbase64');

        Route::post('/save-item-issue', 'OrderController@saveItemIssue')->name('save.item.issue');
        Route::post('/remove-item-issue', 'OrderController@removeItemIssue')->name('remove.item.issue');
        //is.item.issue.fixed
        Route::post('/is-item-issue-fixed', 'OrderController@isItemIssueFixed')->name('is.item.issue.fixed');


        Route::group(['prefix' => 'machine-details'], function() {
            Route::get('/', 'MachineController@index')->name('machine.details');
            Route::get('/create', 'MachineController@create')->name('machine.detail.create');
            Route::post('/save', 'MachineController@save')->name('machine.detail.save');
            Route::get('/machine-list', 'MachineController@machineIndex')->name('machine.list');
            Route::get('/{machineDetailId}/show', 'MachineController@show')->name('machine.detail.show');
            Route::get('/machine-add', 'MachineController@machineAdd')->name('machine.add');
            Route::post('/machine-save', 'MachineController@machineSave')->name('machine.save');
            Route::get('/{machine_id}/machine-view', 'MachineController@machineView')->name('machine.view');
        });

        Route::get('scanner', 'OrderController@uploadView')->name('scanner');

        Route::group(['prefix' => 'sunny-dryer'], function() {
            Route::get('/create', 'DryerController@create')->name('sunny.dryer.create');
            Route::post('/save', 'DryerController@save')->name('sunny.dryer.save');
            Route::get('/marked-complete', 'DryerController@markedCompleteForm')->name('sunny.dryer.marked.complete.form');
            Route::post('/marked-complete', 'DryerController@markedComplete')->name('sunny.dryer.marked.complete');
            #Route::get('/{dryer}/edit', 'DryerController@edit')->name('sunny.dryer.edit');
            #Route::patch('/{dryer}/update', 'DryerController@update')->name('sunny.dryer.update');
            Route::get('/{status?}', 'DryerController@index')->name('sunny.dryer');

       
        });
        
        Route::group(['prefix' => 'pricing'], function() {
            Route::get('/', 'PricingController@index')->name('pricing');
            Route::get('/sync', 'PricingController@syncPricing')->name('pricing.sync');
        });
    });

    


    Route::post('/send-whatsapp', 'OrderController@sendWhatsApp')->name('send.whatsapp');
   

});



