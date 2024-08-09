<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'App\Http\Controllers\Api','middleware' => ['custom.client']], function() {//,'middleware' => ['custom.client'] ,'middleware' => ['ipcheck']
    Route::post('/create-complaint', 'ComplaintController@create')->name('create.complaint');
    Route::post('/track-complaint', 'ComplaintController@track')->name('track.complaint');
    Route::post('/review', 'ComplaintController@review')->name('review');
});