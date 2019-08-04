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

    }


    //生成工作包任务
    public function generateJobOrderItem(Request $request)
    {

        $week_num = Utils::getChiWeekNum(DateTool::getToday());

        $job_orders = JobOrderManager::getListByCon(['status' => '1', 'week_times' => $week_num], false);
        foreach ($job_orders as $job_order) {
            JobOrderWorkerManager::generateJobOrderItem($job_order->id);
        }

        return ApiResponse::makeResponse(true, "生成工作包任务", ApiResponse::SUCCESS_CODE);
    }

    /*
     * 加密接口
     *
     *
     */
    public function encryptCode(Request $request)
    {
        $data = $request->all();
        $code = $data['code'];

        Utils::processLog(__METHOD__, '', 'code:' . $code);
//        $encrypt_code = Crypt::encrypt($code, false);
        $encrypt_code = AES::encryptData($code, env('AES_KEY', ''));
        Utils::processLog(__METHOD__, '', 'encrypt_code:' . $encrypt_code);
        return ApiResponse::makeResponse(true, $encrypt_code, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 解密接口
     *
     *
     */
    public function decryptCode(Request $request)
    {
        $data = $request->all();
        $code = $data['decrypt_code'];

        $code = "TWyeuLqk0+x/ojffIOcVPw==";

        Utils::processLog(__METHOD__, '', 'code:' . $code);
        $decrypt_code = AES::decryptData($code, env('AES_KEY', ''));
        Utils::processLog(__METHOD__, '', 'decrypt_code:' . $decrypt_code);
        return ApiResponse::makeResponse(true, $decrypt_code, ApiResponse::SUCCESS_CODE);
    }


    //按照工作包生成工资单
    public function generatePayroll(Request $request)
    {
        $data = $request->all();

    }

    //发送未打卡的模板消息
    public function sendClockInNotifyMessage()
    {
        ScheduleManager::clockInNotifySchedule();
    }

    //发送考核提醒的模板消息
    public function sendAuditNotifyMessage()
    {
        ScheduleManager::auditNotifySchedule();
    }

    //进行业务导入
    public function importExcel()
    {
        ImportWorkerTaskManager::startImport(12);
    }


    //进行导出业务
    public function exportExcel()
    {
        ExportWorkerSalaryManager::startExport(11000);
    }

}

