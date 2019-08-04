@extends('company.layouts.app')

@section('content')

    <style>

    </style>

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <form id="search_form" class="layui-form" method="post"
                      action="{{URL::asset('/company/role1/companyWorker/index')}}?page={{$datas->currentPage()}}">
                    {{csrf_field()}}
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">员工id</label>
                            <div class="layui-input-block">
                                <input type="text" name="worker_id" placeholder="请输入员工id" autocomplete="off"
                                       value="{{$con_arr['worker_id']}}"
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
                <div>
                    <table class="layui-table text-c" style="width: 100%;">
                        <thead>
                        <th scope="col" colspan="100">
                            <span>共有<strong>{{$datas->total()}}</strong> 条数据</span>
                        </th>
                        <tr>
                            <th class="text-c" width="10">ID</th>
                            <th class="text-c" width="20">员工</th>
                            <th class="text-c" width="30">物业公司</th>
                            <th class="text-c" width="30">在管项目</th>
                            <th class="text-c" width="30">加入时间</th>
                            <th class="text-c" width="30">离开时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr class="{{$data->worker->alert_level}}" title="{{$data->worker->alert_str}}">
                                <td>{{$data->id}}</td>
                                <td>{{$data->worker->name}}</td>
                                <td>{{$data->man_company->name}}</td>
                                <td>{{$data->bus_company->name}}</td>
                                <td>{{isset($data->join_at)?$data->join_at:'--'}}</td>
                                <td>{{isset($data->leave_at)?$data->leave_at:'--'}}</td>
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

                ajaxRequest('{{URL::asset('/company/role1/companyWorker/setStatus')}}/' + id, param, "GET", function (ret) {
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

                ajaxRequest('{{URL::asset('/company/role1/companyWorker/setStatus')}}/' + id, param, "GET", function (ret) {
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
            {{--location.replace('{{URL::asset('/company/role1/companyWorker/index')}}');--}}
            reloadPage();
        }

        /*驳回*/
        function reject(obj, id) {
            layer.confirm('确认要驳回吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    _token: "{{ csrf_token() }}"
                }
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                ajaxRequest('{{URL::asset('/company/role1/companyWorker/reject')}}', param, "GET", function (ret) {
                    if (ret.result) {
                        layer.msg('已经驳回');
                        $("#search_form").submit();
                    } else {
                        layer.msg(ret.message, {icon: 5, time: 2000});
                    }
                    layer.close(loadding_index);
                });
            });
        }

        /*审核通过*/
        function approve(title, url, id) {
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
            //     area: ['650px', '450px'],
            //     fixed: false,
            //     maxmin: true,
            //     title: title,
            //     content: url
            // });

            //方式3：新建tab页
            // parent.layui.index.openTabsPage(url, title);
        }


    </script>
@endsection