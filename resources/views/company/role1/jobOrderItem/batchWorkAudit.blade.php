@extends('company.layouts.app')

@section('content')

    <style>

    </style>

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <form id="search_form" class="layui-form" method="get"
                      action="{{URL::asset('/company/role1/jobOrderItem/batchWorkAudit')}}?page={{$datas->currentPage()}}">
                    {{csrf_field()}}
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">工作包id</label>
                            <div class="layui-input-block">
                                <input type="text" name="job_order_id" placeholder="请输入工作包id" autocomplete="off"
                                       value="{{$con_arr['job_order_id']}}"
                                       class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">员工id</label>
                            <div class="layui-input-block">
                                <input type="text" name="worker_id" placeholder="请输入员工id" autocomplete="off"
                                       value="{{$con_arr['worker_id']}}"
                                       class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">接单id</label>
                            <div class="layui-input-block">
                                <input type="text" name="job_order_worker_id" placeholder="请输入接单id" autocomplete="off"
                                       value="{{$con_arr['job_order_worker_id']}}"
                                       class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">在管项目id</label>
                            <div class="layui-input-block">
                                <input type="text" name="bus_company_id" placeholder="请输入在管项目id" autocomplete="off"
                                       value="{{$con_arr['bus_company_id']}}"
                                       class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">审核状态</label>
                            <div class="layui-input-block">
                                <select name="audit_status">
                                    <option value="" {{$con_arr['audit_status']=="" ? 'selected':''}}>请选择</option>
                                    @foreach(\App\Components\Common\Utils::AUDIT_STATUS_VAL as $key=>$value)
                                        <option value="{{$value}}" {{$con_arr['audit_status']==strval($key) ? 'selected':''}}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layuiadmin-btn-forum-list" type="submit" lay-submit=""
                                    lay-filter="LAY-app-forumlist-search">
                                <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                            </button>
                            <button class="layui-btn layuiadmin-btn-forum-list btn-refresh" type="button"
                                    onclick="refresh()">
                                <i class="layui-icon layui-icon-refresh layuiadmin-button-btn"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="layui-card-body layui-form">
                <div style="padding-bottom: 10px;">
                    <button class="layui-btn layuiadmin-btn-forum-list" lay-submit lay-filter="LAY-form-edit">
                        批量考核
                    </button>
                </div>
                <div>
                    <table class="layui-table text-c" style="width: 100%;">
                        <thead>
                        <th scope="col" colspan="100">
                            <span>共有<strong>{{$datas->total()}}</strong> 条数据</span>
                        </th>
                        <tr>
                            <th class="text-c" width="10">
                                <input type="checkbox" name="" title="" lay-skin="primary"
                                       lay-filter="LAY-select-all">
                            </th>
                            <th class="text-c" width="10">ID</th>
                            <th class="text-c" width="40">工作包</th>
                            <th class="text-c" width="40">在管项目</th>
                            <th class="text-c" width="20">员工</th>
                            <th class="text-c" width="30">工作日</th>
                            <th class="text-c" width="30">打卡状态</th>
                            <th class="text-c" width="30">审核状态</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr class="{{$data->alert_level}}" title="{{$data->alert_str}}">
                                <td>
                                    <input type="checkbox" name="job_order_item_ids[]" title=""
                                           class="select-job-order-item" value="{{$data->id}}"
                                           lay-skin="primary">
                                </td>
                                <td>{{$data->id}}</td>
                                <td>
                                    {{$data->job_order->name}}
                                </td>
                                <td>
                                    {{$data->bus_company->name}}
                                </td>
                                <td>
                                    {{$data->worker->name}}
                                </td>
                                <td>
                                    {{$data->work_at}}
                                </td>
                                <td>
                                    {{$data->is_finished_str}}
                                </td>
                                <td>
                                    {{$data->audit_status_str}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <div class="">
                        {{ $datas->appends($con_arr)->links() }}
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

            form.render();

            form.on('checkbox(LAY-select-all)', function (data) {
                consoledebug.log(JSON.stringify(data));
                $(".select-job-order-item").attr("checked", "checked");
                form.render();
            });

            //表单提交
            form.on('submit(LAY-form-edit)', function (data) {
                layer.confirm('确认要进行批量考核吗？考核默认评分为3分，工资、工时将按照计划工资、工时设置', function (index) {
                    var param = data.field;
                    consoledebug.log("param:" + JSON.stringify(param));
                    var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                    //进行请求
                    ajaxRequest('{{URL::asset('/company/role1/jobOrderItem/batchWorkAudit')}}', param, "POST", function (ret) {
                        if (ret.result) {
                            layer.msg('批量考核成功', {icon: 1, time: 1000});
                            $("#search_form").submit();
                        } else {
                            layer.msg(ret.message, {icon: 5, time: 2000});
                        }
                    });
                });
            });
        });

        /*编辑*/
        function edit(title, url, id) {
            console.log("edit url:" + url);

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

                ajaxRequest('{{URL::asset('/company/role1/jobOrderItem/setStatus')}}/' + id, param, "GET", function (ret) {
                    if (ret.result) {
                        layer.msg('已经启用');
                        $("#search_form").submit();
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

                ajaxRequest('{{URL::asset('/company/role1/jobOrderItem/setStatus')}}/' + id, param, "GET", function (ret) {
                    if (ret.result) {
                        layer.msg('已经停用');
                        $("#search_form").submit();
                    } else {
                        layer.msg(ret.message, {icon: 5, time: 2000});
                    }
                    layer.close(loadding_index);
                });
            });
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

        /*
         * 页面刷新
         * 
         * By TerryQi
         *
         */
        function refresh() {
            $('#search_form')[0].reset();
            {{--location.replace('{{URL::asset('/company/role1/jobOrderItem/batchWorkAudit')}}');--}}
            reloadPage();
        }


        /*重置密码*/
        function resetPassword(obj, id) {
            //此处请求后台程序，下方是成功后的前台处理
            layer.confirm('确认要重置密码？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    _token: "{{ csrf_token() }}"
                }
                ajaxRequest('{{URL::asset('')}}' + "company/role1/jobOrderItem/resetPassword/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        $("#search_form").submit();
                        $(".btn-refresh").click();
                    }
                });
                layer.msg('重置密码为Aa123456', {icon: 6, time: 1000});
            });
        }

        /*
       *
       * 展示小程序码
       *
       * By TerryQi
       *
       * 2018-03-29
       */
        function show_ewm(title, url) {
            consoledebug.log("url:" + url);
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }

    </script>
@endsection