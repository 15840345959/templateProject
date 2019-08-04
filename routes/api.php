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

//测试接口
Route::group(['prefix' => 'test', 'middleware' => ['BeforeRequest', 'CheckTestENV']], function () {

    //测试用路由
    Route::get('/test', 'Api\TestController@test');

});

//支付结果通知等接口，不过跨域中间件
Route::group(['prefix' => '', 'middleware' => ['BeforeRequest']], function () {

});

//公共接口
Route::group(['prefix' => '', 'middleware' => ['BeforeRequest', 'CheckItemValid', 'RecordFormId']], function () {

    //获取七牛token
    Route::get('getQiniuToken', 'Api\QNController@getQiniuToken')->middleware('CheckToken');       //获取七牛token

    //user
    Route::get('user/getById', 'Api\UserController@getById')->middleware('CheckToken');
    Route::get('user/getListByCon', 'Api\UserController@getListByCon');
    Route::post('user/login', 'Api\UserController@login');       //用户登陆
    Route::post('user/bindUnionId', 'Api\UserController@bindUnionId')->middleware('CheckToken');          //绑定unionid
    Route::post('user/updateById', 'Api\UserController@updateById')->middleware('CheckToken');       //更新用户信息
    Route::get('user/isWorker', 'Api\UserController@isWorker')->middleware('CheckToken');       //判断用户是否为员工

});
