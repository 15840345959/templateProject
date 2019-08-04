@extends('admin.layouts.app')

@section('content')

    <style>

    </style>

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <form id="search_form" class="layui-form" method="post"
                      action="{{URL::asset('/admin/jobOrderItem/index')}}?page={{$datas->currentPage()}}">
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

            <div class="layui-card-body">
                <div class="table-container">
                    <table class="layui-table text-c" style="width: 100%;">
                        <thead>
                        <th scope="col" colspan="100">
                            <span>共有<strong>{{$datas->total()}}</strong> 条数据</span>
                        </th>
                        <tr>
                            <th class="text-c" width="10">ID</th>
                            <th class="text-c" width="40">工作包</th>
                            <th class="text-c" width="40">在管项目</th>
                            <th class="text-c" width="20">员工</th>
                            <th class="text-c" width="30">工作日</th>
                            <th class="text-c" width="30">预计工作时长</th>
                            <th class="text-c" width="30">实际工作时长</th>
                            <th class="text-c" width="30">预计结算工资</th>
                            <th class="text-c" width="30">实际结算时长</th>
                            <th class="text-c" width="30">奖金</th>
                            <th class="text-c" width="30">补助</th>
                            <th class="text-c" width="30">扣款1</th>
                            <th class="text-c" width="30">扣款2</th>
                            <th class="text-c" width="30">打卡状态</th>
                            <th class="text-c" width="30">审核状态</th>
                            <th class="text-c" width="20">状态</th>
                            <th class="text-c" width="40">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr class="{{$data->alert_level}}" title="{{$data->alert_str}}">
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
                                    {{$data->plan_work_hours}} 小时
                                </td>
                                <td>
                                    {{$data->real_work_hours}} 小时
                                </td>
                                <td>
                                    {{$data->plan_settle_wage}} 元
                                </td>
                                <td>
                                    {{$data->real_work_hours}} 元
                                </td>
                                <td>
                                    {{$data->bonus_wage}} 元
                                </td>
                                <td>
                                    {{$data->subsidy_wage}} 元
                                </td>
                                <td>
                                    {{$data->fine1_wage}} 元
                                </td>
                                <td>
                                    {{$data->fine2_wage}} 元
                                </td>
                                <td>
                                    {{$data->is_finished_str}}
                                </td>
                                <td>
                                    {{$data->audit_status_str}}
                                </td>
                                <td>
                                    @if($data->status == \App\Components\Common\Utils::STATUS_VALUE_1)
                                        <span class="layui-badge layui-bg-blue">启用</span>
                                    @else
                                        <span class="layui-badge layui-bg-gray">停用</span>
                                    @endif

                                </td>
                                <td>
                                    <div>
                                        @if($data->status==0)
                                            <button class="layui-btn layui-btn-sm" onclick="start(this,{{$data->id}})">
                                                启用
                                            </button>
                                        @else
                                            <button class="layui-btn layui-btn-sm" onclick="stop(this,{{$data->id}})">
                                                停用
                                            </button>
                                        @endif
                                        <button class="layui-btn layui-btn-sm"
                                                onclick="info('工作任务详情-{{$data->id}}','{{URL::asset('admin/jobOrderItem/info')}}?id={{$data->id}}',{{$data->id}})">
                                            详情
                                        </button>
                                    </div>
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

                ajaxRequest('{{URL::asset('/admin/jobOrderItem/setStatus')}}/' + id, param, "GET", function (ret) {
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

                ajaxRequest('{{URL::asset('/admin/jobOrderItem/setStatus')}}/' + id, param, "GET", function (ret) {
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
            reloadPage();
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