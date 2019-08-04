<?php

/**
* Created by PhpStorm.
* User: mtt17
* Date: 2018/4/20
* Time: 10:50
*/

namespace App\Http\Controllers\Admin;

use App\Components\Common\RequestValidator;
use App\Components\UserLoginManager;
use App\Components\Common\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class UserLoginController
{

    /*
    * 首页
    *
    * By Auto CodeCreator
    *
    * 2019-05-15 15:34:10
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
        $user_logins =UserLoginManager::getListByCon($con_arr, true);
        foreach ($user_logins as $user_login) {
        $user_login = UserLoginManager::getInfoByLevel($user_login, '');
        }

        return view('admin.userLogin.index', ['admin' => $admin, 'datas' => $user_logins, 'con_arr' => $con_arr]);
    }

    /*
    * 编辑-get
    *
    * By Auto CodeCreator
    *
    * 2019-05-15 15:34:10
    */
    public function edit(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $user_login = new UserLogin();
        if (array_key_exists('id', $data)) {
        $user_login = UserLoginManager::getById($data['id']);
        }
        return view('admin.userLogin.edit', ['admin' => $admin, 'data' => $user_login, 'upload_token' => $upload_token]);
    }


    /*
    * 添加或编辑-post
    *
    * By Auto CodeCreator
    *
    * 2019-05-15 15:34:10
    */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $user_login = new UserLogin();
        //存在id是保存
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $user_login = UserLoginManager::getById($data['id']);
        }
        $data['admin_id'] = $admin['id'];
        $user_login = UserLoginManager::setInfo($user_login, $data);
        $user_login->save();
        return ApiResponse::makeResponse(true, $user_login, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 设置状态
    *
    * By Auto CodeCreator
    *
    * 2019-05-15 15:34:10
    */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        if (is_numeric($id) !== true) {
            return ApiResponse::makeResponse(false, "合规校验失败，请检查参数", ApiResponse::INNER_ERROR);
        }
        $user_login = UserLoginManager::getById($data['id']);
        $user_login = UserLoginManager::setInfo($user_login, $data);
        $user_login->save();
        return ApiResponse::makeResponse(true, $user_login, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 查看信息
    *
    * By Auto CodeCreator
    *
    * 2019-05-15 15:34:10
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
        $user_login = UserLoginManager::getById($data['id']);
        $user_login = UserLoginManager::getInfoByLevel($user_login, '0');

        return view('admin.userLogin.info', ['admin' => $admin, 'data' => $user_login]);
    }

}

