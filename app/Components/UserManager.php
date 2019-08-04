<?php


/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Components;


use App\Components\Common\DateTool;
use App\Components\Common\Utils;
use App\Models\User;
use App\Models\UserLogin;
use Leto\MiniProgramAES\WXBizDataCrypt;

class UserManager
{

    /*
     * getById
     *
     * By TerryQi
     *
     * 2019-4-15
     */
    public static function getById($id)
    {
        $info = User::where('id', $id)->first();
        return $info;
    }

    /*
     * getInfoByLevel
     *
     * By TerryQi
     *
     * 2019-02-25
     *
     */
    public static function getInfoByLevel($info, $level)
    {

        $info->gender_str = Project::USER_GENDER_VAL[$info->gender];
        $info->status_str = Project::COMMON_STATUS_VAL[$info->status];

        //0:
        if (strpos($level, '0') !== false) {

        }
        //1:    设置是否绑定标识
        if (strpos($level, '1') !== false) {
            $info->bind_flag = UserLoginManager::isBindUnionId($info->id, Utils::ACCOUNT_TYPE_XCX);
        }
        //2:带登录信息，里面的token等
        if (strpos($level, '2') !== false) {
            $userLogin = UserLoginManager::getListByCon(['user_id' => $info->id, 'status' => '1'], false)->first();
            $info->token = $userLogin->token;
        }
        //3:    用户是否为主管
        if (strpos($level, '3') !== false) {
            $company_user = CompanyUserManager::getByUserId($info->id);
            if ($company_user) {
                $info->is_company_user = true;
            } else {
                $info->is_company_user = false;
            }
        }
        //4:    用户是否为员工
        if (strpos($level, '4') !== false) {
            $worker = WorkerManager::getByUserId($info->id);
            if ($worker) {
                $info->is_worker = true;
            } else {
                $info->is_worker = false;
            }
        }

        //X:        脱敏
        if (strpos($level, 'X') !== false) {

        }
        //Y:        压缩，去掉content_html等大报文信息
        if (strpos($level, 'Y') !== false) {

        }
        //Z:        预留
        if (strpos($level, 'Z') !== false) {

        }


        return $info;
    }

