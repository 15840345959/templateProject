@extends('admin.layouts.app')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">进行考核</div>
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
                                <div class="layui-form-mid layui-word-aux">
                                    工作日期 {{$data->work_at}}
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">细项调整</label>
                                <div class="layui-input-block">
                                    <input type="checkbox" lay-skin="switch"
                                           lay-filter="LAY-set-work-condition" lay-text="调整|默认">
                                </div>
                                <div class="layui-form-mid layui-word-aux">可调整工作时长和工资</div>
                            </div>

                            <div class="layui-form-item set-work-condition hidden">
                                <label class="layui-form-label">工作时长</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="real_work_hours"
                                           value="{{($data->audit_status==\App\Components\Common\Utils::STATUS_VALUE_0)?$data->plan_work_hours:$data->real_work_hours}}"
                                           class="layui-input"
                                           lay-verify="required"
                                           placeholder="请输入实际工作时长">
                                </div>
                                <div class="layui-form-mid layui-word-aux">计划工作时长{{$data->plan_work_hours}} 小时</div>
                            </div>
                            <div class="layui-form-item set-work-condition hidden">
                                <label class="layui-form-label">奖金</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="bonus_wage"
                                           value="{{$data->bonus_wage}}"
                                           class="layui-input"
                                           lay-verify="required"
                                           placeholder="请输入奖金">
                                </div>
                                <div class="layui-form-mid layui-word-aux">奖励员工金额</div>
                            </div>
                            <div class="layui-form-item set-work-condition hidden">
                                <label class="layui-form-label">补助</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="subsidy_wage"
                                           value="{{$data->subsidy_wage}}"
                                           class="layui-input"
                                           lay-verify="required"
                                           placeholder="请输入补助">
                                </div>
                                <div class="layui-form-mid layui-word-aux">为员工提供的补助金额</div>
                            </div>
                            <div class="layui-form-item set-work-condition hidden">
                                <label class="layui-form-label">罚款1</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="fine1_wage"
                                           value="{{$data->fine1_wage}}"
                                           class="layui-input"
                                           lay-verify="required"
                                           placeholder="请输入罚款1">
                                </div>
                                <div class="layui-form-mid layui-word-aux">罚款项1金额</div>
                            </div>
                            <div class="layui-form-item set-work-condition hidden">
                                <label class="layui-form-label">罚款2</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="fine1_wage"
                                           value="{{$data->fine1_wage}}"
                                           class="layui-input"
                                           lay-verify="required"
                                           placeholder="请输入罚款2">
                                </div>
                                <div class="layui-form-mid layui-word-aux">罚款项2金额</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">考核评分</label>
                                <div class="layui-input-inline">
                                    <div id="work-rate"></div>
                                </div>
                                <div class="layui-form-mid layui-word-aux">评分是考核员工的重要依据</div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label">工作评价</label>
                                <div class="layui-input-block">
                                    <textarea name="audit_remark" placeholder="请输入内容"
                                              class="layui-textarea">{{\App\Components\Common\Utils::isObjNull($data->audit_remark)?'表现很好，再接再厉':$data->audit_remark}}</textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="LAY-form-edit">确认考核</button>
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

        //满意度
        var satisfaction = '{{$data->satisfaction}}';

        $(function () {

        });


        layui.use(['index', 'layer', 'form', 'set', 'laydate', 'rate'], function () {
            layer = layui.layer;        //初始化layer
            form = layui.form;
            var rate = layui.rate;

            form.render();

            //显示文字
            rate.render({
                elem: '#work-rate'
                , value: '{{$data->satisfaction}}' //初始值
                , text: false //开启文本
                , choose: function (value) {
                    consoledebug.log("work-rate value:" + value);
                    satisfaction = value;
                }
            });

            //监听开关
            form.on('switch(LAY-set-work-condition)', function (data) {
                consoledebug.log("data:" + JSON.stringify(data));
                consoledebug.log(data.elem.checked);
                var score_pay_way_flag = data.elem.checked;
                if (score_pay_way_flag) {
                    $(".set-work-condition").removeClass('hidden');
                } else {
                    $(".set-work-condition").addClass('hidden');
                }
            });

            //表单提交text
            form.on('submit(LAY-form-edit)', function (data) {
                var param = data.field;
                param.satisfaction = satisfaction;
                consoledebug.log("param:" + JSON.stringify(param));
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                //进行请求
                ajaxRequest('{{URL::asset('/company/role1/jobOrderItem/workAudit')}}', param, "POST", function (ret) {
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