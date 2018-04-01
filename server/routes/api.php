<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/img', 'API\\ImgController@getByTakenAfter');
Route::post('/img', 'API\\ImgController@create');

Route::post('/objects_of_imgs', 'API\\ImgController@addImgObjRel');

Route::get('/objects', 'API\\ObjectController@all');

Route::get('/search', 'API\\ImgController@getByObjectName');

Route::get('/latest_process_time', 'API\\ImgController@latestProcessTime');
