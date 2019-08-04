<?php

/**
* Created by PhpStorm.
* User: mtt17
* Date: 2018/4/20
* Time: 10:50
*/

namespace App\Http\Controllers\Admin;

use App\Components\Common\RequestValidator;
use App\Components\AdminLoginManager;
use App\Components\Common\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use App\Models\AdminLogin;
use Illuminate\Http\Request;

class AdminLoginController
{

    /*
    * 首页
    *
    * By Auto CodeCreator
    *
    * 2019-05-11 18:01:25
    */
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //相关搜素条件
        $status = null;
        $search_word = null;
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'status' => $status,
            'search_word' => $search_word,
        );
        $admin_logins =AdminLoginManager::getListByCon($con_arr, true);
        foreach ($admin_logins as $admin_login) {
        $admin_login = AdminLoginManager::getInfoByLevel($admin_login, '');
        }

        return view('admin.admin_login.index', ['admin' => $admin, 'datas' => $admin_logins, 'con_arr' => $con_arr]);
    }

    /*
    * 编辑-get
    *
    * By Auto CodeCreator
    *
    * 2019-05-11 18:01:25
    */
    public function edit(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $admin_login = new AdminLogin();
        if (array_key_exists('id', $data)) {
        $admin_login = AdminLoginManager::getById($data['id']);
        }
        return view('admin.admin_login.edit', ['admin' => $admin, 'data' => $admin_login, 'upload_token' => $upload_token]);
    }


    /*
    * 添加或编辑-post
    *
    * By Auto CodeCreator
    *
    * 2019-05-11 18:01:25
    */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $admin_login = new AdminLogin();
        //存在id是保存
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $admin_login = AdminLoginManager::getById($data['id']);
        }
        $data['admin_id'] = $admin['id'];
        $admin_login = AdminLoginManager::setInfo($admin_login, $data);
        $admin_login->save();
        return ApiResponse::makeResponse(true, $admin_login, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 设置状态
    *
    * By Auto CodeCreator
    *
    * 2019-05-11 18:01:25
    */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        if (is_numeric($id) !== true) {
            return ApiResponse::makeResponse(false, "合规校验失败，请检查参数", ApiResponse::INNER_ERROR);
        }
        $admin_login = AdminLoginManager::getById($data['id']);
        $admin_login = AdminLoginManager::setInfo($admin_login, $data);
        $admin_login->save();
        return ApiResponse::makeResponse(true, $admin_login, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 查看信息
    *
    * By Auto CodeCreator
    *
    * 2019-05-11 18:01:25
    *
    */
    public function info(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
        'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
        return ApiResponse::makeResponse(false, "合规校验失败，请检查参数", ApiResponse::INNER_ERROR);
        }
        //信息
        $admin_login = AdminLoginManager::getById($data['id']);
        $admin_login = AdminLoginManager::getInfoByLevel($admin_login, '0');

        return view('admin.admin_login.info', ['admin' => $admin, 'data' => $admin_login]);
    }



}

