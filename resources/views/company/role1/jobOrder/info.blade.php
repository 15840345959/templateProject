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
                                <td>{{$data->trade_no}}</td>
                                <td>任务名</td>
                                <td>{{$data->name}}</td>
                                <td>类型</td>
                                <td>{{$data->type_str}}</td>
                            </tr>
                            <tr>
                                <td>物业公司</td>
                                <td>{{$data->man_company->name}}</td>
                                <td>在管项目</td>
                                <td>{{$data->bus_company->name}}</td>
                                <td>工作</td>
                                <td>{{$data->job->name}}</td>
                                <td>子工作</td>
                                <td>
                                    @foreach($data->sub_jobs as $sub_job)
                                        {{$sub_job->name}}
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>性别要求</td>
                                <td>{{$data->gender_str}}</td>
                                <td>年龄要求</td>
                                <td>{{$data->min_age}} - {{$data->max_age}} 岁</td>
                                <td>招人数工</td>
                                <td>{{$data->people_num}}</td>
                                <td>城市</td>
                                <td>{{$data->city->name}}</td>
                            </tr>
                            <tr>
                                <td>月工资</td>
                                <td>{{$data->monthly_pay}} 元</td>
                                <td>日工资</td>
                                <td>{{$data->daily_pay}} 元</td>
                                <td>每日时长</td>
                                <td>{{$data->hours_per_day}} 小时</td>
                                <td>小时工资</td>
                                <td>{{$data->hourly_pay}} 元</td>
                            </tr>
                            <tr>
                                <td>工作日</td>
                                <td colspan="3">{{$data->week_times_str}}</td>
                                <td>启用状态</td>
                                <td>{{$data->status_str}}</td>
                                <td>审核状态</td>
                                <td>{{$data->audit_status_str}}</td>
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
                    <div class="layui-card-header">
                        当前接单人
                    </div>
                    <div class="layui-card-body">
                        <div style="padding-bottom: 10px;">
                            <button class="layui-btn layuiadmin-btn-forum-list"
                                    onclick="setWorker('指派公司雇员','{{URL::asset('company/role1/jobOrder/setWorker')}}?id={{$data->id}}')">
                                指派公司雇员
                            </button>
                            <button class="layui-btn layuiadmin-btn-forum-list btn-refresh" type="button"
                                    onclick="refresh()">
                                <i class="layui-icon layui-icon-refresh layuiadmin-button-btn"></i>
                            </button>
                        </div>
                        <div>
                            <table class="layui-table text-c" style="width: 100%;">
                                <thead>
                                <th scope="col" colspan="100">
                                    <span>共有<strong>{{$job_order_workers->count()}}</strong> 条数据</span>
                                </th>
                                <tr>
                                    <th class="text-c" width="10">ID</th>
                                    <th class="text-c" width="20">姓名</th>
                                    <th class="text-c" width="60">保单号</th>
                                    <th class="text-c" width="20">接单方式</th>
                                    <th class="text-c" width="40">审核状态</th>
                                    <th class="text-c" width="40">状态</th>
                                    <th class="text-c" width="100">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($job_order_workers as $job_order_worker)
                                    <tr class="{{$job_order_worker->alert_level}}"
                                        title="{{$job_order_worker->alert_str}}">
                                        <td>{{$job_order_worker->id}}</td>
                                        <td>{{$job_order_worker->worker->name}}</td>
                                        <td>{{isset($job_order_worker->insurance_no) ? $job_order_worker->insurance_no:'--'}}</td>
                                        <td>{{$job_order_worker->type_str}}</td>
                                        <td>{{$job_order_worker->audit_status_str}}</td>
                                        <td>
                                            @if($job_order_worker->status == \App\Components\Common\Utils::STATUS_VALUE_1)
                                                <span class="layui-badge layui-bg-blue">启用</span>
                                            @else
                                                <span class="layui-badge layui-bg-gray">停用</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                @if($job_order_worker->status==0)
                                                    <button class="layui-btn layui-btn-sm"
                                                            onclick="start(this,{{$job_order_worker->id}})">
                                                        启用
                                                    </button>
                                                @else
                                                    <button class="layui-btn layui-btn-sm"
                                                            onclick="stop(this,{{$job_order_worker->id}})">
                                                        停用
                                                    </button>
                                                @endif
                                                <button class="layui-btn layui-btn-sm"
                                                        onclick="info('工作包接单详情-{{$data->id}}','{{URL::asset('company/role1/jobOrderWorker/info')}}?id={{$job_order_worker->id}}',{{$job_order_worker->id}})">
                                                    详情
                                                </button>
                                            </div>
                                        </td>
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
         * 页面刷新
         *
         * By TerryQi
         *
         */
        function refresh() {
            reloadPage();
        }

        /*
         * 展示详细信息
         *
         * By TerryQi
         *
         * 2018-07-07
         *
         */
        function info(title, url) {
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
            //     area: ['850px', '550px'],
            //     fixed: false,
            //     maxmin: true,
            //     title: title,
            //     content: url
            // });

            //方式3：新建tab页
            parent.layui.index.openTabsPage(url, title);
        }


        /*启用*/
        function start(obj, id) {
            layer.confirm('确认要启用吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                ajaxRequest('{{URL::asset('/company/role1/jobOrderWorker/setStatus')}}/' + id, param, "GET", function (ret) {
                    if (ret.result) {
                        layer.msg('已经启用');
                        $(".btn-refresh").click();
                    } else {
                        layer.msg(ret.message, {icon: 5, time: 2000});
                    }
                    layer.close(loadding_index);
                });
            });
        }

        /*停用*/
        function stop(obj, id) {
            layer.confirm('确认要停用吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 0,
                    _token: "{{ csrf_token() }}"
                }
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                ajaxRequest('{{URL::asset('/company/role1/jobOrderWorker/setStatus')}}/' + id, param, "GET", function (ret) {
                    if (ret.result) {
                        layer.msg('已经停用');
                        $(".btn-refresh").click();
                    } else {
                        layer.msg(ret.message, {icon: 5, time: 2000});
                    }
                    layer.close(loadding_index);
                });
            });
        }


        /*
         * 指派雇员
         *
         * By TerryQi
         *
         * 2019-07-02
         */
        function setWorker(title, url) {
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
                area: ['850px', '550px'],
                fixed: false,
                maxmin: true,
                title: title,
                content: url
            });

            //方式3：新建tab页
            // parent.layui.index.openTabsPage(url, title);
        }


    </script>
@endsection