<?php


/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Components;


use App\Components\Common\Utils;
use App\Models\UserLogin;

class UserLoginManager
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
        $info = UserLogin::where('id', $id)->first();
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
        $info->status_str = Utils::COMMON_STATUS_VAL[$info->status];

        //0:
        if (strpos($level, '0') !== false) {

        }
        //1:
        if (strpos($level, '1') !== false) {

        }
        //2:
        if (strpos($level, '2') !== false) {

        }

        //X:        脱敏
        if (strpos($level, 'X') !== false) {

        }
        //Y:        压缩，去掉content_html等大报文信息
        if (strpos($level, 'Y') !== false) {
            unset($info->content_html);
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
        $infos = new UserLogin();

        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
        }

        if (array_key_exists('ids_arr', $con_arr) && !empty($con_arr['ids_arr'])) {
            $infos = $infos->wherein('id', $con_arr['ids_arr']);
        }


        if (array_key_exists('id', $con_arr) && !Utils::isObjNull($con_arr['id'])) {
            $infos = $infos->where('id', '=', $con_arr['id']);
        }

        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $infos = $infos->where('user_id', '=', $con_arr['user_id']);
        }

        if (array_key_exists('token', $con_arr) && !Utils::isObjNull($con_arr['token'])) {
            $infos = $infos->where('token', '=', $con_arr['token']);
        }

        if (array_key_exists('account_type', $con_arr) && !Utils::isObjNull($con_arr['account_type'])) {
            $infos = $infos->where('account_type', '=', $con_arr['account_type']);
        }

        if (array_key_exists('ve_value1', $con_arr) && !Utils::isObjNull($con_arr['ve_value1'])) {
            $infos = $infos->where('ve_value1', '=', $con_arr['ve_value1']);
        }

        if (array_key_exists('ve_value2', $con_arr) && !Utils::isObjNull($con_arr['ve_value2'])) {
            $infos = $infos->where('ve_value2', '=', $con_arr['ve_value2']);
        }

        if (array_key_exists('login_at', $con_arr) && !Utils::isObjNull($con_arr['login_at'])) {
            $infos = $infos->where('login_at', '=', $con_arr['login_at']);
        }

        if (array_key_exists('valid_time', $con_arr) && !Utils::isObjNull($con_arr['valid_time'])) {
            $infos = $infos->where('valid_time', '=', $con_arr['valid_time']);
        }

        if (array_key_exists('seq', $con_arr) && !Utils::isObjNull($con_arr['seq'])) {
            $infos = $infos->where('seq', '=', $con_arr['seq']);
        }

        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
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

        //排序设定
        if (array_key_exists('orderby', $con_arr) && is_array($con_arr['orderby'])) {
            $orderby_arr = $con_arr['orderby'];
            //例子，传入数据样式为'status'=>'desc'
            if (array_key_exists('status', $orderby_arr) && !Utils::isObjNull($orderby_arr['status'])) {
                $infos = $infos->orderby('status', $orderby_arr['status']);
            }
        }
        $infos = $infos->orderby('seq', 'desc')->orderby('id', 'desc');

        //分页设定
        if ($is_paginate) {
            $page_size = Utils::PAGE_SIZE;
            //如果con_arr中有page_size信息
            if (array_key_exists('page_size', $con_arr) && !Utils::isObjNull($con_arr['page_size'])) {
                $page_size = $con_arr['page_size'];
            }
            $infos = $infos->paginate($page_size);
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

        if (array_key_exists('user_id', $data)) {
            $info->user_id = $data['user_id'];
        }

        if (array_key_exists('token', $data)) {
            $info->token = $data['token'];
        }

        if (array_key_exists('account_type', $data)) {
            $info->account_type = $data['account_type'];
        }

        if (array_key_exists('ve_value1', $data)) {
            $info->ve_value1 = $data['ve_value1'];
        }

        if (array_key_exists('ve_value2', $data)) {
            $info->ve_value2 = $data['ve_value2'];
        }

        if (array_key_exists('login_at', $data)) {
            $info->login_at = $data['login_at'];
        }

        if (array_key_exists('valid_time', $data)) {
            $info->valid_time = $data['valid_time'];
        }

        if (array_key_exists('seq', $data)) {
            $info->seq = $data['seq'];
        }

        if (array_key_exists('status', $data)) {
            $info->status = $data['status'];
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
     * 用户是否进行信息绑定
     *
     * By TerryQi
     *
     * 2018-11-22
     */
    public static function isBindUnionId($user_id, $account_type)
    {
        $con_arr = array(
            'user_id' => $user_id,
            'account_type' => $account_type,
        );
        $userLogin = self::getListByCon($con_arr, false)->first();
        Utils::processLog(__METHOD__, '', " " . "login:" . json_encode($userLogin));
        //如果存在登录信息且登录信息中的ve_value2不为空
        if ($userLogin && !Utils::isObjNull($userLogin->ve_value2)) {
            return true;
        } else {
            return false;
        }
    }

}

