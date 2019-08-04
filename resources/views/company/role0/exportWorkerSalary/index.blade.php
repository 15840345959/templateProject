@extends('admin.layouts.app')

@section('content')

    <style>

    </style>

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <form id="search_form" class="layui-form" method="post"
                      action="{{URL::asset('/company/role0/exportWorkerSalary/index')}}?page={{$datas->currentPage()}}">
                    {{csrf_field()}}
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">项目id</label>
                            <div class="layui-input-block">
                                <input type="text" name="bus_company_id" placeholder="请输入项目id" autocomplete="off"
                                       value="{{$con_arr['bus_company_id']}}"
                                       class="layui-input">
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
                <div style="padding-bottom: 10px;">
                    <button class="layui-btn layuiadmin-btn-forum-list"
                            onclick="export_salary('导出工资单','{{URL::asset('company/role0/exportWorkerSalary/export')}}')">
                        导出工资单
                    </button>
                </div>
                <div class="table-container">
                    <table class="layui-table text-c" style="width: 100%;">
                        <thead>
                        <th scope="col" colspan="100">
                            <span>共有<strong>{{$datas->total()}}</strong> 条数据</span>
                        </th>
                        <tr>
                            <th class="text-c" width="10">ID</th>
                            <th class="text-c" width="60">统计时段</th>
                            <th class="text-c" width="60">物业公司</th>
                            <th class="text-c" width="60">项目名称</th>
                            <th class="text-c" width="20">任务状态</th>
                            <th class="text-c" width="20">导出人</th>
                            <th class="text-c" width="20">操作时间</th>
                            <th class="text-c" width="20">导出文件</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <td>{{$data->id}}</td>
                                <td>
                                    {{$data->from_date}}至{{$data->to_date}}
                                </td>
                                <td>
                                    {{isset($data->man_company)?$data->man_company->name:'--'}}
                                </td>
                                <td>
                                    {{isset($data->bus_company)?$data->bus_company->name:'--'}}
                                </td>
                                <td>
                                    {{$data->task_status_str}}
                                </td>
                                <td>
                                    @if(isset($data->admin))
                                        {{$data->admin->name}}
                                    @endif
                                    @if(isset($data->company_user))
                                        {{$data->company_user->name}}
                                    @endif
                                </td>
                                <td>
                                    {{$data->created_at}}
                                </td>
                                <td>
                                    @if(isset($data->file_name))
                                        <a href="{{URL::asset('/excel/exportWorkerSalary/'.$data->file_name)}}"
                                           class="ml-10 c-primary"
                                           style="cursor: pointer;">导出文件</a></td>
                                @else
                                    --
                                    @endif
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


        /*导出*/
        function export_salary(title, url, id) {
            console.log("export_salary url:" + url);

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
            //     area: ['850px', '350px'],
            //     fixed: false,
            //     maxmin: true,
            //     title: title,
            //     content: url
            // });

            //方式3：新建tab页
            // parent.layui.index.openTabsPage(url, title);
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

                ajaxRequest('{{URL::asset('/company/role0/exportWorkerSalary/setStatus')}}/' + id, param, "GET", function (ret) {
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

                ajaxRequest('{{URL::asset('/company/role0/exportWorkerSalary/setStatus')}}/' + id, param, "GET", function (ret) {
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
            {{--location.replace('{{URL::asset('/company/role0/exportWorkerSalary/index')}}');--}}
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
                ajaxRequest('{{URL::asset('')}}' + "company/role0/exportWorkerSalary/resetPassword/" + id, param, "GET", function (ret) {
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


        /*设置员工*/
        function setUser(title, url, id) {
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
                area: ['850px', '450px'],
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