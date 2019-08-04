<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/27
 * Time: 10:25
 */

namespace App\Components;


class Project
{
    //七牛相关
    const QI_NIU_DOMAIN = 'http://twst.isart.me/';//连接

    //小程序账号
    const XCX_ACCOUNT_CONFIG = "wechat.mini_program";     //配置文件位置
    //支付账号
    const PAYMENT_ACCOUNT_CONFIG = "";        //配置文件位置

    //可以结算最少金额
    const CAN_SETTLE_SHOP_LEAST_MONEY = 1;

    //状态值
    const STATUS_VALUE_0 = '0';
    const STATUS_VALUE_1 = '1';
    const STATUS_VALUE_2 = '2';
    const STATUS_VALUE_3 = '3';
    const STATUS_VALUE_4 = '4';
    const STATUS_VALUE_5 = '5';

    //通用
    const COMMON_STATUS_VAL = ['0' => '失效', '1' => '有效'];
    const COMMON_STATUS_1 = '1';
    const COMMON_STATUS_0 = '0';

    const RECOMM_FLAG_VAL = ['0' => '未推荐', '1' => '已推荐'];
    const RECOMM_FLAG_1 = '1';
    const RECOMM_FLAG_0 = '0';

    const ACCOUNT_TYPE_TEL_PASSWD = "tel_password";       //手机号加密码
    const ACCOUNT_TYPE_VAL = ['xcx' => '小程序', 'fwh' => '公众号', 'tel_password' => '手机号密码', '手机号随机码' => 'tel_code'];
    //管理员角色
    const ADMIN_ROLE_VAL = ['0' => '超级管理员', '1' => '普通管理员'];

    const COMPANY_USER_TYPE_VAL = ['0' => '物业', '1' => '项目'];

    const COMPANY_USER_ROLE_VAL = ['0' => '物业管理员', '1' => '项目管理员'];

    //用户性别
    const USER_GENDER_VAL = ['0' => '保密', '1' => '男', '2' => '女'];

    //轮播图跳转类型
    const AD_TYPE_VAL = ['0' => '不跳转', '1' => '配置htm内容'];

    //规则协议类型
    const RULE_TW_TYPE = ['0' => '关于我们', '1' => '注册协议', '2' => '业务介绍'];
    //规则状态
    const RULE_TW_STATUS = ['0' => '在首页 ', '1' => '不在首页'];

    //工作包类型
    const JOB_ORDER_TYPE_VAL = ['0' => '内部工单', '1' => '开放工单'];
    const JOB_ORDER_GENDER_VAL = ['0' => '不限', '1' => '男', '2' => '女'];
    const JOB_ORDER_SENDER_TYPE_VAL = ['0' => '公司', '1' => '平台'];

    //接单人类型
    const JOB_ORDER_WORKER_TYPE_VAL = ['0' => '自主接单', '1' => '指派工单'];
    const JOB_ORDER_WORKER_INSURANCE_TYPE_VAL = ['0' => '未设置', '1' => '商业保险', '2' => '商业保险'];


    //工作项内容
    const JOB_ORDER_ITEM_FINISHED_VAL = ['0' => '未完成', '1' => '已完成'];
    //工作项审核状态
    const JOB_ORDER_ITEM_AUDIT_STATUS_VAL = ['0' => '未考核', '1' => '已考核'];


    //任务状态
    const IMPORT_WORKER_TASK_STATUS_VAL = ['0' => '未开始', '1' => '进行中', '2' => '已结束'];

    //工作状态
    const WORKER_COMPANY_WORK_STATUS_VAL = ['0' => '停工中', '1' => '工作中'];
    const WORKER_COMPANY_INSURANCE_NO_VAL = ['0' => '无保单', '1' => '有保单'];

    //员工状态
    const WORKER_IS_JOIN_INSURANCE_VAL = ['0' => '未参保', '1' => '已参保'];
    const WORKER_IS_JOIN_INSURANCE_TEXT_VAL = ['0' => '否', '1' => '是'];
    const WORKER_INSURANCE_TYPE_VAL = ['0' => '未知', '1' => '商业保险', '2' => '社会统筹'];

    const WORKER_IS_SPEC_VAL = ['0' => '无需关注', '1' => '需要关注'];


    const ALERT_RED_BG = "layui-bg-red";
    const ALERT_ORANGE_BG = "layui-bg-orange";
    const ALERT_GREEN_BG = "layui-bg-green";


    //小程序模板
    const XCX_CLOCK_IN_NOTIFY_TEMPLATE = "zCGCvBuRBovb54xL5fyFa6vVtebLSX4ReyztriYVp7Q";
    const XCX_AUDIT_NOTIFY_TEMPLATE = "UYZ1JM0CzfnLbnFRZGnP-BTEg8GPMCV0S0Bcmizf-Wc";

    //小程序页面路径
    const XCX_CLOCKIN_PAGE_PATH = "pages/index/index";       //打卡页面
    const XCX_AUDIT_PAGE_PATH = "pages/index/index";       //考核页面


}





