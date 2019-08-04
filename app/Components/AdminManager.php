<?php


/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Components;


use App\Components\Common\Utils;
use App\Models\Admin;

class AdminManager
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
        $info = Admin::where('id', $id)->first();
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
        $info->role_str = Project::ADMIN_ROLE_VAL[$info->role];
        $info->status_str = Project::COMMON_STATUS_VAL[$info->status];

        //0:
        if (strpos($level, '0') !== false) {

        }
        //1:
        if (strpos($level, '1') !== false) {

        }
        //2:
        if (strpos($level, '2') !== false) {

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

        $infos = new Admin();

        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orwhere('phonenum', 'like', "%{$keyword}%");
            });
        }

        if (array_key_exists('id', $con_arr) && !Utils::isObjNull($con_arr['id'])) {
            $infos = $infos->where('id', '=', $con_arr['id']);
        }

        if (array_key_exists('name', $con_arr) && !Utils::isObjNull($con_arr['name'])) {
            $infos = $infos->where('name', '=', $con_arr['name']);
        }

        if (array_key_exists('avatar', $con_arr) && !Utils::isObjNull($con_arr['avatar'])) {
            $infos = $infos->where('avatar', '=', $con_arr['avatar']);
        }

        if (array_key_exists('phonenum', $con_arr) && !Utils::isObjNull($con_arr['phonenum'])) {
            $infos = $infos->where('phonenum', '=', $con_arr['phonenum']);
        }

        if (array_key_exists('email', $con_arr) && !Utils::isObjNull($con_arr['email'])) {
            $infos = $infos->where('email', '=', $con_arr['email']);
        }

        if (array_key_exists('remark', $con_arr) && !Utils::isObjNull($con_arr['remark'])) {
            $infos = $infos->where('remark', '=', $con_arr['remark']);
        }

        if (array_key_exists('token', $con_arr) && !Utils::isObjNull($con_arr['token'])) {
            $infos = $infos->where('token', '=', $con_arr['token']);
        }

        if (array_key_exists('role', $con_arr) && !Utils::isObjNull($con_arr['role'])) {
            $infos = $infos->where('role', '=', $con_arr['role']);
        }

        if (array_key_exists('admin_id', $con_arr) && !Utils::isObjNull($con_arr['admin_id'])) {
            $infos = $infos->where('admin_id', '=', $con_arr['admin_id']);
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

        if (array_key_exists('name', $data)) {
            $info->name = $data['name'];
        }

        if (array_key_exists('avatar', $data)) {
            $info->avatar = $data['avatar'];
        }

        if (array_key_exists('phonenum', $data)) {
            $info->phonenum = $data['phonenum'];
        }

        if (array_key_exists('email', $data)) {
            $info->email = $data['email'];
        }

        if (array_key_exists('token', $data)) {
            $info->token = $data['token'];
        }

        if (array_key_exists('role', $data)) {
            $info->role = $data['role'];
        }


        if (array_key_exists('remark', $data)) {
            $info->remark = $data['remark'];
        }

        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = $data['admin_id'];
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

}

