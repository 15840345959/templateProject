<div class="layui-card-body">
    <div style="padding-bottom: 10px;">
        <button class="layui-btn layuiadmin-btn-forum-list"
                onclick="edit('添加合同','{{URL::asset('/admin/companyContract/edit')}}?man_company_id={{$data->id}}')">
            添加合同
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
                <span>共有<strong>{{$company_contracts->count()}}</strong> 条数据</span>
            </th>
            <tr>
                <th class="text-c" width="20">ID</th>
                <th class="text-c" width="60">合同名</th>
                <th class="text-c" width="20">有效期</th>
                <th class="text-c" width="20">状态</th>
                <th class="text-c" width="60">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($company_contracts as $company_contract)
                <tr class="{{$company_contract->alert_level}}" title="{{$company_contract->alert_str}}">
                    <td>{{$company_contract->id}}</td>
                    <td>
                        <div class="text-oneline">
                            {{$company_contract->name}}
                        </div>
                    </td>
                    <td>{{$company_contract->valid_start_time}} 至 {{$company_contract->valid_end_time}}</td>
                    <td>
                        @if($company_contract->status == \App\Components\Common\Utils::STATUS_VALUE_1)
                            <span class="layui-badge layui-bg-blue">启用</span>
                        @else
                            <span class="layui-badge layui-bg-gray">停用</span>
                        @endif

                    </td>
                    <td>
                        <div>
                            @if($company_contract->status=='0')
                                <button class="layui-btn layui-btn-sm" onclick="start(this,{{$company_contract->id}})">
                                    启用
                                </button>
                            @else
                                <button class="layui-btn layui-btn-sm" onclick="stop(this,{{$company_contract->id}})">
                                    停用
                                </button>
                            @endif
                            <button class="layui-btn layui-btn-sm"
                                    onclick="edit('编辑合同-{{$data->name}}','{{URL::asset('/admin/companyContract/edit')}}?id={{$company_contract->id}}&man_company_id={{$data->id}}',{{$data->id}})">
                                编辑
                            </button>
                            <button class="layui-btn layui-btn-sm"
                                    onclick="openDownloadDialog('{{$company_contract->attach}}','{{$company_contract->name}}')">
                                下载
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

            ajaxRequest('{{URL::asset('/admin/companyContract/setStatus')}}/' + id, param, "GET", function (ret) {
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

            ajaxRequest('{{URL::asset('/admin/companyContract/setStatus')}}/' + id, param, "GET", function (ret) {
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