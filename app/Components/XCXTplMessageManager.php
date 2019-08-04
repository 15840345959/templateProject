<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Components\Common\Utils;
use Illuminate\Support\Facades\Log;
use Qiniu\Auth;

//微信模板消息
/*
 * By TerryQi
 *
 * 2018-11-15
 */

class XCXTplMessageManager
{

    /*
     * 小程序派发模板消息
     *
     * By TerryQi
     *
     * $app app对象
     * @template_id 模板id
     * @info 消息数组，形式为
     *
     * [
     *  "keyword1"=>keyword1,
     * "keyword2"=>keyword2,
     * "keyword3"=>keyword3
     *
     *  ]
     *
     * @user_id：用户id信息
     *
     * @return true/false       发送成功 or 失败
     */
    public static function sendMessage($app, $template_id, $user_id, $info, $page_path, $busi_name = null, $emphasis_keyword = null)
    {
        Utils::processLog(__METHOD__, '', " template_id:" . $template_id . " page_path:" . $page_path .
            " user_id:" . json_encode($user_id) . " info:" . json_encode($info) . " emphasis_keyword：" . $emphasis_keyword);

        //判断openid和是否剩余form_id
        $user_login = UserLoginManager::getListByCon(['user_id' => $user_id], false)->first();
        if (!$user_login) {
            return false;
        }

        $xcx_formId = XcxFormIdManager::getListByCon(['user_id' => $user_id, 'valid_num_bigger_than' => '0'], false)->first();
        if (!$xcx_formId) {
            return false;
        }

        $data = self::getContentByTemplatedId($template_id, $info);

        //如果data为空，代表没有模板，则返回
        if ($data == null) {
            return false;       //返回失败
        }
        //2019-01-12其中是消息数
        $message_data = [
            'touser' => $user_login->ve_value1,
            'template_id' => $template_id,
            'page' => $page_path,
            'form_id' => $xcx_formId->form_id,
            'data' => $data,
        ];
        //关键词
        if (!Utils::isObjNull($emphasis_keyword)) {
            $message_data['emphasis_keyword'] = $emphasis_keyword;
        }
        $result = $app->template_message->send($message_data);
        Utils::processLog(__METHOD__, '' . " result:" . json_encode($result));

        //进行formId数的调整
        XcxFormIdManager::setNum($xcx_formId->id, 'valid_num', -1);
        XcxFormIdManager::setNum($xcx_formId->id, 'used_num', 1);

        return true;
    }


    /*
     * 根据模板id获取keywords相关信息
     *
     * By TerryQi
     *
     * 2018-11-15
     *
     */
    private static function getContentByTemplatedId($templated_id, $info)
    {
        //$templated_id
        switch ($templated_id) {
            //根据模板消息自行定义
            case Project::XCX_CLOCK_IN_NOTIFY_TEMPLATE:
                return [
                    'keyword1' => $info["keyword1"],
                    'keyword2' => $info["keyword2"],
                    'keyword3' => $info["keyword3"],
                    'keyword4' => $info["keyword4"],
                    'keyword5' => $info["keyword5"],
                ];
            default:
                return null;
        }
    }


}