@extends('company.layouts.app')

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
                                <li class="{{$item== 1 ? 'layui-this':''}}" onclick="selectItem(1)">招工条件</li>
                                <li class="{{$item== 2 ? 'layui-this':''}}" onclick="selectItem(2)">详细信息</li>
                            </ul>
                            <div class="layui-tab-content">
                                <div class="mt-10">
                                    {{--通过item控制显示选项--}}
                                    @if($item=='0')
                                        @include('company.role1.jobOrder.item0')
                                    @endif
                                    @if($item=='1')
                                        @include('company.role1.jobOrder.item1')
                                    @endif
                                    @if($item=='2')
                                        @include('company.role1.jobOrder.item2')
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

@include('vendor.ueditor.assets')

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
        var jobOrder_id = '{{$data->id}}';

        $(function () {

        });

        //选择项目
        function selectItem(item) {
            if (judgeIsAnyNullStr(jobOrder_id)) {
                layer.alert('必须配置物业公司基本信息后才可以进行其他配置');
                return;
            }
            var index = layer.load(2, {time: 10 * 1000}); //加载
            location.replace('{{URL::asset('/company/role1/jobOrder/edit')}}?id={{$data->id}}&item=' + item);
        }

    </script>
@endsection