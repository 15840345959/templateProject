<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\AdminLoginManager;
use App\Components\AdminManager;
use App\Components\Common\DateTool;
use App\Components\Project;
use App\Components\Common\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminLogin;
use Illuminate\Http\Request;
use App\Components\Common\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class AdminController extends Controller
{
    /*
     * 管理员首页
     *
     * By Ada
     *
     * 2019-04-10
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //相关搜素条件
        $search_word = null;
        $role = null;
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('role', $data) && !Utils::isObjNull($data['role'])) {
            $role = $data['role'];
        }
        $con_arr = array(
            'search_word' => $search_word,
            'role' => $role
        );
        $admins = AdminManager::getListByCon($con_arr, true);
        foreach ($admins as $admin) {
            $admin = AdminManager::getInfoByLevel($admin, '0');
        }

//        dd($admins);

        return view('admin.admin.index', ['datas' => $admins, 'con_arr' => $con_arr]);
    }

    /*
     * 新建或编辑管理员-get
     *
     * By Ada
     *
     * 2019-04-13
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $admin_b = new Admin();
        if (array_key_exists('id', $data)) {
            $admin_b = AdminManager::getById($data['id']);
            $admin_b = AdminManager::getInfoByLevel($admin_b, '');
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.admin.edit', ['admin' => $admin, 'data' => $admin_b, 'upload_token' => $upload_token]);
    }

    /*
     * 新建或编辑管理员->post
     *
     * By Ada
     *
     * 2019-04-13
     */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $admin_b = new Admin();
        //存在id是保存
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $admin_b = AdminManager::getById($data['id']);
            //保存查看手机号是否重复
            if (array_key_exists('phonenum', $data) && !Utils::isObjNull($data['phonenum'])) {
                $e_admin = AdminManager::getListByCon(['phonenum' => $data['phonenum']], false)->first();
                if ($e_admin->id != $data['id']) {
                    return ApiResponse::makeResponse(false, "手机号重复", ApiResponse::PHONENUM_DUP);
                }
            }
        } else {
            //新建进行校验，手机号是否重复
            if (array_key_exists('phonenum', $data) && !Utils::isObjNull($data['phonenum'])) {
                $con_arr = array(
                    'phonenum' => $data['phonenum']
                );
                $e_admin = AdminManager::getListByCon($con_arr, false)->first();
                if ($e_admin) {
                    return ApiResponse::makeResponse(false, "手机号重复", ApiResponse::PHONENUM_DUP);
                }
            }
        }
        $admin_b = AdminManager::setInfo($admin_b, $data);
        $admin_b->admin_id = $admin->id;
        $admin_b->save();
        //如果不存在id代表新建，则默认设置密码
        if (!array_key_exists('id', $data) || Utils::isObjNull($data['id'])) {
            $password = env('DEFAULT_PASSWORD', '');  //该password为Aa123456的码
            $admin_b_login = new AdminLogin();
            $admin_b_login->admin_id = $admin_b['id'];
            $admin_b_login->account_type = Utils::ACCOUNT_TYPE_TEL_PASSWORD;
            $admin_b_login->ve_value1 = $data['phonenum'];
            $admin_b_login->ve_value2 = $password;
            $admin_b_login->token = Utils::getGUID();
            $admin_b_login->login_at = DateTool::getCurrentTime();
            $admin_b_login->save();
        }
        return ApiResponse::makeResponse(true, $data, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 设置管理员状态
     *
     * By Ada
     *
     * 2019-04-13
     */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        if (is_numeric($id) !== true) {
            return ApiResponse::makeResponse(false, "合规校验失败，请检查参数", ApiResponse::INNER_ERROR);
        }
        $admin = AdminManager::getById($id);
        $admin->status = $data['status'];
        $admin->save();
        return ApiResponse::makeResponse(true, $admin, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 编辑个人密码-get
     *
     * By Ada
     *
     * 2019-04-10
     */
    public function editPassword(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $upload_token = QNManager::uploadToken();
        return view('admin.admin.editPassword', ['admin' => $admin, 'data' => $admin, 'upload_token' => $upload_token]);
    }

    /*
     * 新建或编辑个人密码->post
     *
     * By Ada
     *
     * 2019-04-13
     */
    public function editPasswordPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $con_arr = array(
            'admin_id' => $admin->id,
            'account_type' => Utils::ACCOUNT_TYPE_TEL_PASSWORD
        );
        $admin_login = AdminLoginManager::getListByCon($con_arr, false)->first();
        Utils::processLog(__METHOD__, 'admin_login:' . json_encode($admin_login));
        if ($data['old_password'] != $admin_login->ve_value2) {
            return ApiResponse::makeResponse(false, "原密码输入不正确", ApiResponse::INNER_ERROR, "原密码输入不正确");
        }
        $admin_login->ve_value2 = $data['password'];
        $admin_login->save();
        return ApiResponse::makeResponse(true, "修改成功", ApiResponse::SUCCESS_CODE);
    }


    /*
     * 编辑个人密码-get
     *
     * By Ada
     *
     * 2019-04-10
     */
    public function editMyself(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $admin_b = AdminManager::getById($admin->id);
        $admin_b = AdminManager::getInfoByLevel($admin_b, '');
        Utils::processLog(__METHOD__, '', 'admin_b:' . json_encode($admin_b));
        $upload_token = QNManager::uploadToken();
        return view('admin.admin.editMyself', ['admin' => $admin, 'data' => $admin_b, 'upload_token' => $upload_token]);
    }

    /*
     * 新建或编辑个人密码->post
     *
     * By Ada
     *
     * 2019-04-13
     */
    public function editMyselfPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        Utils::processLog(__METHOD__, '', 'admin:' . json_encode($admin));
        $admin_b = AdminManager::getById($admin->id);
        $admin_b = AdminManager::setInfo($admin_b, $data);
        $admin_b->save();
        return ApiResponse::makeResponse(true, "修改成功", ApiResponse::SUCCESS_CODE);
    }

    /*
     * 重置密码
     *
     * By TerryQi
     *
     * 2019-06-07
     */
    public function resetPassword(Request $request, $id)
    {
        $data = $request->all();
        if (is_numeric($id) !== true) {
            return ApiResponse::makeResponse(false, "合规校验失败，请检查参数", ApiResponse::INNER_ERROR);
        }
        $admin = AdminManager::getById($id);
        $admin_login = AdminLoginManager::resetPassword($admin->id);

        return ApiResponse::makeResponse(true, $admin_login, ApiResponse::SUCCESS_CODE);
    }


}