@extends('company.layouts.app')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">设置保单号</div>
                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item hidden">
                                <label class="layui-form-label">id</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="id" value="{{$data->id}}"
                                           class="layui-input"
                                           lay-verify=""
                                           placeholder="id">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">人员</label>
                                <div class="layui-input-inline">
                                    <span class="layui-form-mid">{{$data->worker->name}}</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">手机</label>
                                <div class="layui-input-inline">
                                    <span class="layui-form-mid">{{$data->worker->phonenum}}</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">在建项目</label>
                                <div class="layui-input-inline">
                                    <span class="layui-form-mid">{{$data->job_order->bus_company->name}}</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">工作包</label>
                                <div class="layui-input-inline">
                                    <span class="layui-form-mid">{{$data->job_order->name}}</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">是否参保</label>
                                <div class="layui-input-inline">
                                    <select name="is_join_insurance" lay-verify="required">
                                        @foreach(\App\Components\Project::WORKER_IS_JOIN_INSURANCE_VAL as $key=>$value)
                                            <option value="{{$key}}" {{$data['is_join_insurance'] == $key? "selected":""}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">保险类型</label>
                                <div class="layui-input-inline">
                                    <select name="insurance_type" lay-verify="required">
                                        @foreach(\App\Components\Project::JOB_ORDER_WORKER_INSURANCE_TYPE_VAL as $key=>$value)
                                            <option value="{{$key}}" {{$data['insurance_type'] == $key? "selected":""}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">保单号</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="insurance_no" value="{{$data->insurance_no}}"
                                           class="layui-input"
                                           lay-verify="required"
                                           placeholder="请输入保单号">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="LAY-form-edit">设置保单号</button>
                                    <button type="reset" class="layui-btn layui-btn-primary" onclick="reloadPage();">
                                        重新填写
                                    </button>
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


        $(function () {

        });


        layui.use(['index', 'layer', 'form', 'set', 'laydate'], function () {
            layer = layui.layer;        //初始化layer
            form = layui.form;

            form.render();

            //表单提交text
            form.on('submit(LAY-form-edit)', function (data) {
                var param = data.field;
                consoledebug.log("param:" + JSON.stringify(param));
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                //进行请求
                ajaxRequest('{{URL::asset('/company/role0/jobOrderWorker/setInsuranceNo')}}', param, "POST", function (ret) {
                    if (ret.result) {
                        layer.msg('保存成功', {icon: 1, time: 1000});
                        setTimeout(function () {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.$(".btn-refresh").click();
                            parent.layer.close(index);
                        }, 500);
                    } else {
                        layer.msg(ret.message, {icon: 5, time: 2000});
                    }
                    layer.close(loadding_index);
                });
            });
        });


    </script>
@endsection