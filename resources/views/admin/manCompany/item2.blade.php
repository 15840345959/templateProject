<div class="layui-card-body">
    <div style="padding-bottom: 10px;">
        <button class="layui-btn layuiadmin-btn-forum-list"
                onclick="edit('添加在管项目','{{URL::asset('/admin/busCompany/edit')}}?man_company_id={{$data->id}}')">
            添加在管项目
        </button>
        <button class="layui-btn layuiadmin-btn-forum-list btn-refresh" type="button"
                onclick="refresh()">
            <i class="layui-icon layui-icon-refresh layuiadmin-button-btn"></i>
        </button>
    </div>
    <div>
        <table class="layui-table text-c" style="width: 100%;">
            <thead>
            <th scope="col" colspan="100">
                <span>共有<strong>{{$bus_companys->count()}}</strong> 条数据</span>
            </th>
            <tr>
                <th class="text-c" width="20">ID</th>
                <th class="text-c" width="50">名称</th>
                <th class="text-c" width="30">电话</th>
                <th class="text-c" width="60">地址</th>
                <th class="text-c" width="20">状态</th>
                <th class="text-c" width="40">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bus_companys as $bus_company)
                <tr>
                    <td>{{$bus_company->id}}</td>
                    <td>
                        <div class="text-oneline">
                            {{$bus_company->name}}
                        </div>
                    </td>
                    <td>
                        {{$bus_company->phonenum}}
                    </td>
                    <td>
                        <div class="text-oneline">{{$bus_company->address}}</div>
                    </td>
                    <td>
                        @if($bus_company->status == \App\Components\Common\Utils::STATUS_VALUE_1)
                            <span class="layui-badge layui-bg-blue">启用</span>
                        @else
                            <span class="layui-badge layui-bg-gray">停用</span>
                        @endif

                    </td>
                    <td>
                        <div>
                            @if($bus_company->status==0)
                                <button class="layui-btn layui-btn-sm" onclick="start(this,{{$bus_company->id}})">
                                    启用
                                </button>
                            @else
                                <button class="layui-btn layui-btn-sm" onclick="stop(this,{{$bus_company->id}})">
                                    停用
                                </button>
                            @endif
                            <button class="layui-btn layui-btn-sm"
                                    onclick="edit('编辑在管项目-{{$bus_company->name}}','{{URL::asset('/admin/busCompany/edit')}}?id={{$data->id}})&man_company_id={{$data->man_company_id}}',{{$data->id}})">
                                编辑
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript">


    $(function () {

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
        //     area: ['750px', '480px'],
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

            ajaxRequest('{{URL::asset('/admin/busCompany/setStatus')}}/' + id, param, "GET", function (ret) {
                if (ret.result) {
                    layer.msg('已经启用');
                    refresh();
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

            ajaxRequest('{{URL::asset('/admin/busCompany/setStatus')}}/' + id, param, "GET", function (ret) {
                if (ret.result) {
                    layer.msg('已经停用');
                    refresh();
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
        {{--location.replace('{{URL::asset('/admin/manCompany/edit')}}?id={{$data->id}}&item={{$item}}');--}}
        reloadPage();
    }

</script>