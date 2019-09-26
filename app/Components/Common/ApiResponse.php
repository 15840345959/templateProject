<?php
/**
 * File_Name:ApiResponse.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 14:37
 */

namespace App\Components\Common;

class ApiResponse
{
    /* 响应码表 */

    // 通用响应码
    const SUCCESS_CODE = 200;       //操作成功
    const TOKEN_LOST = 401;         //未授权，一般未授权需要踢到登录页面，重新登录
    const USER_ID_LOST = 401;         //未授权，一般未授权需要踢到登录页面，重新登录
    const TOKEN_ERROR = 403;        //禁止访问
    const INNER_ERROR = 500;        //内部错误

    const MISSING_PARAM = 901;      //缺少参数
    const UNKNOW_ERROR = 999;         //未知错误


    //登录鉴权相关
    const NEED_PHONENUM_PASSWORD_CAPTCHA_CODE = 121;
    const CAPTCHA_CODE_ERROR = 122;
    const ACCOUNT_INVALID = 123;
    const PHONENUM_ERROR = 124;
    const PASSWORD_ERROR = 125;


    /* 映射错误信息 */
    public static $errorMessage = [
        self::SUCCESS_CODE => '操作成功',

        self::UNKNOW_ERROR => '未知错误',
        self::MISSING_PARAM => '缺少参数',
        self::INNER_ERROR => '逻辑错误',

        self::TOKEN_LOST => '缺少token或userid',
        self::TOKEN_ERROR => 'token校验失败',
        self::USER_ID_LOST => '缺少token或userid',

        self::NEED_PHONENUM_PASSWORD_CAPTCHA_CODE => '请输入手机号、密码、验证码',
        self::CAPTCHA_CODE_ERROR => '验证码错误',
        self::ACCOUNT_INVALID => '账号已经禁用',
        self::PHONENUM_ERROR => '手机号错误',
        self::PASSWORD_ERROR => '密码错误',
    ];

    /* 映射错误信息 */

    public static function makeResponse($result, $ret, $code, $message = null)
    {
        $respones = [];
        $respones['code'] = $code;

        if ($result === true) {
            $respones['result'] = true;
            $respones['ret'] = $ret;
            if (isset($message)) {
                $respones['message'] = $message;
            } else {
                $respones['message'] = self::$errorMessage[$code];
            }
        } else {
            $respones['result'] = false;
            $respones['ret'] = $ret;
            if (isset($message)) {
                $respones['message'] = $message;
            } else {
                if (array_key_exists($code, self::$errorMessage)) {
                    $respones['message'] = self::$errorMessage[$code];
                } else {
                    $respones['message'] = 'undefind error code';
                }
            }
        }

        Utils::ResponseLog(__METHOD__, $respones);
        return response()->json($respones);
    }
}
