@extends('admin.layouts.app')

@section('content')
    <div class="layui-fluid">

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        申请用户信息
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
                                                <td>{{$data->name}}</td>
                                                <td>电话</td>
                                                <td>{{$data->phonenum}}</td>
                                                <td>出生日期</td>
                                                <td>{{$data->birthday}}</td>
                                            </tr>
                                            <tr>
                                                <td>年龄</td>
                                                <td>{{$data->age}}</td>
                                                <td>审核状态</td>
                                                <td>{{$data->audit_status_str}}</td>
                                                <td>工作</td>
                                                <td>{{$data->job->name}}</td>
                                                <td>子工作</td>
                                                <td>
                                                    @foreach($data->sub_jobs as $sub_job)
                                                        <span class="mr-5">{{$sub_job->name}}</span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>身份证</td>
                                                <td>{{isset($data->ID_card_no)?$data->ID_card_no:'--'}}</td>
                                                <td>城市</td>
                                                <td>{{isset($data->city) ? $data->city->name:'--'}}</td>
                                                <td>--</td>
                                                <td>--</td>
                                                <td>--</td>
                                                <td>--</td>
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
                                                    <td>{{$data->admin->id}}</td>
                                                    <td>审核人</td>
                                                    <td>{{$data->admin->name}}</td>
                                                    <td>审核人手机号</td>
                                                    <td>{{$data->admin->phonenum}}</td>
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


    </script>
@endsection