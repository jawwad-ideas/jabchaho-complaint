<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     //return view('welcome');
// });

Route::group(['namespace' => 'App\Http\Controllers\Frontend\Auth', 'middleware' => ['complainant.guest:complainant']], function () {
    #signup
    Route::get('/register', 'SignupController@signupForm')->name('signup.show');
    Route::post('/register', 'SignupController@signup')->name('signup.perform');

    #signin
    //Route::get('/', 'SigninController@signinForm')->name('signin.show.form');
    Route::get('/login', 'SigninController@signinForm')->name('signin.show');
    Route::post('/login', 'SigninController@signin')->name('signin.perform');

    #Forgot Password
    Route::get('/forgot-password', 'ForgotPasswordController@forgotPasswordForm')->name('forgot.password.show');
    Route::post('/forgot-password', 'ForgotPasswordController@forgotPassword')->name('forgot.password.perform');

    #Reset Password
    Route::get('reset-password/{token}', 'ResetPasswordController@resetPasswordForm')->name('reset.password.show');
    Route::post('reset-password', 'ResetPasswordController@resetPassword')->name('reset.password.perform');

});

Route::group(['namespace' => 'App\Http\Controllers\Frontend', 'middleware' => ['complainant.auth:complainant']], function () {
    #complaints
    Route::group(['prefix' => 'complaints'], function () {
        Route::get('/', 'ComplaintController@index')->name('complaints');
        Route::get('/create', 'ComplaintController@create')->name('complaints.create');
        Route::post('/create', 'ComplaintController@store')->name('complaints.store');
        Route::get('/{complaintId}/show', 'ComplaintController@show')->name('my.complaints.show');

    });

    #File upload
    Route::post('/upload-compalint-files', 'ManageFileController@uploadCompalintFiles')->name('upload.compalint.files');
    #remove File
    Route::delete('/remove-compalint-files/{file}', 'ManageFileController@removeCompalintFiles')->name('remove.compalint.files');

    #categories
    Route::post('sub-categories', 'CategoryController@subCategories')->name('sub.categories');

    #signout
    Route::get('/logout', 'SignoutController@signout')->name('signout.perform');

    #change-password
    Route::post('/change-password', 'ChangePasswordController@changePassword')->name('change.password.perform');

});

#Guest For App\Http\Controllers\Frontend
Route::group(['namespace' => 'App\Http\Controllers\Frontend'], function () {
    #cms
    Route::get('/{url?}', 'CMSController@index')->name('cms.pages');
});