    /*
     * getListByCon
     *
     * By mtt
     *
     * 2018-4-9
     */
    public static function getListByCon($con_arr, $is_paginate)
    {

        $infos = new User();

        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('phonenum', 'like', "%{$keyword}%")
                    ->orwhere('nick_name', 'like', "%{$keyword}%")
                    ->orwhere('real_name', 'like', "%{$keyword}%");
            });
        }

        if (array_key_exists('id', $con_arr) && !Utils::isObjNull($con_arr['id'])) {
            $infos = $infos->where('id', '=', $con_arr['id']);
        }

        if (array_key_exists('real_name', $con_arr) && !Utils::isObjNull($con_arr['real_name'])) {
            $infos = $infos->where('real_name', '=', $con_arr['real_name']);
        }

        if (array_key_exists('nick_name', $con_arr) && !Utils::isObjNull($con_arr['nick_name'])) {
            $infos = $infos->where('nick_name', '=', $con_arr['nick_name']);
        }

        if (array_key_exists('avatar', $con_arr) && !Utils::isObjNull($con_arr['avatar'])) {
            $infos = $infos->where('avatar', '=', $con_arr['avatar']);
        }

        //2019-05-21，用于替换存量的在微信上的头像
        if (array_key_exists('avatar_search_word', $con_arr) && !Utils::isObjNull($con_arr['avatar_search_word'])) {
            $infos = $infos->where('avatar', 'like', "%" . $con_arr['avatar_search_word'] . "%");
        }

        if (array_key_exists('phonenum', $con_arr) && !Utils::isObjNull($con_arr['phonenum'])) {
            $infos = $infos->where('phonenum', '=', $con_arr['phonenum']);
        }

        if (array_key_exists('gender', $con_arr) && !Utils::isObjNull($con_arr['gender'])) {
            $infos = $infos->where('gender', '=', $con_arr['gender']);
        }

        if (array_key_exists('type', $con_arr) && !Utils::isObjNull($con_arr['type'])) {
            $infos = $infos->where('type', '=', $con_arr['type']);
        }

        if (array_key_exists('code', $con_arr) && !Utils::isObjNull($con_arr['code'])) {
            $infos = $infos->where('code', '=', $con_arr['code']);
        }

        if (array_key_exists('country', $con_arr) && !Utils::isObjNull($con_arr['country'])) {
            $infos = $infos->where('country', '=', $con_arr['country']);
        }

        if (array_key_exists('province', $con_arr) && !Utils::isObjNull($con_arr['province'])) {
            $infos = $infos->where('province', '=', $con_arr['province']);
        }

        if (array_key_exists('city', $con_arr) && !Utils::isObjNull($con_arr['city'])) {
            $infos = $infos->where('city', '=', $con_arr['city']);
        }

        if (array_key_exists('birthday', $con_arr) && !Utils::isObjNull($con_arr['birthday'])) {
            $infos = $infos->where('birthday', '=', $con_arr['birthday']);
        }

        if (array_key_exists('language', $con_arr) && !Utils::isObjNull($con_arr['language'])) {
            $infos = $infos->where('language', '=', $con_arr['language']);
        }

        if (array_key_exists('level', $con_arr) && !Utils::isObjNull($con_arr['level'])) {
            $infos = $infos->where('level', '=', $con_arr['level']);
        }

        if (array_key_exists('sign', $con_arr) && !Utils::isObjNull($con_arr['sign'])) {
            $infos = $infos->where('sign', '=', $con_arr['sign']);
        }

        if (array_key_exists('score', $con_arr) && !Utils::isObjNull($con_arr['score'])) {
            $infos = $infos->where('score', '=', $con_arr['score']);
        }

        if (array_key_exists('a_user_id', $con_arr) && !Utils::isObjNull($con_arr['a_user_id'])) {
            $infos = $infos->where('a_user_id', '=', $con_arr['a_user_id']);
        }

        if (array_key_exists('seq', $con_arr) && !Utils::isObjNull($con_arr['seq'])) {
            $infos = $infos->where('seq', '=', $con_arr['seq']);
        }

        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
        }

        if (array_key_exists('created_at_date', $con_arr) && !Utils::isObjNull($con_arr['created_at_date'])) {
            $infos = $infos->whereDate('created_at', $con_arr['created_at_date']);
        }

        if (array_key_exists('created_at', $con_arr) && !Utils::isObjNull($con_arr['created_at'])) {
            $infos = $infos->where('created_at', '=', $con_arr['created_at']);
        }

        if (array_key_exists('updated_at', $con_arr) && !Utils::isObjNull($con_arr['updated_at'])) {
            $infos = $infos->where('updated_at', '=', $con_arr['updated_at']);
        }

        if (array_key_exists('deleted_at', $con_arr) && !Utils::isObjNull($con_arr['deleted_at'])) {
            $infos = $infos->where('deleted_at', '=', $con_arr['deleted_at']);
        }


        $infos = $infos->orderby('seq', 'desc')->orderby('id', 'desc');
        if ($is_paginate) {
            $infos = $infos->paginate(Utils::PAGE_SIZE);
        } else {
            $infos = $infos->get();
        }
        return $infos;
    }

    /*
     * setInfo
     *
     * By TerryQi
     *
     * 2018-06-11
     */
    public static function setInfo($info, $data)
    {


        if (array_key_exists('id', $data)) {
            $info->id = $data['id'];
        }

        if (array_key_exists('real_name', $data)) {
            $info->real_name = $data['real_name'];
        }

        if (array_key_exists('nick_name', $data)) {
            $info->nick_name = $data['nick_name'];
        }

        if (array_key_exists('avatar', $data)) {
            $info->avatar = $data['avatar'];
        }

        if (array_key_exists('phonenum', $data)) {
            $info->phonenum = $data['phonenum'];
        }

        if (array_key_exists('gender', $data)) {
            $info->gender = $data['gender'];
        }

        if (array_key_exists('type', $data)) {
            $info->type = $data['type'];
        }

        if (array_key_exists('code', $data)) {
            $info->code = $data['code'];
        }

        if (array_key_exists('country', $data)) {
            $info->country = $data['country'];
        }

        if (array_key_exists('province', $data)) {
            $info->province = $data['province'];
        }

        if (array_key_exists('city', $data)) {
            $info->city = $data['city'];
        }

        if (array_key_exists('birthday', $data)) {
            $info->birthday = $data['birthday'];
        }

        if (array_key_exists('language', $data)) {
            $info->language = $data['language'];
        }

        if (array_key_exists('level', $data)) {
            $info->level = $data['level'];
        }

        if (array_key_exists('sign', $data)) {
            $info->sign = $data['sign'];
        }

        if (array_key_exists('a_user_id', $data)) {
            $info->a_user_id = $data['a_user_id'];
        }

        if (array_key_exists('seq', $data)) {
            $info->seq = $data['seq'];
        }

        if (array_key_exists('created_at', $data)) {
            $info->created_at = $data['created_at'];
        }

        if (array_key_exists('updated_at', $data)) {
            $info->updated_at = $data['updated_at'];
        }

        if (array_key_exists('deleted_at', $data)) {
            $info->deleted_at = $data['deleted_at'];
        }

        return $info;
    }


    /*
     * 通过手机号注册用户
     *
     * By TerryQi
     *
     * 2019-04-26
     *
     * 要求进入的参数格式必须为
     *
     * [
     *      'phonenum'=>'15840345959',
     *      'account_type'=>'tel_passwd',
     *      'password'=>'e1880de65eb8b4af3996eee5904a7729'
     * ]
     *
     */
    public static function registerByTelPasswd($data)
    {
        //创建用户信息
        $user = new User();
        $user = self::setInfo($user, $data);
        $user->save();
        $user = self::getById($user->id);
        //创建用户密码
        $user_login = new UserLogin();
        $user_login->user_id = $user['id'];
        $user_login->token = Utils::getGUID();
        $user_login->account_type = $data['account_type'];
        $user_login->ve_value1 = $data['phonenum'];
        $user_login->ve_value2 = $data['password'];
        $user_login->login_at = DateTool::getCurrentTime();
        $user_login->save();
        return $user;
    }

    /*
     * 小程序的登录和注册流程
     *
     * By TerryQi
     *
     * 2018-07-04
     *
     * $data中应该包含openid、unionid（可选）、session信息
     *
     * !!!请注意，此处的用户是有新用户标识的
     */
    public static function loginXCX($data)
    {
        Utils::processLog(__METHOD__, '', " " . "data:" . json_encode($data));
        $user = null;   //应返回用户信息
        //第一步，入参中是否有unionid信息，如果有，则通过uniond判断用户，平台中是否已有用户信息
        if (array_key_exists('unionid', $data)) {
            $con_arr = array(
                've_value2' => $data['unionid']
            );
            $userLogin = UserLoginManager::getListByCon($con_arr, false)->first();  //根据unionid获取登录信息
            Utils::processLog(__METHOD__, '', " " . "condition1 login pos1:" . json_encode($userLogin));
            //如果已经有用户信息，则获取用户信息，并进行登录信息补全，返回用户信息
            if ($userLogin) {
                Utils::processLog(__METHOD__, '', " " . "condition1 login pos2:" . json_encode($userLogin));
                $user = UserManager::getById($userLogin->user_id);
                //补全登录信息
                self::setUserLoginXCX($user->id, $data);
                return $user;
            }
        }
        //第二步，入参是否有openid信息，如果有，则通过openid判断用户，平台中是否已有用户信息
        if (array_key_exists('openid', $data)) {
            $con_arr = array(
                've_value1' => $data['openid']
            );
            $userLogin = UserLoginManager::getListByCon($con_arr, false)->first();  //根据unionid获取登录信息
            Utils::processLog(__METHOD__, '', " " . "condition2 login pos1:" . json_encode($userLogin));
            //如果已经有用户信息，则获取用户信息，并进行登录信息补全，返回用户信息
            if ($userLogin) {
                Utils::processLog(__METHOD__, '', " " . "condition2 login pos2:" . json_encode($userLogin));
                $user = UserManager::getById($userLogin->user_id);
                //补全登录信息
                self::setUserLoginXCX($userLogin->user_id, $data);
                return $user;
            }
        }
        //第三步，如果均未返回用户信息，则代表需要新注册用户
        //注册用户
        $user = new User();
        $user = UserManager::setInfo($user, $data);
        $user->save();
        //如果是新注册用户，则给一个new_flag==true//////////////////////////////////////////////////
        $user->new_flag = true;
        //new_flag是新用户标识，主要标识用户信息////////////////////////////////////////////////////

        Utils::processLog(__METHOD__, '', " " . "user:" . json_encode($user));
        //补全登录信息
        $userLogin = self::setUserLoginXCX($user->id, $data);
        Utils::processLog(__METHOD__, '', " " . "condition3 login:" . json_encode($userLogin));
        return $user;
    }

    /*
     * 小程序补全登录信息，主要解决uniond、openid等缺失的问题
     *
     * By TerryQi
     *
     * 2018-07-03
     *
     * $data中应有busi_name、openid、unionid，分别映射login表中的busi_name、ve_value1、ve_value2
     *
     * $user_id为用户id，因此需要注册成功再处理绑定关系
     *
     * return false：失败 true：成功
     *
     */
    public static function setUserLoginXCX($user_id, $data)
    {
        Utils::processLog(__METHOD__, '', " " . "user_id:" . $user_id . " data:" . json_encode($data));
        //user_id如果为空不能往下走
        if (Utils::isObjNull($user_id)) {
            return null;
        }
        //获取基本信息
        $openid = $data['openid'];      //openid
        $unionid = null;        //unionid
        if (array_key_exists('unionid', $data)) {
            $unionid = $data['unionid'];        //unionid
        }
        //根据openid获取用户信息
        $con_arr = array(
            've_value1' => $openid
        );
        $userLogin = UserLoginManager::getListByCon($con_arr, false)->first();
        Utils::processLog(__METHOD__, '', " " . "login:" . json_encode($userLogin));
        //如果有值，就进行信息补全，如果没有就新建
        if (!$userLogin) {
            Utils::processLog(__METHOD__, '', " " . "不存在login，则新建login信息");
            $userLogin = new UserLogin();
            $userLogin->token = Utils::getGUID();
        }
        $userLogin->user_id = $user_id;
        $userLogin->account_type = Utils::ACCOUNT_TYPE_XCX;
        $userLogin->ve_value1 = $openid;
        $userLogin->ve_value2 = $unionid;
        $userLogin->login_at = DateTool::getCurrentTime();
        $userLogin->save();

        Utils::processLog(__METHOD__, '', " " . "login:" . json_encode($userLogin));

        return $userLogin;
    }

    /*
    * 进行消息解密
    *
    * By TerryQi
    *
    * 2018-11-22
    *
    * @app为外部信息，code、vi（注意已经解密）、encryptedData（注意已经解密）、env_appid_name
    */
    public static function decryptData($app, $code, $iv, $encryptedData, $env_appid_name)
    {
        Utils::processLog(__METHOD__, '', "code:" . $code . " iv:" . $iv . " encrytedData:" . $encryptedData . " env_appid_name:" . $env_appid_name);
        $code = $code;
        $result = $app->auth->session($code);
        Utils::processLog(__METHOD__, '', json_encode($result));
        //如果出错，返回null
        if (array_key_exists('errcode', $result)) {
            return null;
        }
        $sessionKey = $result['session_key'];
        Utils::processLog(__METHOD__, '', "sessionKey:" . json_encode($sessionKey));
        $appid = env($env_appid_name);
        Utils::processLog(__METHOD__, '', "appid:" . $appid);
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $result);
        Utils::processLog(__METHOD__, '', "errorCode:" . json_encode($errCode));
        if ($errCode == 0) {
            return (array)json_decode($result, true);
        } else {
            return null;
        }
    }

    /*
     * 将小程序的消息解密的数据返回至前端
     *
     * By TerryQi
     *
     * 2018-11-22
     */
    public static function convertDecryptDatatoUserData($decrytData)
    {
        $data = array(
            'openid' => $decrytData['openId'],
            'nick_name' => $decrytData['nickName'],
            'gender' => $decrytData['gender'],
            'language' => $decrytData['language'],
            'city' => $decrytData['city'],
            'province' => $decrytData['province'],
            'country' => $decrytData['country'],
            'avatar' => $decrytData['avatarUrl'],
        );
        if (array_key_exists('unionId', $decrytData) && !Utils::isObjNull($decrytData['unionId'])) {
            $data['unionid'] = $decrytData['unionId'];
        }
        return $data;
    }

}

