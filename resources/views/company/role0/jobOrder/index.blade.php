@extends('company.layouts.app')

@section('content')

    <style>

    </style>

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <form id="search_form" class="layui-form" method="post"
                      action="{{URL::asset('/company/role0/jobOrder/index')}}?page={{$datas->currentPage()}}">
                    {{csrf_field()}}
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">关键字</label>
                            <div class="layui-input-block">
                                <input type="text" name="search_word" placeholder="请输入姓名/电话" autocomplete="off"
                                       value="{{$con_arr['search_word']}}"
                                       class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">城市</label>
                            <div class="layui-input-inline">
                                <select name="city_id">
                                    <option value="" {{$con_arr['city_id']=="" ? 'selected':''}}>请选择</option>
                                    @foreach($citys as $city)
                                        <option value="{{$city->id}}" {{$con_arr['city_id']==strval($city->id) ? 'selected':''}}>{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">工作</label>
                            <div class="layui-input-inline">
                                <select name="job_id">
                                    <option value="" {{$con_arr['job_id']=="" ? 'selected':''}}>请选择</option>
                                    @foreach($jobs as $job)
                                        <option value="{{$job->id}}" {{$con_arr['job_id']==strval($job->id) ? 'selected':''}}>{{$job->name}}</option>
                                    @endforeach
                                </select>
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
                            <label class="layui-form-label">性别</label>
                            <div class="layui-input-inline">
                                <select name="gender">
                                    <option value="" {{$con_arr['gender']=="" ? 'selected':''}}>请选择</option>
                                    @foreach(\App\Components\Project::JOB_ORDER_GENDER_VAL as $key=>$value)
                                        <option value="{{$key}}" {{$con_arr['job_id']==strval($key) ? 'selected':''}}>{{$value}}</option>
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
                <div style="padding-bottom: 10px;">
                    <button class="layui-btn layuiadmin-btn-forum-list"
                            onclick="edit('派发工作包','{{URL::asset('company/role0/jobOrder/edit')}}')">
                        派发工作包
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
                            {{--<th class="text-c" width="20">单号</th>--}}
                            <th class="text-c" width="30">工作内容</th>
                            <th class="text-c" width="60">在管项目</th>
                            <th class="text-c" width="40">性别/人数</th>
                            <th class="text-c" width="30">工作</th>
                            <th class="text-c" width="30">城市</th>
                            <th class="text-c" width="60">月工资</th>
                            <th class="text-c" width="60">小时工资</th>
                            <th class="text-c" width="60">工作时间</th>
                            <th class="text-c" width="20">状态</th>
                            <th class="text-c" width="100">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr class="{{$data->alert_level}}" title="{{$data->alert_str}}">
                                <td>{{$data->id}}</td>
                                {{--<td>{{$data->trade_no}}</td>--}}
                                <td>{{$data->name}}</td>
                                <td>{{isset($data->bus_company)?$data->bus_company->name:'--'}}</td>
                                <td>{{$data->gender_str}} / {{$data->people_num}}人</td>
                                <td>{{isset($data->job)?$data->job->name:'--'}}</td>
                                <td>{{isset($data->city)?$data->city->name:'--'}}</td>
                                <td>{{$data->monthly_pay}} 元</td>
                                <td>{{$data->daily_pay}} 元</td>
                                <td>{{$data->week_times_str}}</td>
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
                                                onclick="edit('编辑工作包-{{$data->trade_no}}','{{URL::asset('company/role0/jobOrder/edit')}}?id={{$data->id}}',{{$data->id}})">
                                            编辑
                                        </button>
                                        <button class="layui-btn layui-btn-sm"
                                                onclick="info('工作包详情-{{$data->trade_no}}','{{URL::asset('company/role0/jobOrder/info')}}?id={{$data->id}}',{{$data->id}})">
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

                ajaxRequest('{{URL::asset('/company/role0/jobOrder/setStatus')}}/' + id, param, "GET", function (ret) {
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

                ajaxRequest('{{URL::asset('/company/role0/jobOrder/setStatus')}}/' + id, param, "GET", function (ret) {
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
            {{--location.replace('{{URL::asset('/company/role0/jobOrder/index')}}');--}}
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

                ajaxRequest('{{URL::asset('/company/role0/jobOrder/reject')}}', param, "GET", function (ret) {
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