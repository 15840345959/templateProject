@extends('company.layouts.app')

@section('content')

    <style>

    </style>

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div>
                    <div class="layui-form" lay-filter="">
                        <div class="layui-form-item">
                            <label class="layui-form-label">导入</label>
                            <div class="layui-input-inline">
                                <input type="text" id="file_name" name="file_name" value=""
                                       class="layui-input"
                                       lay-verify="required" disabled
                                       placeholder="请选择附件上传">
                            </div>
                            <div id="container" class="layui-input-inline">
                                <button id="pickfiles" class="layui-btn layui-btn-primary">选择文件</button>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="LAY-form-edit">确认导入</button>
                                <button class="layui-btn layuiadmin-btn-forum-list btn-refresh" type="button"
                                        onclick="refresh()">
                                    <i class="layui-icon layui-icon-refresh layuiadmin-button-btn"></i>
                                </button>
                                <a href="{{URL::asset('/excel/importWorker/导入员工Excel表模板_标准.xls')}}"
                                   class="ml-10 c-primary"
                                   style="cursor: pointer;">下载《导入员工Excel表模板》</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-card-body">
                <div class="table-container">
                    <table class="layui-table text-c" style="width: 100%;">
                        <thead>
                        <th scope="col" colspan="100">
                            <span>共有<strong>{{$datas->total()}}</strong> 条数据</span>
                        </th>
                        <tr>
                            <th class="text-c" width="20">ID</th>
                            <th class="text-c" width="50">导入文件</th>
                            <th class="text-c" width="30">结果文件</th>
                            <th class="text-c" width="30">导入人</th>
                            <th class="text-c" width="20">导入时间</th>
                            <th class="text-c" width="30">记录总数</th>
                            <th class="text-c" width="30">导入成功数</th>
                            <th class="text-c" width="30">导入失败数</th>
                            <th class="text-c" width="30">状态</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr class="{{$data->alert_level}}" title="{{$data->alert_str}}">
                                <td>{{$data->id}}</td>
                                <td>
                                    @if(isset($data->file_name))
                                        <a href="{{$data->file_name}}"
                                           class="ml-10 c-primary"
                                           style="cursor: pointer;">导入原始文件</a>
                                    @else
                                        --
                                    @endif

                                </td>
                                <td>
                                    @if(isset($data->result_file_name))
                                        <a href="{{URL::asset('/excel/importWorker/'.$data->result_file_name)}}"
                                           class="ml-10 c-primary"
                                           style="cursor: pointer;">导入结果文件</a></td>
                                @else
                                    --
                                @endif
                                <td>
                                    @if(isset($data->admin))
                                        {{$data->admin->name}}
                                    @endif
                                    @if(isset($data->company_user))
                                        {{$data->company_user->name}}
                                    @endif
                                </td>
                                <td>{{$data->created_at}}</td>
                                <td>{{$data->total_count}}</td>
                                <td>{{$data->success_count}}</td>
                                <td>{{$data->failed_count}}</td>
                                <td>{{$data->task_status_str}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <div class="">
                        {{ $datas->appends($con_arr)->links() }}
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
            //初始化七牛
            initQNUploader();
        });

        //初始化模块
        layui.use(['index', 'layer', 'form', 'set', 'laypage'], function () {
            layer = layui.layer;        //初始化layer
            form = layui.form;

            form.render();

            //表单提交text
            form.on('submit(LAY-form-edit)', function (data) {
                var param = data.field;

                var file_name = $("#file_name").val();
                var file_type = extname(file_name);
                if (file_type != '.xls') {
                    layer.msg("请下载模板并上传Excel文件，要求文件格式必须为xls", {icon: 5, time: 10000});
                    return;
                }
                consoledebug.log("param:" + JSON.stringify(param));
                var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

                //进行请求
                ajaxRequest('{{URL::asset('/company/role0/importWorkerTask/import')}}', param, "POST", function (ret) {
                    if (ret.result) {
                        layer.msg('保存成功', {icon: 1, time: 1000});
                        setTimeout(function () {
                            refresh();
                        }, 500);
                    } else {
                        layer.msg(ret.message, {icon: 5, time: 1000});
                    }
                    layer.close(loadding_index);
                });
            });
        });


        //初始化七牛上传模块
        function initQNUploader() {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: 'pickfiles',         // 上传选择的点选按钮，必需
                container: 'container',//上传按钮的上级元素ID
                // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
                // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
                // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
                uptoken: "{{$upload_token}}", // uptoken是上传凭证，由其他程序生成
                // uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
                // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
                //    // do something
                //    return uptoken;
                // },
                get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                // downtoken_url: '/downtoken',
                // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
                unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
                // save_key: true,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
                domain: 'http://twst.isart.me/',     // bucket域名，下载资源时用到，必需
                max_file_size: '100mb',             // 最大文件体积限制
                flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                dragdrop: true,                     // 开启可拖曳上传
                drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                //x_vars : {
                //    查看自定义变量
                //    'time' : function(up,file) {
                //        var time = (new Date()).getTime();
                // do something with 'time'
                //        return time;
                //    },
                //    'size' : function(up,file) {
                //        var size = file.size;
                // do something with 'size'
                //        return size;
                //    }
                //},
                init: {
                    'FilesAdded': function (up, files) {
                        plupload.each(files, function (file) {

                        });
                    },
                    'BeforeUpload': function (up, file) {
                        // 每个文件上传前，处理相关的事情
//                        console.log("BeforeUpload up:" + up + " file:" + JSON.stringify(file));
                    },
                    'UploadProgress': function (up, file) {
                        // 每个文件上传时，处理相关的事情
//                        console.log("UploadProgress up:" + up + " file:" + JSON.stringify(file));
                    },
                    'FileUploaded': function (up, file, info) {
                        // 每个文件上传成功后，处理相关的事情
                        // 其中info是文件上传成功后，服务端返回的json，形式如：
                        // {
                        //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                        //    "key": "gogopher.jpg"
                        //  }
                        console.log(JSON.stringify(info));
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        //获取上传成功后的文件的Url
                        var sourceLink = domain + res.key;
                        $("#file_name").val(sourceLink);
//                        console.log($("#pickfiles").attr('src'));
                    },
                    'Error': function (up, err, errTip) {
                        //上传出错时，处理相关的事情
                        console.log(err + errTip);
                    },
                    'UploadComplete': function () {
                        //队列文件处理完毕后，处理相关的事情
                    },
                    'Key': function (up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在unique_names: false，save_key: false时才生效

                        var key = "";
                        // do something with key here
                        return key
                    }
                }
            });
        }

        /*
         * 页面刷新
         *
         * By TerryQi
         *
         */
        function refresh() {
            {{--location.replace('{{URL::asset('/company/role0/worker/index')}}');--}}
            reloadPage();
        }

        //获取文件扩展名
        const extname = (filename) => {
            var str = "";
            //lastIndexOf()查找指定字符在字符串里面最后一次出现的下标，找不到的话返回-1
            var index1 = filename.lastIndexOf(".");
            //index1为0时代表第一个字符为'.',这种情况下没有后缀名
            if (index1 >= 1) {
                str = filename.substr(index1);
            }
            return str;
        }

    </script>
@endsection