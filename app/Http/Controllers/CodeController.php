<?php

/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/20
 * Time: 10:50
 */

namespace App\Http\Controllers;


use App\Components\AdManager;
use App\Components\QNManager;
use App\Components\Common\Utils;
use App\Components\Common\ApiResponse;
use App\Models\Ad;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;

class CodeController
{

    /*
    * 生成验证码
    *
    * By Auto CodeCreator
    *
    * 2019-04-21 15:58:05
    */
    public function captcha(Request $request)
    {
        $builder = new CaptchaBuilder(4);
        $builder->build(150, 50);
        $phrase = $builder->getPhrase();

        Utils::processLog(__METHOD__, '', 'phrase:' . json_encode($phrase));

        //把内容存入session
        $request->session()->put('milkcaptcha', $phrase);
        ob_clean(); //清除缓存

        return response($builder->output())->header('Content-type', 'image/jpeg'); //把验证码数据以jpeg图片的格式输出
    }
}

