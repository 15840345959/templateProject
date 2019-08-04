<?php

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


//登录
Route::get('/admin/login', 'Admin\LoginController@login');        //登录
Route::post('/admin/login', 'Admin\LoginController@loginPost');   //post登录请求
Route::get('/admin/logout', 'Admin\LoginController@logout');  //注销

Route::get('captcha/code/{tmp}', 'CodeController@captcha');  //验证码

Route::group(['prefix' => 'admin', 'middleware' => ['BeforeRequest', 'CheckAdminLogin']], function () {

    //业务概览
    Route::get('/overview/index', 'Admin\OverviewController@index');
    Route::any('/overview/jobOrderItemTrend', 'Admin\OverviewController@jobOrderItemTrend');  //工作包趋势
    Route::any('/overview/manCompanyStmt', 'Admin\OverviewController@manCompanyStmt');  //物业公司
    Route::any('/overview/busCompanyStmt', 'Admin\OverviewController@busCompanyStmt');  //在管项目

    //首页
    Route::get('/', 'Admin\IndexController@index');       //首页
    Route::get('/index', 'Admin\IndexController@index');  //首页

    //错误页面
    Route::get('/error/500', ['as' => 'error', 'uses' => 'Admin\IndexController@error']);  //错误页面

    //关于我们
    Route::any('/aboutus/index', 'Admin\AboutUsController@index');  //aboutus首页


    //管理员管理
    Route::any('/admin/index', 'Admin\AdminController@index');  //管理员管理首页
    Route::get('/admin/setStatus/{id}', 'Admin\AdminController@setStatus');  //设置管理员状态
    Route::get('/admin/edit', 'Admin\AdminController@edit');  //新建或编辑管理员
    Route::post('/admin/edit', 'Admin\AdminController@editPost');  //新建或编辑管理员
    Route::get('/admin/editPassword', 'Admin\AdminController@editPassword');  //修改个人密码get
    Route::post('/admin/editPassword', 'Admin\AdminController@editPasswordPost');  //修改个人密码post
    Route::get('/admin/editMyself', 'Admin\AdminController@editMyself');  //修改个人信息get
    Route::post('/admin/editMyself', 'Admin\AdminController@editMyselfPost');  //修改个人信息post
    Route::get('/admin/resetPassword/{id}', 'Admin\AdminController@resetPassword');  //设置adminLogin的密码


    //adminLogin
    Route::any('/adminLogin/index', 'Admin\AdminLoginController@index');  //adminLogin首页
    Route::get('/adminLogin/edit', 'Admin\AdminLoginController@edit');  //编辑adminLogin
    Route::post('/adminLogin/edit', 'Admin\AdminLoginController@editPost');  //编辑adminLogin
    Route::get('/adminLogin/setStatus/{id}', 'Admin\AdminLoginController@setStatus');  //设置adminLogin状态


    //user
    Route::any('/user/index', 'Admin\UserController@index');  //user首页
    Route::get('/user/edit', 'Admin\UserController@edit');  //编辑user
    Route::post('/user/edit', 'Admin\UserController@editPost');  //编辑user
    Route::get('/user/setStatus/{id}', 'Admin\UserController@setStatus');  //设置user状态


    //userLogin
    Route::any('/userLogin/index', 'Admin\UserLoginController@index');  //userLogin首页
    Route::get('/userLogin/edit', 'Admin\UserLoginController@edit');  //编辑userLogin
    Route::post('/userLogin/edit', 'Admin\UserLoginController@editPost');  //编辑userLogin
    Route::get('/userLogin/setStatus/{id}', 'Admin\UserLoginController@setStatus');  //设置userLogin状态


    //xcxFormId
    Route::any('/xcxFormId/index', 'Admin\XcxFormIdController@index');  //xcxFormId首页
    Route::get('/xcxFormId/edit', 'Admin\XcxFormIdController@edit');  //编辑xcxFormId
    Route::post('/xcxFormId/edit', 'Admin\XcxFormIdController@editPost');  //编辑xcxFormId
    Route::get('/xcxFormId/setStatus/{id}', 'Admin\XcxFormIdController@setStatus');  //设置xcxFormId状态

});
