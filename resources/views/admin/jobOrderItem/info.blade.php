@extends('admin.layouts.app')

@section('content')
    <div class="layui-fluid">

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        工作任务详情
                    </div>
                    <div class="layui-card-body">
                        <table class="layui-table">
                            <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{$data->id}}</td>
                                <td>工作包</td>
                                <td>{{$data->job_order->name}}</td>
                                <td>物业公司</td>
                                <td>{{$data->man_company->name}}</td>
                                <td>在管项目</td>
                                <td>{{$data->bus_company->name}}</td>
                            </tr>
                            <tr>
                                <td>月工资</td>
                                <td>{{$data->job_order->monthly_pay}} 元</td>
                                <td>每日时长</td>
                                <td>{{$data->job_order->hours_per_day}} 小时</td>
                                <td>打卡状态</td>
                                <td>{{$data->is_finished_str}}</td>
                                <td>主管审核</td>
                                <td>{{$data->audit_status_str}}</td>
                            </tr>
                            <tr>
                                <td>计划工作时长</td>
                                <td>{{$data->plan_work_hours}} 小时</td>
                                <td>实际工作时长</td>
                                <td>{{$data->real_work_hours}} 小时</td>
                                <td>计划结算工资</td>
                                <td>{{$data->plan_settle_wage}} 元</td>
                                <td>实际结算工资</td>
                                <td>{{$data->real_settle_wage}} 元</td>
                            </tr>
                            <tr>
                                <td>奖金</td>
                                <td>{{$data->bonus_wage}} 元</td>
                                <td>补助</td>
                                <td>{{$data->subsidy_wage}} 元</td>
                                <td>罚款1</td>
                                <td>{{$data->fine1_wage}} 元</td>
                                <td>罚款2</td>
                                <td>{{$data->fine2_wage}} 元</td>
                            </tr>
                            <tr>
                                <td>工作日期</td>
                                <td>{{$data->work_at}}</td>
                                <td>员工</td>
                                <td>{{$data->worker->name}}</td>
                                <td colspan="4">
                                    @if($data->is_finished==\App\Components\Common\Utils::STATUS_VALUE_0)
                                        <button class="layui-btn layui-btn-sm"
                                                onclick="sub_clockIn(this,'{{$data->id}}')">
                                            代TA打卡
                                        </button>
                                    @endif
                                    @if($data->audit_status==\App\Components\Common\Utils::STATUS_VALUE_0)
                                        <button class="layui-btn layui-btn-sm"
                                                onclick="work_audit('工作考评-{{$data->id}}','{{URL::asset('admin/jobOrderItem/workAudit')}}?id={{$data->id}}',{{$data->id}})">
                                            工作考评
                                        </button>
                                    @endif
                                    <button class="layui-btn layui-btn-sm btn-refresh"
                                            onclick="refresh()">
                                        <i class="layui-icon layui-icon-refresh layuiadmin-button-btn"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">时间线索</div>
                    <div class="layui-card-body">
                        <ul class="layui-timeline">
                            <li class="layui-timeline-item">
                                <i class="layui-icon layui-timeline-axis"></i>
                                <div class="layui-timeline-content layui-text">
                                    <h3 class="layui-timeline-title">打卡时间
                                        / {{isset($data->finished_at)?$data->finished_at:'--'}}</h3>
                                    <div>
                                        <table class="layui-table">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    工作汇报：{{\App\Components\Common\Utils::isObjNull($data->work_report)?'--':$data->work_report}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </li>
                            <li class="layui-timeline-item">
                                <i class="layui-icon layui-timeline-axis"></i>
                                <div class="layui-timeline-content layui-text">
                                    <h3 class="layui-timeline-title">主管审核
                                        / {{isset($data->audit_at)?$data->audit_at:'--'}}</h3>
                                    <div>
                                        @if($data->audit_status != \App\Components\Common\Utils::COMMON_STATUS_0)
                                            <table class="layui-table">
                                                <tbody>
                                                <tr>
                                                    <td>审核人ID</td>
                                                    <td>{{isset($data->audit_company_user)?$data->audit_company_user->id:'--'}}</td>
                                                    <td>审核人</td>
                                                    <td>{{isset($data->audit_company_user)?$data->audit_company_user->name:'--'}}</td>
                                                    <td>审核人手机号</td>
                                                    <td>{{isset($data->audit_company_user)?$data->audit_company_user->phonenum:'--'}}</td>
                                                    <td>满意度</td>
                                                    <td>{{$data->satisfaction}} 分</td>
                                                </tr>
                                                <tr>
                                                    <td>审核备注</td>
                                                    <td colspan="7">
                                                        {{$data->audit_remark}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@include('vendor.ueditor.assets')

@section('script')
    <script type="text/javascript">

        //初始化layer
        var layer = null;
        var form = null;

        // 入口函数
        $(function () {
            //点击图片进行展示
            $(".img-pic").on("click", function (e) {
                layer.photos({
                    photos: {"data": [{"src": e.target.src}]}
                });
            });
        });

        //初始化模块
        layui.use(['index', 'layer', 'form', 'set', 'laypage'], function () {
            layer = layui.layer;        //初始化layer
            form = layui.form;

        });

        /*
         * 设置保单
         * 
         * By TerryQi
         * 
         * 2019-07-01
         */
        function work_audit(title, url, id) {
            console.log("edit url:" + url);

            //方式1：全屏打开
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);

            //方式2：固定窗口大小
            // var index = layer.open({
            //     type: 2,
            //     area: ['850px', '500px'],
            //     fixed: false,
            //     maxmin: true,
            //     title: title,
            //     content: url
            // });

            //方式3：新建tab页
            // parent.layui.index.openTabsPage(url, title);
        }


        /*
         * 页面刷新
         *
         * By TerryQi
         *
         */
        function refresh() {
            {{--location.replace('{{URL::asset('/admin/jobOrderWorker/info')}}?id={{$data->id}}');--}}
            reloadPage();
        }

        /*
         * 代Ta打卡
         *
         * By TerryQi
         *
         * 2019-07-05
         */
        function sub_clockIn(obj, id) {
            layer.confirm('确认要代TA打卡吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    _token: "{{ csrf_token() }}"
                }
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                ajaxRequest('{{URL::asset('/admin/jobOrderItem/subClockIn')}}/' + id, param, "GET", function (ret) {
                    if (ret.result) {
                        layer.msg('打卡成功');
                        $(".btn-refresh").click();
                    } else {
                        layer.msg(ret.message, {icon: 5, time: 2000});
                    }
                    layer.close(loadding_index);
                });
            });
        }


    </script>
@endsection