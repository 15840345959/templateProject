@extends('admin.layouts.app')

@section('content')

    <style>

    </style>

    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">

            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        工作包任务数（个）
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$page_data['today_job_order_item_num']}}</p>
                        <p>
                            工作包任务总数
                            <span class="layuiadmin-span-color">{{$page_data['total_job_order_item_num']}}<i
                                        class="layui-inline layui-icon layui-icon-flag"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        工作时长 预计/实际（小时）
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">

                        <p class="layuiadmin-big-font">{{$page_data['today_job_order_item_plan_work_hours']}}
                            / {{$page_data['today_job_order_item_real_work_hours']}}</p>
                        <p>
                            工作总时长
                            <span class="layuiadmin-span-color">{{$page_data['total_job_order_item_plan_work_hours']}}
                                / {{$page_data['total_job_order_item_real_work_hours']}} <i
                                        class="layui-inline layui-icon layui-icon-component"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        工作报酬 实际（元）
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list"
                         title="{{$page_data['today_job_order_item_plan_settle_wage']}} / {{$page_data['today_job_order_item_real_settle_wage']}}">

                        <p class="layuiadmin-big-font">{{$page_data['today_job_order_item_real_settle_wage']}}</p>
                        <p>
                            工作总报酬
                            <span class="layuiadmin-span-color">{{$page_data['total_job_order_item_plan_settle_wage']}}
                                / {{$page_data['total_job_order_item_real_settle_wage']}} <i
                                        class="layui-inline layui-icon layui-icon-dollar"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        员工数（个）
                        <span class="layui-badge layui-bg-green layuiadmin-badge">总</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">

                        <p class="layuiadmin-big-font">{{$page_data['total_worker_num']}}</p>
                        <p>
                            物业 / 项目
                            <span class="layuiadmin-span-color">{{$page_data['total_man_company_num']}}
                                / {{$page_data['total_bus_company_num']}}<i
                                        class="layui-inline layui-icon layui-icon-flag"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        工作包趋势评价
                        <div class="layui-btn-group layuiadmin-btn-group">
                            <span id="job_order_item_trend_days_num_7"
                                  class="layui-btn layui-btn-xs job_order_item_trend_days_num"
                                  onclick="clickJobOrderItemTrendDaysNum(7,this)">7天</span>
                            <span id="job_order_item_trend_days_num_15"
                                  class="layui-btn layui-btn-primary layui-btn-xs job_order_item_trend_days_num"
                                  onclick="clickJobOrderItemTrendDaysNum(15)">15天</span>
                            <span id="job_order_item_trend_days_num_30"
                                  class="layui-btn layui-btn-primary layui-btn-xs job_order_item_trend_days_num"
                                  onclick="clickJobOrderItemTrendDaysNum(30)">30天</span>
                            <span id="job_order_item_trend_days_num_90"
                                  class="layui-btn layui-btn-primary layui-btn-xs job_order_item_trend_days_num"
                                  onclick="clickJobOrderItemTrendDaysNum(90)">90天</span>
                            <span id="job_order_item_trend_days_num_180"
                                  class="layui-btn layui-btn-primary layui-btn-xs job_order_item_trend_days_num"
                                  onclick="clickJobOrderItemTrendDaysNum(180)">180天</span>
                            <span id="job_order_item_trend_days_num_365"
                                  class="layui-btn layui-btn-primary layui-btn-xs job_order_item_trend_days_num"
                                  onclick="clickJobOrderItemTrendDaysNum(365)">365天</span>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-sm8">
                                <div class="layui-carousel layadmin-carousel layadmin-dataview" data-anim="fade">
                                    <div id="job_order_item_trend_div1"
                                         style="width: 100%;height: 320px;">

                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-sm4">
                                <div class="layui-card">
                                    <div class="layui-card-body layadmin-takerates">
                                        <div class="layui-progress" lay-showPercent="yes"
                                             lay-filter="LAY-orders-total-num">
                                            <h3 class="text-oneline">计划工作时长（小时）</h3>
                                            <div class="layui-progress-bar" lay-percent="0"></div>
                                        </div>
                                        <div class="layui-progress" lay-showPercent="yes"
                                             lay-filter="LAY-orders-total-cash-fee">
                                            <h3 class="text-oneline">实际工作时长（小时）</h3>
                                            <div class="layui-progress-bar layui-bg-blue" lay-percent="0"></div>
                                        </div>
                                        <div class="layui-progress" lay-showPercent="yes"
                                             lay-filter="LAY-orders-total-used-cash-fee">
                                            <h3 class="text-oneline">计划报酬金额（元）</h3>
                                            <div class="layui-progress-bar layui-bg-orange" lay-percent="0"></div>
                                        </div>
                                        <div class="layui-progress" lay-showPercent="yes"
                                             lay-filter="LAY-orders-total-score-fee">
                                            <h3 class="text-oneline">实际报酬金额（元）</h3>
                                            <div class="layui-progress-bar layui-bg-green" lay-percent="0"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-row">
                            <div id="job_order_item_trend_div2" style="width: 100%;height: 320px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="layui-col-sm12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        未设置保单人次数，共计 {{$page_data['insurance_no_is_null_job_order_workers']->total()}} 户，最近15条未设置保单信息
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <table class="layui-table text-c" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-c" width="10">ID</th>
                                    <th class="text-c" width="40">工作包</th>
                                    <th class="text-c" width="40">在管项目</th>
                                    <th class="text-c" width="20">员工</th>
                                    <th class="text-c" width="30">保单</th>
                                    <th class="text-c" width="30">接包时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($page_data['insurance_no_is_null_job_order_workers'] as $insurance_no_is_null_job_order_worker)
                                    <tr class="{{$insurance_no_is_null_job_order_worker->alert_level}}"
                                        title="{{$insurance_no_is_null_job_order_worker->alert_str}}">
                                        <td>{{$insurance_no_is_null_job_order_worker->id}}</td>
                                        <td>
                                            <div class="text-oneline">
                                                {{$insurance_no_is_null_job_order_worker->job_order->name}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-oneline">
                                                {{$insurance_no_is_null_job_order_worker->job_order->bus_company->name}}
                                            </div>
                                        </td>
                                        <td>
                                            {{$insurance_no_is_null_job_order_worker->worker->name}}
                                        </td>
                                        <td>
                                            {{isset($insurance_no_is_null_job_order_worker->insurance_no) ? $insurance_no_is_null_job_order_worker->insurance_no:'--'}}
                                        </td>
                                        <td>
                                            {{$insurance_no_is_null_job_order_worker->created_at}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="layui-col-sm12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        预警合同，共计 {{$page_data['alert_company_contracts']->total()}} 份，最近15条即将过期的合同
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <table class="layui-table text-c" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-c" width="20">ID</th>
                                    <th class="text-c" width="60">合同名</th>
                                    <th class="text-c" width="60">物业公司</th>
                                    <th class="text-c" width="60">在管项目</th>
                                    <th class="text-c" width="20">有效期</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($page_data['alert_company_contracts'] as $alert_company_contract)
                                    <tr class="{{$alert_company_contract->alert_level}}"
                                        title="{{$alert_company_contract->alert_str}}">
                                        <td>{{$alert_company_contract->id}}</td>
                                        <td>
                                            <div class="text-oneline">
                                                {{$alert_company_contract->name}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-oneline">
                                                {{$alert_company_contract->man_company->name}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-oneline">
                                                {{$alert_company_contract->bus_company->name}}
                                            </div>
                                        </td>
                                        <td>{{$alert_company_contract->valid_start_time}}
                                            至 {{$alert_company_contract->valid_end_time}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

        //初始化layer
        var layer = null;
        var form = null;
        var element = null;

        // 入口函数
        $(function () {
            //点击图片进行展示
            $(".img-pic").on("click", function (e) {
                layer.photos({
                    photos: {"data": [{"src": e.target.src}]}
                });
            });

            //描绘交易和gmv趋势图
            clickJobOrderItemTrendDaysNum(7);
        });


        //初始化模块
        layui.use(['index', 'layer', 'form', 'set', 'laypage', 'element'], function () {
            layer = layui.layer;        //初始化layer
            form = layui.form;
            element = layui.element;

        });

        /*
         * 页面刷新
         *
         * By TerryQi
         *
         */
        function refresh() {
            $('#search_form')[0].reset();
            {{--location.replace('{{URL::asset('/admin/city/index')}}');--}}
            reloadPage();
        }


        //点击交易量gmv趋势
        function clickJobOrderItemTrendDaysNum(days_num) {
            $(".job_order_item_trend_days_num").addClass('layui-btn-primary');
            $("#job_order_item_trend_days_num_" + days_num).removeClass('layui-btn-primary');
            getJobOrderItemTrend(days_num);
        }

        //获取交易与gwv曲线
        function getJobOrderItemTrend(days_num) {
            ajaxRequest('{{URL::asset('/admin/overview/jobOrderItemTrend')}}', {"days_num": days_num}, "GET", function (ret) {
                    if (ret.result === true) {
                        var msgObj = ret.ret;       //返回值

                        var date_array = [];
                        var plan_work_hours_array = [];
                        var real_work_hours_array = [];
                        var plan_settle_wage_array = [];
                        var real_settle_wage_array = [];
                        var legend_data_array = ['计划工作时长', '实际工作时长', '计划报酬金额', '实际报酬金额'];

                        var data_length = msgObj.plan_work_hours_arr.length;        //获取数据长度
                        //配置数据
                        for (var i = 0; i < data_length; i++) {
                            date_array.push(msgObj.plan_work_hours_arr[i].date);
                            plan_work_hours_array.push(msgObj.plan_work_hours_arr[i].value);
                            real_work_hours_array.push(msgObj.real_work_hours_arr[i].value);
                            plan_settle_wage_array.push(msgObj.plan_settle_wage_arr[i].value);
                            real_settle_wage_array.push(msgObj.real_settle_wage_arr[i].value);
                        }

                        //折线堆叠图
                        var job_order_item_trend_div1_echart = echarts.init(document.getElementById("job_order_item_trend_div1"))
                        job_order_item_trend_div1_echart.showLoading({
                            type: 'default'
                        });
                        //折线图的option
                        var job_order_item_trend_div1_echart_option = {
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross',
                                    crossStyle: {
                                        color: '#999'
                                    }
                                }
                            },
                            legend: {
                                data: [legend_data_array[0], legend_data_array[1]]
                            },
                            xAxis: [
                                {
                                    type: 'category',
                                    data: date_array,
                                    axisPointer: {
                                        type: 'shadow'
                                    }
                                }
                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    name: legend_data_array[0],
                                    axisLabel: {
                                        formatter: '小时'
                                    },
                                    minInterval: 1
                                },
                                {
                                    type: 'value',
                                    name: legend_data_array[1],
                                    axisLabel: {
                                        formatter: '小时'
                                    },
                                    minInterval: 1
                                }
                            ],
                            series: [
                                {
                                    name: legend_data_array[0],
                                    type: 'line',
                                    data: plan_work_hours_array
                                },
                                {
                                    name: legend_data_array[1],
                                    type: 'line',
                                    data: real_work_hours_array,
                                    yAxisIndex: 1
                                },
                            ]
                        }

                        consoledebug.log("options:" + JSON.stringify(job_order_item_trend_div1_echart_option));
                        job_order_item_trend_div1_echart.setOption(job_order_item_trend_div1_echart_option)
                        job_order_item_trend_div1_echart.hideLoading();


                        //柱状图
                        //折线堆叠图
                        var job_order_item_trend_div2_echart = echarts.init(document.getElementById("job_order_item_trend_div2"))
                        job_order_item_trend_div2_echart.showLoading({
                            type: 'default'
                        });
                        //折线图的option
                        var job_order_item_trend_div2_echart_option = {
                            tooltip: {
                                trigger: 'axis',
                                // formatter: "{b} : {c} 笔"
                            },
                            legend: {
                                data: [legend_data_array[2], legend_data_array[3]]
                            },
                            xAxis: {
                                type: 'category',
                                data: date_array
                            },
                            yAxis: [
                                {
                                    type: 'value',
                                    axisLabel: {
                                        formatter: '元'
                                    }
                                }, {
                                    type: 'value',
                                    axisLabel: {
                                        formatter: '元'
                                    }
                                }
                            ],
                            series: [{
                                name: legend_data_array[2],
                                data: plan_settle_wage_array,
                                type: 'bar'
                            }, {
                                name: legend_data_array[3],
                                data: real_settle_wage_array,
                                type: 'bar'
                            }]
                        }

                        consoledebug.log("options:" + JSON.stringify(job_order_item_trend_div2_echart_option));
                        job_order_item_trend_div2_echart.setOption(job_order_item_trend_div2_echart_option)
                        job_order_item_trend_div2_echart.hideLoading();

                        //设置百分比
                        element.progress('LAY-orders-total-num', msgObj.total_plan_work_hours);
                        element.progress('LAY-orders-total-cash-fee', msgObj.total_real_work_hours);
                        element.progress('LAY-orders-total-score-fee', msgObj.total_plan_settle_wage);
                        element.progress('LAY-orders-total-used-cash-fee', msgObj.total_real_settle_wage);

                    }
                    else {

                    }
                }
            )
        }

    </script>
@endsection