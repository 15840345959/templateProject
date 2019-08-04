<?php

/**
 * Created by PhpStorm.
 * User:robot
 * Date: 2019-05-11 18:01:32
 * Time: 13:29
 */

namespace App\Http\Controllers\Api;


use App\Components\Common\RequestValidator;
use App\Components\Project;
use App\Components\UserLoginManager;
use App\Components\UserManager;
use App\Components\Common\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use App\Components\VericodeManager;
use App\Components\VertifyManager;
use App\Components\WorkerManager;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class UserController
{

    /*
     * 用户登录
     *
     * By Ada
     *
     * 2019-04-18
     */
    public function login(Request $request)
    {
        $data = $request->all();
        //合规校验openid
        $requestValidationResult = RequestValidator::validator($data, [
            'account_type' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //根据account_type判断各种登录
        $user = null;       //返回user
        switch ($data['account_type']) {
            case Utils::ACCOUNT_TYPE_TEL_PASSWORD:     //电话号码+密码登录
                $requestValidationResult = RequestValidator::validator($data, [
                    'phonenum' => 'required',
                    'password' => 'required',
                ]);
                if ($requestValidationResult !== true) {
                    return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
                }
                //查询是否存在此用户
                $con_userLogin_arr = array(
                    've_value1' => $data['phonenum'],
                    've_value2' => $data['password']
                );
                $userLogin = UserLoginManager::getListByCon($con_userLogin_arr, false)->first();
                //用户信息是否为空
                if (!$userLogin) {
                    return ApiResponse::makeResponse(false, "用户名或密码错误", ApiResponse::INNER_ERROR);
                }
                $user = UserManager::getById($userLogin->user_id);
                break;
            case Utils::ACCOUNT_TYPE_XCX:     //小程序登录+注册
                //合规校验account_type
                $requestValidationResult = RequestValidator::validator($request->all(), [
                    'code' => 'required'
                ]);
                if ($requestValidationResult !== true) {
                    return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
                }
                $app = app(Project::XCX_ACCOUNT_CONFIG);
                $code = $data['code'];  //获取小程序code
                $ret = $app->auth->session($code);
                Utils::processLog(__METHOD__, '', " " . "code ret:" . json_encode($ret));
                //判断微信端返回信息，如果失败，则告知前端失败
                if (array_key_exists('errcode', $ret)) {
                    return ApiResponse::makeResponse(false, $ret, ApiResponse::INNER_ERROR);
                }
                //如果成功获取openid和uniondid，则进行登录处理
                $data['openid'] = $ret['openid'];
                $data['session_key'] = $ret['session_key'];
                //如果传入unionid，则赋值unionid
                if (array_key_exists('unionid', $ret)) {
                    $data['unionid'] = $ret['unionid'];
                }
                Utils::processLog(__METHOD__, '', " " . "data:" . json_encode($data));
                $user = UserManager::loginXCX($data);
                break;

            default:
                break;
        }
        if (!$user) {
            return ApiResponse::makeResponse(false, "创建用户失败", ApiResponse::INNER_ERROR);
        }
        $user = UserManager::getById($user->id);
        $user = UserManager::getInfoByLevel($user, '2');        //增加token
        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 更新用户信息
     *
     * By Ada
     *
     * 2019-04-19
     */
    public function updateById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //根据用户id获取信息
        $user = UserManager::getById($data['user_id']);
        $user = UserManager::setInfo($user, $data);
        $user->save();
        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 重置密码
     *
     * By Ada
     *
     * 2019-04-19
     */
    public function resetPassword(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            'new_password' => 'required',
            'phonenum' => 'required',
            'vertify_code' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //校验验证码
        if (!VertifyManager::judgeVertifyCode($data['phonenum'], $data['vertify_code'])) {
            return ApiResponse::makeResponse(false, false, ApiResponse::VERTIFY_ERROR);
        }
        //验证手机号
        $con_arr = array(
            've_value1' => $data['phonenum']
        );
        $userLogin = UserLoginManager::getListByCon($con_arr, false)->first();
        if (!$userLogin) {
            return ApiResponse::makeResponse(false, false, ApiResponse::NO_USER);
        }
        //修改密码
        $userLogin->ve_value2 = $data['new_password'];
        $userLogin->save();
        //查询用户信息
        $user = UserManager::getById($userLogin['user_id']);
        $user['userLogin'] = $userLogin;
        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据id获取信息
     *
     * By Ada
     *
     * 2019-04-19
     */
    public function getById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($data, [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //根据用户id获取信息
        $user = UserManager::getById($data['user_id']);

        $level = "";
        if (array_key_exists('level', $data) && !Utils::isObjNull($data['level'])) {
            $level = $data['level'];
        }

        $user = UserManager::getInfoByLevel($user, $level);

        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }


    /*
    * 下发验证码
    *
    * By TerryQi
    *
    * 2017-11-28
    *
    */
    public function sendVertifyCode(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'phonenum' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $result = VertifyManager::sendVericode($data['phonenum']);
        if ($result) {
            return ApiResponse::makeResponse(true, '验证码发送成功', ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMessage[ApiResponse::UNKNOW_ERROR], ApiResponse::UNKNOW_ERROR);
        }
    }


    /*
     * 绑定unionid接口
     *
     * By TerryQi
     *
     * 2018-11-22
     *
     */
    public function bindUnionId(Request $request)
    {
        $data = $request->all();
//        dd($data);
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'code' => 'required',
            'iv' => 'required',
            'encryptedData' => 'required',
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //判断是否存在用户信息
        $user = UserManager::getById($data['user_id']);
        Utils::processLog(__METHOD__, '', " " . "user:" . json_encode($user));
        if (!$user) {
            return ApiResponse::makeResponse(false, "不存在用户信息", ApiResponse::INNER_ERROR);
        }
        //获取信息
        $code = $data['code'];
        $iv = base64_decode($data['iv']);
        $encryptedData = base64_decode($data['encryptedData']);
        $app = app(Project::XCX_ACCOUNT_CONFIG);
        $result = UserManager::decryptData($app, $code, $iv, $encryptedData, 'WECHAT_MINI_PROGRAM_APPID');
        if ($result == null) {
            return ApiResponse::makeResponse(false, "解析消息失败", ApiResponse::INNER_ERROR);
        }
        Utils::processLog(__METHOD__, '', " " . "result json_decode:" . json_encode($result));
        $user_data = UserManager::convertDecryptDatatoUserData($result);    //转为数据库字段名字
        Utils::processLog(__METHOD__, '', " " . "user_date:" . json_encode($user_data));

        //此处一定要注意，避免token丢失
        $user = UserManager::getById($user->id);
        $user = UserManager::setInfo($user, $user_data);
        $user->save();
        Utils::processLog(__METHOD__, '', " " . "after set data user:" . json_encode($user));

        //进行unionid的绑定//////////////////////////////////////
        if (array_key_exists('unionid', $user_data) && !Utils::isObjNull($user_data['unionid'])) {
            $con_arr = array(
                've_value1' => $user_data['openid'],
                'user_id' => $user->id
            );
            Utils::processLog(__METHOD__, '', " " . "userLogin con_arr:" . json_encode($con_arr));
            $userLogin = UserLoginManager::getListByCon($con_arr, false)->first();
            Utils::processLog(__METHOD__, '', " " . "userLogin:" . json_encode($userLogin));
            if ($userLogin) {
                $userLogin->ve_value2 = $user_data['unionid'];
                $userLogin->save();
            } else {
                //2019-02-26日优化逻辑，可能存在没有登录信息的情况
                $userLogin = new userLogin();
                $data['user_id'] = $con_arr['user_id'];
                $data['ve_value1'] = $con_arr['ve_value1'];
                $data['ve_value2'] = $user_data['unionid'];
                $data['account_type'] = Utils::ACCOUNT_TYPE_XCX;
                $userLogin = userLoginManager::setInfo($userLogin, $data);
                $userLogin->save();
                Utils::processLog(__METHOD__, '', " " . "after set value userLogin:" . json_encode($userLogin));
            }

        }
        /////////////////////////////////////////////////////////
        //此处要注意，重新获取用户，去除token
        $user = UserManager::getById($user->id);
        $user = UserManager::getInfoByLevel($user, '2');

        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 用户是否为员工
     *
     * By TerryQi
     *
     * 2019-07-12
     */
    public function isWorker(Request $request)
    {
        $data = $request->all();
//        dd($data);
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $worker = WorkerManager::getListByCon(['user_id' => $data['user_id']], false)->first();
        if (!$worker) {
            return ApiResponse::makeResponse(false, $worker, ApiResponse::NOT_WORKER, "该用户还不是员工");
        }
        if ($worker->status == Utils::STATUS_VALUE_0) {
            return ApiResponse::makeResponse(false, $worker, ApiResponse::INNER_ERROR, "员工身份已经失效");
        }

        return ApiResponse::makeResponse(true, $worker, ApiResponse::SUCCESS_CODE);
    }


}

