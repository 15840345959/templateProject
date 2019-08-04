@extends('admin.layouts.app')

@section('content')

    <div class="layui-fluid">
        {{--在管项目基本信息--}}
        <div class="layui-card">
            <div class="layui-card-header">在管项目信息</div>
            <div class="layui-card-body">
                <div class="layui-btn-container">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <td>ID</td>
                            <td>{{$data->id}}</td>
                            <td>名称</td>
                            <td>{{$data->name}}</td>
                            <td>物业公司</td>
                            <td>{{isset($data->man_company)?$data->man_company->name:'--'}}</td>
                            <td>状态</td>
                            <td>{{$data->status_str}}</td>
                        </tr>
                        <tr>
                            <td>电话</td>
                            <td>{{$data->phonenum}}</td>
                            <td>地址</td>
                            <td>{{$data->address}}</td>
                            <td>当前员工数</td>
                            <td>{{$company_workers->count()}} 名</td>
                            <td>--</td>
                            <td>--</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md6">
                <div class="layui-card">
                    <div class="layui-card-header">当前员工</div>
                    <div class="layui-card-body">
                        <div class="layui-btn-container">
                            <table class="layui-table text-c" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-c" width="20">ID</th>
                                    <th class="text-c" width="20">姓名</th>
                                    <th class="text-c" width="50">电话</th>
                                    <th class="text-c" width="50">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($company_workers as $company_worker)
                                    <tr class="{{$company_worker->worker->alert_level}}"
                                        title="{{$company_worker->worker->alert_str}}">
                                        <td>{{$company_worker->id}}</td>
                                        <td>{{$company_worker->worker->name}}</td>
                                        <td>{{$company_worker->worker->phonenum}}</td>
                                        <td>
                                            <button class="layui-btn layui-btn-sm"
                                                    onclick="remove_worker(this,'{{$company_worker->id}}')">
                                                调离
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md6">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <div class="layui-row" style="padding-top: 2px;">
                            <div class="layui-col-md10">
                                <input type="text" name="search_word" id="search_word"
                                       placeholder="请输入检索员工的姓名或电话" class="layui-input" style="">
                            </div>
                            <div class="layui-col-md2">
                                <div class="" style="display: inline-block;">
                                    <i class="layui-icon layui-icon-search" onclick="search_worker();"
                                       style="cursor: pointer;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-btn-container">
                            <table class="layui-table text-c" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-c" width="20">ID</th>
                                    <th class="text-c" width="20">姓名</th>
                                    <th class="text-c" width="50">电话</th>
                                    <th class="text-c" width="50">操作</th>
                                </tr>
                                </thead>
                                <tbody id="search_worker_div">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--搜索员工列表-->
    <script id="search-worker-content" type="text/x-dot-template">
        @{{for(var i=0;i
        <it.length ;i++){}}
        <tr class="@{{=it[i].alert_level}}" title="@{{=it[i].alert_str}}">
            <td>@{{=it[i].id}}</td>
            <td>@{{=it[i].name}}</td>
            <td>@{{=it[i].phonenum}}</td>
            <td>
                <button class="layui-btn layui-btn-sm" onclick="add_worker(this,@{{=it[i].id}},{{$data->id}})">
                    加入
                </button>
            </td>
        </tr>
        @{{}}}
    </script>


@endsection


@section('script')
    <script type="text/javascript">

        var layer = null;
        var form = null;


        $(function () {

        })

        //配置轮播图实例
        layui.use(['carousel', 'index', 'layer', 'form'], function () {
            layer = layui.layer;        //初始化layer
            form = layui.form;

            form.render();
        });

        //点击搜索人员
        function search_worker() {
            var search_word = $("#search_word").val();
            if (judgeIsAnyNullStr(search_word)) {
                layer.msg('请输入员工姓名或电话', {icon: 5, time: 2000});
            }
            var param = {
                search_word: search_word,
                _token: "{{ csrf_token() }}"
            }
            var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

            ajaxRequest('{{URL::asset('/admin/worker/getListByCon')}}', param, "GET", function (ret) {
                if (ret.result) {
                    var interText = doT.template($("#search-worker-content").text());
                    consoledebug.log(interText);
                    $("#search_worker_div").html(interText(ret.ret))
                } else {
                    layer.msg(ret.message, {icon: 5, time: 2000});
                }
                layer.close(loadding_index);
            });
        }


        /*
         * 进行员工加入
         *
         * By TerryQi
         *
         * 2019-07-01
         *
         */
        function add_worker(obj, worker_id, bus_company_id) {
            layer.confirm('确认要加入吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    worker_id: worker_id,
                    bus_company_id: bus_company_id,
                    _token: "{{ csrf_token() }}"
                }
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                ajaxRequest('{{URL::asset('/admin/companyWorker/add')}}', param, "POST", function (ret) {
                    if (ret.result) {
                        layer.msg('已经加入');
                        refresh();
                    } else {
                        layer.msg(ret.message, {icon: 5, time: 2000});
                    }
                    layer.close(loadding_index);
                });
            });

        }

        /*
         * 进行员工删除
         *
         * By TerryQi
         *
         * 2019-07-01
         *
         */
        function remove_worker(obj, id) {
            layer.confirm('确认要调离吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    _token: "{{ csrf_token() }}"
                }
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                ajaxRequest('{{URL::asset('/admin/companyWorker/remove')}}', param, "POST", function (ret) {
                    if (ret.result) {
                        layer.msg('已经调离');
                        refresh();
                    } else {
                        layer.msg(ret.message, {icon: 5, time: 2000});
                    }
                    layer.close(loadding_index);
                });
            });
        }


        /*
         * 页面刷新
         *
         * By TerryQi
         *
         */
        function refresh() {
            {{--location.replace('{{URL::asset('/admin/busCompany/worker')}}?id={{$data->id}}');--}}
            reloadPage();
        }


    </script>
@endsection