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
// TODO: test /api/objects_of_imgs
// test case:
// [ { imgId: 1, objects: [ 'pen' ] } ]
// [ { imgId: 1, objects: [ 'pencil', 'key' ] } ]
// [ { imgId: 1, objects: [ 'pencil', 'wallet' ] }, { imgId: 2, objects: [ 'key' ] } ]
// [ { imgId: 1, objects: [ 'pencil', 'wallet' ] }, { imgId: 2, objects: [ 'mouse' ] } ]
