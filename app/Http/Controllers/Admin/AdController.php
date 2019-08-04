<?php

/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/20
 * Time: 10:50
 */

namespace App\Http\Controllers\Admin;

use App\Components\Common\RequestValidator;
use App\Components\AdManager;
use App\Components\Common\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController
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
        $ads = AdManager::getListByCon($con_arr, true);
        foreach ($ads as $ad) {
            $ad = AdManager::getInfoByLevel($ad, '');
        }

        return view('admin.ad.index', ['admin' => $admin, 'datas' => $ads, 'con_arr' => $con_arr]);
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
        $ad = new Ad();
        if (array_key_exists('id', $data)) {
            $ad = AdManager::getById($data['id']);
        }
        return view('admin.ad.edit', ['admin' => $admin, 'data' => $ad, 'upload_token' => $upload_token]);
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
        $ad = new Ad();
        //存在id是保存
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $ad = AdManager::getById($data['id']);
        }
        $data['admin_id'] = $admin['id'];
        $ad = AdManager::setInfo($ad, $data);
        $ad->save();
        return ApiResponse::makeResponse(true, $ad, ApiResponse::SUCCESS_CODE);
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
        $ad = AdManager::getById($data['id']);
        $ad = AdManager::setInfo($ad, $data);
        $ad->save();
        return ApiResponse::makeResponse(true, $ad, ApiResponse::SUCCESS_CODE);
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
        $ad = AdManager::getById($data['id']);
        $ad = AdManager::getInfoByLevel($ad, '0');

        return view('admin.ad.info', ['admin' => $admin, 'data' => $ad]);
    }

}

