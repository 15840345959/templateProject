<div class="layui-form" lay-filter="">
    <div class="layui-form-item hidden">
        <label class="layui-form-label">id</label>
        <div class="layui-input-inline">
            <input type="text" name="id" value="{{$data->id}}" class="layui-input"
                   lay-verify=""
                   placeholder="id">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" name="name" value="{{$data->name}}" class="layui-input"
                   lay-verify="required"
                   placeholder="请输入名称">
        </div>
        <div class="layui-form-mid layui-word-aux"></div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否开放</label>
        <div class="layui-input-inline">
            <input type="checkbox" id="type"
                   name="type" lay-skin="switch"
                   {{$data->type=='1' ? 'checked':''}}
                   lay-filter="LAY-type" lay-text="开放|内部">
        </div>
        <div class="layui-form-mid layui-word-aux">开放工作包可以被全平台抢单</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">城市</label>
        <div class="layui-input-inline">
            <select name="city_id" lay-verify="required">
                <option value="">请选择</option>
                @foreach($citys as $city)
                    <option value="{{$city->id}}" {{$data->city_id == $city->id? "selected":""}}>{{$city->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">发包方</label>
        <div class="layui-input-inline">
            <select name="man_company_id" lay-filter="LAY-man-company-id" lay-verify="required">
                <option value="">请选择</option>
                @foreach($man_companys as $man_company)
                    <option value="{{$man_company->id}}" {{$data->man_company_id == $man_company->id? "selected":""}}>{{$man_company->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="layui-input-inline">
            <select id="bus_company_id" name="bus_company_id" lay-filter="bus_company_id"
                    lay-verify="required">
                <option value="">请选择</option>
                @foreach($bus_companys as $bus_company)
                    <option value="{{$bus_company->id}}" {{$data->bus_company_id == $bus_company->id? "selected":""}}>{{$bus_company->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">工作</label>
        <div class="layui-input-inline">
            <select name="job_id" lay-filter="LAY-job-id" lay-verify="required">
                <option value="">请选择</option>
                @foreach($jobs as $job)
                    <option value="{{$job->id}}" {{$data->job_id == $job->id? "selected":""}}>{{$job->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="layui-form-mid layui-word-aux"></div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">子工作</label>
        <div class="layui-input-block">
            <div id="sub_job_ids_div">
                {{--如果没有子工作--}}
                @if(count($sub_jobs)==0)
                    <input type="checkbox" name="sub_job_ids[]" value=""
                           title="请先选择工作" disabled>
                @else
                    @foreach($sub_jobs as $sub_job)
                        <input type="checkbox" name="sub_job_ids[]" value="{{$sub_job->id}}"
                               title="{{$sub_job->name}}" {{$sub_job->checked}}>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-inline">
            <input type="text" name="seq" value="{{isset($data->seq)?$data->seq:'0'}}"
                   class="layui-input"
                   lay-verify="required"
                   placeholder="请输入排序">
        </div>
        <div class="layui-form-mid layui-word-aux">排序越大越靠前</div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">工作要求</label>
        <div class="layui-input-block">
            <textarea name="intro" placeholder="请输入内容"
                      class="layui-textarea">{{$data->intro}}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="LAY-form-edit">保存信息</button>
            <button type="reset" class="layui-btn layui-btn-primary" onclick="reloadPage();">
                重新填写
            </button>
        </div>
    </div>
</div>

<!--二级分类预选项-->
<script id="sub-job-content" type="text/x-dot-template">
    @{{for(var i=0;i
    <it.length ;i++){}}
    <input type="checkbox" name="sub_job_ids[]" value="@{{=it[i].id}}"
           title="@{{=it[i].name}}">
    @{{}}}
</script>

<script id="bus-company-content" type="text/x-dot-template">
    <option value="">请选择</option>
    @{{for(var i=0;i
    <it.length ;i++){}}
    <option value="@{{=it[i].id}}">@{{=it[i].name}}</option>
    @{{}}}
</script>

<script type="text/javascript">

    var layer = null;
    var form = null;

    var type_swith_val = '{{$data->type}}';     //定义type


    $(function () {
        //初始化七牛模块
        initQNUploader();

    });

    //配置轮播图实例
    layui.use(['carousel', 'index', 'layer', 'form', 'set', 'laypage', 'laydate'], function () {
        layer = layui.layer;        //初始化layer
        form = layui.form;

        form.render();

        var laydate = layui.laydate;

        laydate.render({
            elem: '#birthday'
            , calendar: true
            , trigger: 'click'
        });

        //监听变更
        form.on('select(LAY-job-id)', function (data) {
            consoledebug.log("data:" + JSON.stringify(data));
            var job_id = data.value;
            var param = {
                job_id: job_id,
            }
            ajaxRequest('{{URL::asset('')}}' + "api/subJob/getListByCon", param, "GET", function (ret) {
                if (ret.result == true) {
                    consoledebug.log("ret:" + JSON.stringify(ret));
                    var interText = doT.template($("#sub-job-content").text());
                    $("#sub_job_ids_div").html(interText(ret.ret.data))
                    form.render();
                }
            });
        });

        //监听变更
        form.on('select(LAY-man-company-id)', function (data) {
            consoledebug.log("data:" + JSON.stringify(data));
            var man_company_id = data.value;
            var param = {
                man_company_id: man_company_id
            }
            ajaxRequest('{{URL::asset('')}}' + "api/busCompany/getListByCon", param, "GET", function (ret) {
                if (ret.result == true) {
                    consoledebug.log("ret:" + JSON.stringify(ret));
                    var interText = doT.template($("#bus-company-content").text());
                    $("#bus_company_id").html(interText(ret.ret.data))
                    form.render();
                }
            });
        });

        //监听开关
        form.on('switch(LAY-type)', function (data) {
            consoledebug.log("data:" + JSON.stringify(data));
            if (data.elem.checked) {
                type_swith_val = "1";
            } else {
                type_swith_val = "0";
            }
        });

        //表单提交text
        form.on('submit(LAY-form-edit)', function (data) {
            var param = data.field;
            param.type = type_swith_val;
            //如果带富媒体编辑器，则需要单独获取值
            // param.content_html = ue.getContent();
            consoledebug.log("param:" + JSON.stringify(param));
            var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

            //进行请求
            ajaxRequest('{{URL::asset('/company/role1/jobOrder/edit')}}', param, "POST", function (ret) {
                if (ret.result) {
                    layer.msg('保存成功', {icon: 1, time: 1000});
                    location.replace('{{URL::asset('/company/role1/jobOrder/edit')}}?item={{$item}}&id=' + ret.ret.id);
                } else {
                    layer.msg(ret.message, {icon: 5, time: 2000});
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
                        // 文件添加进队列后，处理相关的事情
//                                            alert(alert(JSON.stringify(file)));
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
                    $("#img").val(sourceLink);
                    $("#pickfiles").attr('src', sourceLink);
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

</script>