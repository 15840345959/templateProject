<?php
/**
 * Created by PhpStorm.
 * User: Ada
 * Date: 2019/4/17
 * Time: 16:25
 */

namespace App\Http\Controllers\Api;


use App\Components\Common\ApiResponse;
use App\Components\Common\QNManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QNController extends Controller
{
    /**
     * 获取骑牛token
     *
     * By Ada
     *
     * 2019-04-17
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQiniuToken(Request $request)
    {
        $upToken = QNManager::uploadToken();
        return ApiResponse::makeResponse(true, $upToken, ApiResponse::SUCCESS_CODE);
    }


}