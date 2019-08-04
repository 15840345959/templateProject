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
        <label class="layui-form-label">性别要求</label>
        <div class="layui-input-inline">
            <div style="width: 650px;">
                @foreach(\App\Components\Project::JOB_ORDER_GENDER_VAL as $key=>$value)
                    <input type="radio" name="gender" value="{{$key}}" title="{{$value}}"
                           lay-verify="required" {{$data->gender==strval($key) ? 'checked':''}}>
                @endforeach
            </div>
        </div>
        <div class="layui-form-mid layui-word-aux"></div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">年龄要求</label>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" name="min_age" placeholder="周岁" value="{{$data->min_age}}" autocomplete="off"
                   class="layui-input"
                   lay-verify="required|number">
        </div>
        <div class="layui-form-mid">-</div>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" name="max_age" placeholder="周岁" value="{{$data->max_age}}" autocomplete="off"
                   class="layui-input"
                   lay-verify="required|number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">招聘人数</label>
        <div class="layui-input-inline">
            <input type="text" name="people_num" value="{{$data->people_num}}" class="layui-input"
                   lay-verify="required|number"
                   placeholder="请输入招聘人数">
        </div>
        <div class="layui-form-mid layui-word-aux">0为不限制人数</div>
    </div>
    <div class="layui-form-item">
        <div style="height: 1px;background: #F1F1F1;"></div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">工作日</label>
        <div class="layui-input-block">
            @foreach(\App\Components\Common\Utils::WEEK_VAL as $key=>$value)
                <input type="checkbox" name="week_times[]" value="{{$key}}"
                       {{strpos(strval($data->week_times),strval($key)) !== false ? 'checked':''}}
                       title="{{$value}}">
            @endforeach
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">月工资</label>
        <div class="layui-input-inline">
            <input type="text" id="monthly_pay" name="monthly_pay" value="{{$data->monthly_pay}}" class="layui-input"
                   lay-verify="required|number" placeholder="元" oninput="inputMonthlyPay();"
                   placeholder="请输入每月的框架工资">
        </div>
        <div class="layui-form-mid layui-word-aux">每月计划工资额度，根据该额度、每日工作时长和月份天数可估算出小时工资</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">计算日工资</label>
        <div class="layui-input-inline">
            <div class="layui-form-mid ml-5">
                <span id="daily_pay"></span><span class="ml-5">元</span>
            </div>
        </div>
        <div class="layui-form-mid layui-word-aux">日工资=月公司/30天</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">每日时长</label>
        <div class="layui-input-inline">
            <input type="text" id="hours_per_day" name="hours_per_day"
                   value="{{isset($data->hours_per_day) ? $data->hours_per_day:'3'}}"
                   class="layui-input" oninput="inputHoursPerDay();"
                   lay-verify="required|number" placeholder="小时"
                   placeholder="请输入每日工作时长">
        </div>
        <div class="layui-form-mid layui-word-aux">非全日制用工每日工作不超过4小时，每周工作不超过24小时</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">小时工资</label>
        <div class="layui-input-inline">
            <input type="text" id="hourly_pay" name="hourly_pay" value="{{$data->hourly_pay}}" class="layui-input"
                   lay-verify="required|number" placeholder="小时工资"
                   placeholder="请输入小时工资数">
        </div>
        <div class="layui-form-mid layui-word-aux">
            @if(isset($data->city))
                <span class="">{{$data->city->name}}的最低工资为 {{$data->city->min_wage}}元/小时</span>
            @endif
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


<script type="text/javascript">


    var layer = null;
    var form = null;


    $(function () {
        //初始化七牛模块
        initQNUploader();
        //计算工资
        computeDailyPay();
        computeHourlyPay();
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

        //表单提交text
        form.on('submit(LAY-form-edit)', function (data) {
            var param = data.field;
            //如果带富媒体编辑器，则需要单独获取值
            // param.content_html = ue.getContent();
            consoledebug.log("param:" + JSON.stringify(param));
            var loadding_index = layer.load(1, {time: 10 * 1000});   //加载

            //进行请求
            ajaxRequest('{{URL::asset('/admin/jobOrder/edit')}}', param, "POST", function (ret) {
                if (ret.result) {
                    layer.msg('保存成功', {icon: 1, time: 1000});
                    location.replace('{{URL::asset('/admin/jobOrder/edit')}}?item={{$item}}&id=' + ret.ret.id);
                } else {
                    layer.msg(ret.message, {icon: 5, time: 2000});
                }
                layer.close(loadding_index);
            });
        });
    });

    //输入月工资，将根据月公司计算日工资和小时工资
    function inputMonthlyPay() {
        computeDailyPay();
        computeHourlyPay();
    }

    //输入每日工作小时，将计算小时工资
    function inputHoursPerDay() {
        computeHourlyPay();
    }


    //计算日工资
    function computeDailyPay() {
        var monthly_pay = $("#monthly_pay").val();
        var daily_pay = 0;
        if (!judgeIsAnyNullStr(monthly_pay)) {
            daily_pay = (monthly_pay / 30).toFixed(2);
        }
        $("#daily_pay").text(daily_pay);
        return daily_pay;
    }

    //计算小时工资
    function computeHourlyPay() {
        var daily_pay = computeDailyPay();
        var hours_per_day = $("#hours_per_day").val();
        var hourly_pay = 0;
        if (!judgeIsAnyNullStr(hours_per_day) && hours_per_day != 0) {
            hourly_pay = (daily_pay / hours_per_day).toFixed(2);
        }
        $("#hourly_pay").val(hourly_pay);
    }


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