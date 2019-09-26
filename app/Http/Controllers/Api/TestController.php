<?php

/**
 * Created by PhpStorm.
 * User:robot
 * Date: 2019-04-21 15:44:24
 * Time: 13:29
 */

namespace App\Http\Controllers\Api;


use App\Components\Common\AES;
use App\Components\BillManager;
use App\Components\Common\DateTool;
use App\Components\ExportWorkerSalaryManager;
use App\Components\GoodsManager;
use App\Components\ImportWorkerTaskManager;
use App\Components\JobOrderItemManager;
use App\Components\JobOrderManager;
use App\Components\JobOrderWorkerManager;
use App\Components\OrderManager;
use App\Components\Project;
use App\Components\Common\RequestValidator;
use App\Components\CityManager;
use App\Components\ScheduleManager;
use App\Components\ShopClerkManager;
use App\Components\ShopStoreManager;
use App\Components\UserAvaterManager;
use App\Components\Common\Utils;
use App\Components\VericodeManager;
use App\Components\Common\ApiResponse;

use Geohash\GeoHash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TestController
{

    //进行业务测试
    public function test(Request $request)
    {
        return ApiResponse::makeResponse(true, "这个是测试接口", ApiResponse::SUCCESS_CODE);
    }

}

