@extends('admin.layouts.app')

@section('content')
    <div class="layui-fluid">

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body layui-form">
                        <div style="padding-bottom: 10px;">
                            <button class="layui-btn layuiadmin-btn-forum-list" lay-submit lay-filter="LAY-form-edit">
                                指派人员
                            </button>
                        </div>
                        <div class="layui-form-item hidden">
                            <label class="layui-form-label">工作包id</label>
                            <div class="layui-input-inline">
                                <input type="text" name="job_order_id" value="{{$data->id}}" class="layui-input"
                                       lay-verify="required"
                                       placeholder="请输入工作包id">
                            </div>
                            <div class="layui-form-mid layui-word-aux"></div>
                        </div>
                        <div>
                            <table class="layui-table text-c" style="width: 100%;">
                                <thead>
                                <th scope="col" colspan="100">
                                    <span>共有<strong>{{$company_workers->count()}}</strong> 条数据</span>
                                </th>
                                <tr>
                                    <th class="text-c" width="10">
                                        <input type="checkbox" name="" title="" lay-skin="primary"
                                               lay-filter="LAY-select-all">
                                    </th>
                                    <th class="text-c" width="10">ID</th>
                                    <th class="text-c" width="20">姓名</th>
                                    <th class="text-c" width="30">手机号</th>
                                    <th class="text-c" width="20">加入时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($company_workers as $company_worker)
                                    <tr class="{{$company_worker->worker->alert_level}}"
                                        title="{{$company_worker->worker->alert_str}}">
                                        <td>
                                            <input type="checkbox" name="worker_ids[]" title=""
                                                   class="select-worker-item" value="{{$company_worker->worker->id}}"
                                                   lay-skin="primary" {{$company_worker->has_taken_order == true ? 'checked disabled':''}}>
                                        </td>
                                        <td>{{$company_worker->id}}</td>
                                        <td>{{$company_worker->worker->name}}</td>
                                        <td>{{$company_worker->worker->phonenum}}</td>
                                        <td>{{$company_worker->join_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
            form = layui.form;      //初始化form

            form.render();

            form.on('checkbox(LAY-select-all)', function (data) {
                consoledebug.log(JSON.stringify(data));
                $(".select-worker-item").attr("checked", "checked");
                form.render();
            });

            //表单提交
            form.on('submit(LAY-form-edit)', function (data) {
                var param = data.field;
                consoledebug.log("param:" + JSON.stringify(param));
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                //进行请求
                ajaxRequest('{{URL::asset('/admin/jobOrder/setWorker')}}', param, "POST", function (ret) {
                    if (ret.result) {
                        layer.msg('设置成功', {icon: 1, time: 1000});
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