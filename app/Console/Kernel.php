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
