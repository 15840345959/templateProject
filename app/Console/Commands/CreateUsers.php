<?php

namespace App\Console\Commands;

use App\Components\Common\UserInfoGenerator;
use App\Components\UserLoginManager;
use App\Components\UserManager;
use App\Events\RegisterEasemobUserEvent;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

class CreateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:createUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        //开始进行用户创建
        echo "start create users \n";
        for ($i = 0; $i < 1000; $i++) {
            $nick_name = UserInfoGenerator::getNickName();
            $phonenum = UserInfoGenerator::getPhonenum();
            $avatar = UserInfoGenerator::getAvatar();
            echo "create user i:" . $i . " " . $nick_name . " " . $phonenum . " " . $avatar . " \n";
            $user = new User();
            $user->nick_name = $nick_name;
            $user->phonenum = $phonenum;
            $user->avatar = $avatar;
            $user->save();
            UserManager::setUserLoginTelCode($user, $phonenum, rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9));

            //注册环信
//            Event::fire(new RegisterEasemobUserEvent($user));

        }

        echo "end create users \n";
    }
}
