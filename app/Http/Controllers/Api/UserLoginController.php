<?php

/**
* Created by PhpStorm.
* User:robot
* Date: 2019-06-18 03:50:15
* Time: 13:29
*/

namespace App\Http\Controllers\Api;


use App\Components\Common\RequestValidator;
use App\Components\UserLoginManager;
use App\Components\Common\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use Illuminate\Http\Request;

class UserLoginController
{

    /*
    * 根据id获取信息
    *
    * By Auto CodeCreator
    *
    * 2019-06-18 03:50:15
    */
    public function getById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $user_login = UserLoginManager::getById($data['id']);
        //补充信息
        if($user_login){
            $level = null;
            if (array_key_exists('level', $data) && !Utils::isObjNull($data['level'])) {
                $level = $data['level'];
            }
            $user_login = UserLoginManager::getInfoByLevel($user_login,$level);
        }
        return ApiResponse::makeResponse(true, $user_login, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 根据条件获取列表
    *
    * By Auto CodeCreator
    *
    * 2019-06-18 03:50:15
    */
    public function getListByCon(Request $request)
    {
        $data = $request->all();
        $status = '1';
        $level='';
        //配置获取信息级别
        if (array_key_exists('level', $data) && !Utils::isObjNull($data['level'])) {
            $level = $data['level'];
        }

        //配置条件
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }
        $con_arr = array(
            'status' => $status,
        );
        $user_logins = UserLoginManager::getListByCon($con_arr, true);
        foreach ($user_logins as $user_login) {
            $user_login = UserLoginManager::getInfoByLevel($user_login, $level);
        }

        return ApiResponse::makeResponse(true, $user_logins, ApiResponse::SUCCESS_CODE);
    }
}

