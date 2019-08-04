@extends('company.layouts.app')

@section('content')
    <div class="layui-fluid">

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        工作包详情
                    </div>
                    <div class="layui-card-body">
                        <table class="layui-table">
                            <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{$data->id}}</td>
                                <td>单号</td>
                                <td>{{$data->job_order->trade_no}}</td>
                                <td>任务名</td>
                                <td>{{$data->job_order->name}}</td>
                                <td>类型</td>
                                <td>{{$data->job_order->type_str}}</td>
                            </tr>
                            <tr>
                                <td>物业公司</td>
                                <td>{{$data->job_order->man_company->name}}</td>
                                <td>在管项目</td>
                                <td>{{$data->job_order->bus_company->name}}</td>
                                <td>工作</td>
                                <td>{{$data->job_order->job->name}}</td>
                                <td>月工资</td>
                                <td>{{$data->job_order->monthly_pay}} 元</td>
                            </tr>
                            <tr>
                                <td>姓名</td>
                                <td>{{$data->worker->name}}</td>
                                <td>电话</td>
                                <td>{{$data->worker->phonenum}}</td>
                                <td>年龄</td>
                                <td>{{$data->worker->age}} 岁</td>
                                <td>保单号</td>
                                <td>{{isset($data->insurance_no) ? $data->insurance_no:'--'}}</td>
                            </tr>
                            <tr>
                                <td>审核状态</td>
                                <td>{{$data->audit_status_str}}</td>
                                <td>指派方式</td>
                                <td>{{$data->type_str}}</td>
                                <td colspan="4">
                                    <button class="layui-btn layui-btn-sm"
                                            onclick="set_insurance_no('设置保单号-{{$data->id}}','{{URL::asset('company/role1/jobOrderWorker/setInsuranceNo')}}?id={{$data->id}}',{{$data->id}})">
                                        设置保单
                                    </button>
                                    <button class="layui-btn layui-btn-sm"
                                            onclick="detail_item('任务明细-{{$data->id}}','{{URL::asset('company/role1/jobOrderItem/index')}}?job_order_worker_id={{$data->id}}',{{$data->id}})">
                                        任务明细
                                    </button>
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
                                    <h3 class="layui-timeline-title">申请时间 / {{$data->created_at}}</h3>
                                    <div>
                                        <table class="layui-table">
                                            <tbody>
                                            <tr>
                                                <td>申请ID</td>
                                                <td>{{$data->id}}</td>
                                                <td>姓名</td>
                                                <td>{{$data->worker->name}}</td>
                                                <td>电话</td>
                                                <td>{{$data->worker->phonenum}}</td>
                                                <td>工作</td>
                                                <td>{{$data->job_order->job->name}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </li>
                            <li class="layui-timeline-item">
                                <i class="layui-icon layui-timeline-axis"></i>
                                <div class="layui-timeline-content layui-text">
                                    <h3 class="layui-timeline-title">审核时间
                                        / {{isset($data->audit_at)?$data->audit_at:'--'}}</h3>
                                    <div>
                                        @if($data->audit_status != \App\Components\Common\Utils::COMMON_STATUS_0)
                                            <table class="layui-table">
                                                <tbody>
                                                <tr>
                                                    <td>审核人ID</td>
                                                    <td>{{isset($data->audit_by)?$data->audit_by->id:'--'}}</td>
                                                    <td>审核人</td>
                                                    <td>{{isset($data->audit_by)?$data->audit_by->name:'--'}}</td>
                                                    <td>审核人手机号</td>
                                                    <td>{{isset($data->audit_by)?$data->audit_by->phonenum:'--'}}</td>
                                                    <td>备注</td>
                                                    <td>{{isset($data->remark)?$data->remark:'--'}}</td>
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
        function set_insurance_no(title, url, id) {
            console.log("edit url:" + url);

            //方式1：全屏打开
            // var index = layer.open({
            //     type: 2,
            //     title: title,
            //     content: url
            // });
            // layer.full(index);

            //方式2：固定窗口大小
            var index = layer.open({
                type: 2,
                area: ['850px', '500px'],
                fixed: false,
                maxmin: true,
                title: title,
                content: url
            });

            //方式3：新建tab页
            // parent.layui.index.openTabsPage(url, title);
        }

        /*
         * 任务明细
         *
         * By TerryQi
         *
         * 2019-07-01
         */
        function detail_item(title, url, id) {
            console.log("detail_item url:" + url);

            //方式1：全屏打开
            // var index = layer.open({
            //     type: 2,
            //     title: title,
            //     content: url
            // });
            // layer.full(index);

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
            parent.layui.index.openTabsPage(url, title);
        }


        /*
         * 页面刷新
         *
         * By TerryQi
         *
         */
        function refresh() {
            {{--location.replace('{{URL::asset('/company/role1/jobOrderWorker/info')}}?id={{$data->id}}');--}}
            reloadPage();
        }


    </script>
@endsection