<?php

namespace App\Console;

use App\Components\Common\DateTool;
use App\Components\Common\Utils;
use App\Components\CouponManager;
use App\Components\JobOrderManager;
use App\Components\JobOrderWorkerManager;
use App\Components\ScheduleManager;
use App\Components\UserAvaterManager;
use App\Components\UserCouponManager;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();


        /*
         * 每天01:00生成工作包任务
         *
         * By TerryQi
         *
         * 2019-06-23
         */
        $schedule->call(function () {
            Utils::processLog(__METHOD__, '', "生成工作包任务 start at:" . time());
            $week_num = Utils::getChiWeekNum(DateTool::getToday());
            $job_orders = JobOrderManager::getListByCon(['status' => '1', 'week_times' => $week_num], false);
            foreach ($job_orders as $job_order) {
                JobOrderWorkerManager::generateJobOrderItem($job_order->id);
            }
            Utils::processLog(__METHOD__, '', "生成工作包任务 end at:" . time());
        })->dailyAt('01:00');


        /*
         * 每天17:00进行打卡提醒
         *
         * By TerryQi
         *
         * 2019-06-23
         */
        $schedule->call(function () {
            Utils::processLog(__METHOD__, '', "打卡提醒 start at:" . time());
            ScheduleManager::clockInNotifySchedule();
            Utils::processLog(__METHOD__, '', "打卡提醒 end at:" . time());
        })->dailyAt('17:00');

        /*
         * 每天18:00进行考核提醒
         *
         * By TerryQi
         *
         * 2019-06-23
         */
        $schedule->call(function () {
            Utils::processLog(__METHOD__, '', "考核提醒 start at:" . time());
            ScheduleManager::auditNotifySchedule();
            Utils::processLog(__METHOD__, '', "考核提醒 end at:" . time());
        })->dailyAt('18:00');


        /// 头像处理任务，每分钟处理15个，将现有存量的头像配置为七牛
        ///
        /// By TerryQi
        ///
        ///
        $schedule->call(function () {
            Utils::processLog(__METHOD__, '', "处理头像任务 start at:" . time());
            UserAvaterManager::uploadUserAvatarSechdule();
            Utils::processLog(__METHOD__, '', "处理头像任务 end at:" . time());
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
