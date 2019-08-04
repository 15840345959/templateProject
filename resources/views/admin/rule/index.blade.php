@extends('admin.layouts.app')

@section('content')

    <style>

    </style>

    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">业务规则</div>
                    <div class="layui-card-body">
                        <div class="layui-collapse" lay-filter="component-panel">
                            <div class="layui-colla-item">
                                <h2 class="layui-colla-title">关于业务预警<i
                                            class="layui-icon layui-colla-icon"></i></h2>
                                <div class="layui-colla-content layui-show">
                                    <p>
                                        劳务派遣将以员工、工作包、项目合同、工资单为维度进行规则预警，预警级别分为
                                        <span class="c-orange">黄色预警</span>、<span class="c-danger">红色预警</span>，黄色预警代表需要关注、红色预警代表严重
                                    </p>
                                    <p>
                                        人员预警：针对年龄超过65岁的人员、存在不良记录（发生过劳动纠纷、有不良嗜好等）的人员进行红色预警；针对60岁至65岁人员进行黄色预警。
                                    </p>
                                    <p>
                                        项目合同：协议到期还剩30天进行黄色预警；协议到期未关闭红色预警。
                                    </p>
                                    <p>
                                        工作包：每日工作时长超过4小时、每周工作时长超过24小时、每小时工资低于城市最低工资进行工作包预警。
                                    </p>
                                    <p>
                                        工作包接单：接单人没有上保险，缺少保单号进行红色预警。
                                    </p>
                                    <p>
                                        工资单：工资单按周出具，每日工作时长超过4小时、每周工作时长超过24小时，工资单金额超过800元进行红色预警。
                                    </p>
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

                ajaxRequest('{{URL::asset('/admin/ad/setStatus')}}/' + id, param, "GET", function (ret) {
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

                ajaxRequest('{{URL::asset('/admin/ad/setStatus')}}/' + id, param, "GET", function (ret) {
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
            {{--location.replace('{{URL::asset('/admin/aboutus/index')}}');--}}
            reloadPage();
        }

    </script>
@endsection