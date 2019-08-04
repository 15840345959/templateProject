@extends('admin.layouts.app')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">信息配置</div>
                    <div class="layui-card-body" pad15>
                        <div class="layui-tab layui-tab-brief" lay-filter="component-tabs-brief">
                            <ul class="layui-tab-title">
                                <li class="{{$item== 0 ? 'layui-this':''}}" onclick="selectItem(0)">基础信息</li>
                                <li class="{{$item== 1 ? 'layui-this':''}}" onclick="selectItem(1)">子工作管理</li>
                            </ul>
                            <div class="layui-tab-content">
                                <div class="mt-10">
                                    {{--通过item控制显示选项--}}
                                    @if($item=='0')
                                        @include('admin.job.item0')
                                    @endif
                                    @if($item=='1')
                                        @include('admin.job.item1')
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script type="text/javascript">

        var layer = null;
        var form = null;

        //入口函数
        $(function () {

        });


        layui.use(['index', 'layer', 'form', 'set', 'carousel'], function () {
            layer = layui.layer;        //初始化layer

        });

        //商户id
        var job_id = '{{$data->id}}';

        $(function () {

        });

        //选择项目
        function selectItem(item) {
            if (judgeIsAnyNullStr(job_id)) {
                layer.alert('必须配置工作基本信息后才可以进行其他配置');
                return;
            }
            var index = layer.load(2, {time: 10 * 1000}); //加载
            location.replace('{{URL::asset('/admin/job/edit')}}?id={{$data->id}}&item=' + item);
        }

    </script>
@endsection