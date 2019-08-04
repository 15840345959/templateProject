<?php


namespace App\Http\Controllers\Admin;

use App\Components\BusCompanyManager;
use App\Components\Common\ApiResponse;
use App\Components\Common\DateTool;
use App\Components\Common\Utils;
use App\Components\CompanyContractManager;
use App\Components\JobOrderItemManager;
use App\Components\JobOrderManager;
use App\Components\JobOrderWorkerManager;
use App\Components\ManCompanyManager;
use App\Components\OrderManager;
use App\Components\ShopClerkManager;
use App\Components\ShopStoreManager;
use App\Components\UserManager;
use App\Components\CompanyWorkerManager;
use App\Components\WorkerManager;
use App\Http\Controllers\Controller;
use App\Models\JobOrderItem;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OverviewController extends Controller
{
    //首页
    public function index(Request $request)
    {
        $data = $request->all();
        //获取基本数据信息
        $today = DateTool::getToday();
        //工作包任务数量
        $today_job_order_item_num = JobOrderItemManager::getListByCon(['work_at' => $today, 'status' => '1'], false)->count();
        $total_job_order_item_num = JobOrderItemManager::getListByCon(['status' => '1'], false)->count();

        //工作包任务的工作时长管理
        $today_job_order_item_plan_work_hours = JobOrderItemManager::getListByCon(['work_at' => $today, 'status' => '1'], false)->sum('plan_work_hours');
        $today_job_order_item_real_work_hours = JobOrderItemManager::getListByCon(['work_at' => $today, 'status' => '1'], false)->sum('real_work_hours');
        $total_job_order_item_plan_work_hours = JobOrderItemManager::getListByCon(['status' => '1'], false)->sum('plan_work_hours');
        $total_job_order_item_real_work_hours = JobOrderItemManager::getListByCon(['status' => '1'], false)->sum('real_work_hours');

        //工作包任务的工资管理
        $today_job_order_item_plan_settle_wage = JobOrderItemManager::getListByCon(['work_at' => $today, 'status' => '1'], false)->sum('plan_settle_wage');
        $today_job_order_item_real_settle_wage = JobOrderItemManager::getListByCon(['work_at' => $today, 'status' => '1'], false)->sum('real_settle_wage');
        $total_job_order_item_plan_settle_wage = JobOrderItemManager::getListByCon(['status' => '1'], false)->sum('plan_settle_wage');
        $total_job_order_item_real_settle_wage = JobOrderItemManager::getListByCon(['status' => '1'], false)->sum('real_settle_wage');

        //在管项目数、物业公司数、员工数
        $total_worker_num = WorkerManager::getListByCon(['status' => '1'], false)->count();
        $total_man_company_num = ManCompanyManager::getListByCon(['status' => '1'], false)->count();
        $total_bus_company_num = BusCompanyManager::getListByCon(['status' => '1'], false)->count();

        //没有设置保单号的员工
        $insurance_no_is_null_job_order_workers = JobOrderWorkerManager::getListByCon(['audit_status' => '1', 'status' => '1', 'insurance_no_is_null' => true], true);
        foreach ($insurance_no_is_null_job_order_workers as $insurance_no_is_null_job_order_worker) {
            $insurance_no_is_null_job_order_worker = JobOrderWorkerManager::getInfoByLevel($insurance_no_is_null_job_order_worker, '01');
        }

        //即将到期的合同
        $alert_company_contracts = CompanyContractManager::getListByCon(['status' => '1', 'valid_end_time_after' => DateTool::dateAdd('D', 30, DateTool::getToday())], true);
        foreach ($alert_company_contracts as $alert_company_contract) {
            $alert_company_contract = CompanyContractManager::getInfoByLevel($alert_company_contract, '01');
        }

        $page_data = [
            'today_job_order_item_num' => round($today_job_order_item_num),
            'total_job_order_item_num' => round($total_job_order_item_num),

            'today_job_order_item_plan_settle_wage' => round($today_job_order_item_plan_settle_wage, 2),
            'today_job_order_item_real_settle_wage' => round($today_job_order_item_real_settle_wage, 2),
            'total_job_order_item_plan_settle_wage' => round($total_job_order_item_plan_settle_wage, 2),
            'total_job_order_item_real_settle_wage' => round($total_job_order_item_real_settle_wage, 2),

            'today_job_order_item_plan_work_hours' => round($today_job_order_item_plan_work_hours, 2),
            'today_job_order_item_real_work_hours' => round($today_job_order_item_real_work_hours, 2),
            'total_job_order_item_plan_work_hours' => round($total_job_order_item_plan_work_hours, 2),
            'total_job_order_item_real_work_hours' => round($total_job_order_item_real_work_hours, 2),

            'total_worker_num' => round($total_worker_num),
            'total_man_company_num' => round($total_man_company_num),
            'total_bus_company_num' => round($total_bus_company_num),

            'insurance_no_is_null_job_order_workers' => $insurance_no_is_null_job_order_workers,
            'alert_company_contracts' => $alert_company_contracts
        ];

        return view('admin.overview.index', ['page_data' => $page_data]);
    }


    /*
     * 工作包趋势
     *
     * By TerryQi
     *
     * 2019-06-24
     */
    public function jobOrderItemTrend(Request $request)
    {
        $data = $request->all();
        $days_num = 7;     //默认是按周的活动
        $start_at = null;
        $end_at = Carbon::now()->addDay(1)->toDateString(); //获取到下一天，因为end_at时间为2018-11-28 00:00:00这种形式
        //如果存在days_num，代表传入了日期间隔
        if (array_key_exists('days_num', $data) && !Utils::isObjNull($data['days_num']) && is_numeric($data['days_num'])) {
            $days_num = intval($data['days_num']);
        }
        $start_at = Carbon::now()->subDay($days_num)->toDateString();

        //计划工作时长趋势
        $plan_work_hours_arr = JobOrderItem::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)
            ->where('status', '=', '1')
            ->groupBy('date')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('sum(plan_work_hours) as value')
            ])
            ->toArray();
        $plan_work_hours_arr = Utils::replZero($plan_work_hours_arr, $start_at, $end_at);

        $total_plan_work_hours = JobOrderItem::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)->where('status', '=', '1')->sum('plan_work_hours');

        //实际工作时长趋势
        $real_work_hours_arr = JobOrderItem::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)
            ->where('status', '=', '1')
            ->groupBy('date')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('sum(real_work_hours) as value')
            ])
            ->toArray();
        $real_work_hours_arr = Utils::replZero($real_work_hours_arr, $start_at, $end_at);

        $total_real_work_hours = JobOrderItem::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)->where('status', '=', '1')->sum('real_work_hours');

        //计划工作报酬趋势
        $plan_settle_wage_arr = JobOrderItem::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)
            ->where('status', '=', '1')
            ->groupBy('date')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('sum(plan_settle_wage) as value')
            ])
            ->toArray();
        $plan_settle_wage_arr = Utils::replZero($plan_settle_wage_arr, $start_at, $end_at);

        $total_plan_settle_wage = JobOrderItem::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)->where('status', '=', '1')->sum('plan_settle_wage');

        //实际工作报酬趋势
        $real_settle_wage_arr = JobOrderItem::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)
            ->where('status', '=', '1')
            ->groupBy('date')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('sum(real_settle_wage) as value')
            ])
            ->toArray();
        $real_settle_wage_arr = Utils::replZero($real_settle_wage_arr, $start_at, $end_at);

        $total_real_settle_wage = JobOrderItem::whereDate('created_at', '>=', $start_at)
            ->whereDate('created_at', '<=', $end_at)->where('status', '=', '1')->sum('real_settle_wage');

        $page_data = [
            'plan_work_hours_arr' => $plan_work_hours_arr,
            'total_plan_work_hours' => round($total_plan_work_hours, 2),
            'real_work_hours_arr' => $real_work_hours_arr,
            'total_real_work_hours' => round($total_real_work_hours, 2),
            'plan_settle_wage_arr' => $plan_settle_wage_arr,
            'total_plan_settle_wage' => round($total_plan_settle_wage, 2),
            'real_settle_wage_arr' => $real_settle_wage_arr,
            'total_real_settle_wage' => round($total_real_settle_wage, 2),
        ];

        return ApiResponse::makeResponse(true, $page_data, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 物业公司报表
     *
     * By TerryQi
     *
     * 2019-06-24
     */
    public function manCompanyStmt(Request $request)
    {
        $data = $request->all();
        Utils::processLog(__METHOD__, 'data', json_encode($data));
        $days_num = 7;     //默认是按周的活动
        $start_at = null;
        $man_company_id = null;
        $search_word = null;

        $end_at = Carbon::now()->addDay(1)->toDateString(); //获取到下一天，因为end_at时间为2018-11-28 00:00:00这种形式
        $start_at = Carbon::now()->subDay($days_num)->toDateString();
        //是否传入start_at和end_at
        if (array_key_exists('start_at', $data) && !Utils::isObjNull($data['start_at'])) {
            $start_at = $data['start_at'];
        }
        if (array_key_exists('end_at', $data) && !Utils::isObjNull($data['end_at'])) {
            $end_at = $data['end_at'];
        }
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('man_company_id', $data) && !Utils::isObjNull($data['man_company_id'])) {
            $man_company_id = $data['man_company_id'];
        }

        $con_arr = array(
            'man_company_id' => $man_company_id,
            'search_word' => $search_word
        );

        $man_companys = ManCompanyManager::getListByCon($con_arr, true);
        foreach ($man_companys as $man_company) {
            $man_company->bus_company_num = BusCompanyManager::getListByCon(['man_company_id' => $man_company->id, 'status' => '1'], false)->count();
            $man_company->worker_num = CompanyWorkerManager::getListByCon(['man_company_id' => $man_company->id, 'status' => '1'], false)->count();
            $man_company->job_order_item_num = JobOrderItem::where('man_company_id', '=', $man_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->count();
            $man_company->plan_work_hours = JobOrderItem::where('man_company_id', '=', $man_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->sum('plan_work_hours');
            $man_company->plan_work_hours = round($man_company->plan_work_hours, 2);
            $man_company->real_work_hours = JobOrderItem::where('man_company_id', '=', $man_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->sum('real_work_hours');
            $man_company->real_work_hours = round($man_company->real_work_hours, 2);
            $man_company->plan_settle_wage = JobOrderItem::where('man_company_id', '=', $man_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->sum('plan_settle_wage');
            $man_company->plan_settle_wage = round($man_company->plan_settle_wage, 2);
            $man_company->real_settle_wage = JobOrderItem::where('man_company_id', '=', $man_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->sum('real_settle_wage');
            $man_company->real_settle_wage = round($man_company->real_settle_wage, 2);
        }

        return view('admin.overview.manCompanyStmt', ['datas' => $man_companys, 'start_at' => $start_at, 'end_at' => $end_at, 'con_arr' => $con_arr]);
    }


    /*
     * 在管项目报表
     *
     * By TerryQi
     *
     * 2019-06-24
     */
    public function busCompanyStmt(Request $request)
    {
        $data = $request->all();
        Utils::processLog(__METHOD__, 'data', json_encode($data));
        $days_num = 7;     //默认是按周的活动
        $start_at = null;
        $bus_company_id = null;
        $search_word = null;

        $end_at = Carbon::now()->addDay(1)->toDateString(); //获取到下一天，因为end_at时间为2018-11-28 00:00:00这种形式
        $start_at = Carbon::now()->subDay($days_num)->toDateString();
        //是否传入start_at和end_at
        if (array_key_exists('start_at', $data) && !Utils::isObjNull($data['start_at'])) {
            $start_at = $data['start_at'];
        }
        if (array_key_exists('end_at', $data) && !Utils::isObjNull($data['end_at'])) {
            $end_at = $data['end_at'];
        }
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('bus_company_id', $data) && !Utils::isObjNull($data['bus_company_id'])) {
            $bus_company_id = $data['bus_company_id'];
        }

        $con_arr = array(
            'bus_company_id' => $bus_company_id,
            'search_word' => $search_word
        );

        $bus_companys = BusCompanyManager::getListByCon($con_arr, true);
        foreach ($bus_companys as $bus_company) {
            $bus_company = BusCompanyManager::getInfoByLevel($bus_company, '0');
            $bus_company->worker_num = CompanyWorkerManager::getListByCon(['bus_company_id' => $bus_company->id, 'status' => '1'], false)->count();
            $bus_company->job_order_item_num = JobOrderItem::where('bus_company_id', '=', $bus_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->count();
            $bus_company->plan_work_hours = JobOrderItem::where('bus_company_id', '=', $bus_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->sum('plan_work_hours');
            $bus_company->plan_work_hours = round($bus_company->plan_work_hours, 2);
            $bus_company->real_work_hours = JobOrderItem::where('bus_company_id', '=', $bus_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->sum('real_work_hours');
            $bus_company->real_work_hours = round($bus_company->real_work_hours, 2);
            $bus_company->plan_settle_wage = JobOrderItem::where('bus_company_id', '=', $bus_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->sum('plan_settle_wage');
            $bus_company->plan_settle_wage = round($bus_company->plan_settle_wage, 2);
            $bus_company->real_settle_wage = JobOrderItem::where('bus_company_id', '=', $bus_company->id)->where('status', '=', '1')
                ->whereDate('created_at', '>=', $start_at)->whereDate('created_at', '<=', $end_at)->sum('real_settle_wage');
            $bus_company->real_settle_wage = round($bus_company->real_settle_wage, 2);
        }

        return view('admin.overview.busCompanyStmt', ['datas' => $bus_companys, 'start_at' => $start_at, 'end_at' => $end_at, 'con_arr' => $con_arr]);
    }

}
