@extends('company.layouts.app')

@section('content')
    <div class="layui-fluid">

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        员工信息
                    </div>
                    <div class="layui-card-body">
                        <table class="layui-table">
                            <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{$data->id}}</td>
                                <td>姓名</td>
                                <td>{{$data->name}}</td>
                                <td>性别</td>
                                <td>{{$data->gender_str}}</td>
                                <td>是否关注</td>
                                <td>{{$data->is_spec_str}}</td>
                            </tr>
                            <tr>
                                <td>生日</td>
                                <td>{{$data->birthday}}</td>
                                <td>年龄</td>
                                <td class="{{$data->alert_level}}" title="{{$data->alert_str}}">{{$data->age}}</td>
                                <td>工作</td>
                                <td>{{isset($data->job)?$data->job->name:'--'}}</td>
                                <td>子工作</td>
                                <td>
                                    @if($data->sub_jobs)
                                        @foreach($data->sub_jobs as $sub_job)
                                            <span class="mr-5">{{$sub_job->name}}</span>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>所在城市</td>
                                <td>
                                    {{isset($data->city)?$data->city->name:'--'}}
                                </td>
                                <td>注册时间</td>
                                <td>
                                    {{$data->created_at}}
                                </td>
                                <td colspan="4">
                                    <button class="layui-btn layui-btn-sm"
                                            onclick="job_order_worker_list('工作包接单-{{$data->name}}','{{URL::asset('/company/role1/jobOrderWorker/index')}}?worker_id={{$data->id}}',{{$data->id}})">
                                        接包信息
                                    </button>
                                    <button class="layui-btn layui-btn-sm"
                                            onclick="job_order_item_list('工作包任务-{{$data->name}}','{{URL::asset('/company/role1/jobOrderItem/index')}}?worker_id={{$data->id}}',{{$data->id}})">
                                        任务明细
                                    </button>
                                    <button class="layui-btn layui-btn-sm"
                                            onclick="worker_setUser('员工绑定-{{$data->name}}','{{URL::asset('/company/role1/worker/setUser')}}?id={{$data->id}}',{{$data->id}})">
                                        绑定用户
                                    </button>
                                    <button class="layui-btn layui-btn-sm btn-refresh" onclick="refresh()">
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


        @if(isset($data->user))
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            关联用户
                        </div>
                        <div class="layui-card-body">
                            <table class="layui-table">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{$data->user->id}}</td>
                                    <td>头像</td>
                                    <td>
                                        <img src="{{ $data->user->avatar ? $data->user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : \Illuminate\Support\Facades\URL::asset('/img/default_headicon.png')}}"
                                             class="img-rect-30 radius-5 img-pic">
                                    </td>
                                    <td>昵称</td>
                                    <td>{{isset($data->user->nick_name)?$data->user->nick_name:'--'}}</td>
                                    <td>姓名</td>
                                    <td>{{isset($data->user->real_name)?$data->user->real_name:'--'}}</td>
                                </tr>
                                <tr>
                                    <td>电话</td>
                                    <td>{{isset($data->user->phonenum)?$data->user->phonenum:'--'}}</td>
                                    <td>性别</td>
                                    <td>
                                        {{$data->user->gender_str}}
                                    </td>
                                    <td>省份</td>
                                    <td>{{isset($data->user->province)?$data->user->province:'--'}}</td>
                                    <td>城市</td>
                                    <td>{{isset($data->user->city)?$data->user->city:'--'}}</td>
                                </tr>
                                <tr>
                                    <td>注册时间</td>
                                    <td>
                                        {{$data->user->created_at}}
                                    </td>
                                    <td>状态</td>
                                    <td>
                                        {{$data->user->status_str}}
                                    </td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
            {{--location.replace('{{URL::asset('/company/role1/city/index')}}');--}}
            reloadPage();
        }

        /*编辑*/
        function job_order_worker_list(title, url, id) {
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
            //     area: ['850px', '550px'],
            //     fixed: false,
            //     maxmin: true,
            //     title: title,
            //     content: url
            // });

            //方式3：新建tab页
            // parent.layui.index.openTabsPage(url, title);
        }

        /*编辑*/
        function job_order_item_list(title, url, id) {
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
            //     area: ['850px', '550px'],
            //     fixed: false,
            //     maxmin: true,
            //     title: title,
            //     content: url
            // });

            //方式3：新建tab页
            // parent.layui.index.openTabsPage(url, title);
        }

        /*设置员工*/
        function worker_setUser(title, url, id) {
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