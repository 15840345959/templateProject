@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">日志文件</label>
                    <div class="layui-input-block">
                        <select id="logfile" name="top" lay-filter="file" data-type="changeLogfile">
                            @foreach($data['files'] as $file)
                            <option value="{{ $file }}" @if($data['current_file'] == $file) selected @endif>{{ $file }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-card-body">
                <table id="LAY-mmk-common-data" lay-filter="LAY-mmk-common-data"
                       data-url="{{ route('get_logs') }}"
                       lay-contentType='application/json'
                       data-field="{{ json_encode([
                    ['field'=>'level','title'=>'错误等级','width'=>150,"templet"=>'#level'],
                ['field'=>'level_img','hide'=>true],
                ['field'=>'level_class','hide'=>true],
                ['field'=>'context','title'=>'Context','width'=>100],
                ['field'=>'date','title'=>'日期','width'=>200,'sort'=>true],
                ['field'=>'text','title'=>'内容',"templet"=>'#context'],
                ])}}"
                data-toolbar="buttons"
                ></table>
            </div>
        </div>
        @endsection

        @section('js')
        {{-- 设置 错误等级样式 --}}
        <script type="text/html" id="level">
            @{{#  if(d.level_class == 'danger'){ }}
            <span style="color:#FF5722" class="fa fa-@{{ d.level_img }}" aria-hidden="true">  @{{ d.level }}</span>
            @{{#  } else if(d.level_class == 'warning') { }}
            <span style="color:#FFB800" class="fa fa-@{{ d.level_img }}" aria-hidden="true">  @{{ d.level }}</span>
            @{{#  } else { }}
            <span style="color:#5FB878" class="fa fa-@{{ d.level_img }}" aria-hidden="true">  @{{ d.level }}</span>
            @{{#  } }}
        </script>

        {{-- 工具栏按钮 --}}
        <script type="text/html" id="buttons">
            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-sm" lay-event="Downloadfile" >下载文件</button>
                <button class="layui-btn layui-btn-sm" lay-event="CleanFile">清空文件</button>
                <button class="layui-btn layui-btn-sm" lay-event="DeleteFile">删除文件</button>
                <button class="layui-btn layui-btn-sm" lay-event="DeleteFileAll">删除所有Log</button>
            </div>
        </script>

        <script type="text/javascript">
            layui.use(['form','table'],function(){
                var form = layui.form;
                var table = layui.table

                form.on('select(file)',function(data){
                    var type = $(data.elem).data('type');
                    active[type] ? active[type].call(this,data.value) : '';
                })

                var active =  {
                    changeLogfile: function(file){
                        //执行重载
                        table.reload('LAY-mmk-common-data', {
                            page: {
                                curr: 1 //重新从第 1 页开始
                            }
                            ,url:"{{ route('get_logs') }}?l="+file
                        });
                    },
                    ajaxs:function(current_file,type){
                        if(type == 'dl')
                        {
                            location.href = '{{ route('get_logs') }}?'+type+"="+current_file;
                        }else{
                            layer.confirm('Are you sure?',{
                                offset: '100px'
                            },function(){
                                var ajax_lod = layer.load(1);
                                $.ajax({
                                    type:"GET",
                                    url:'{{ route('get_logs') }}?'+type+"="+current_file,
                                    data:{
                                        _token:'{{ csrf_token() }}'
                                    },
                                    dataType: "json",
                                    success: function(data){
                                        layer.close(ajax_lod);
                                        if(data.status == 1)
                                        {
                                            layer.msg(data.msg,{icon: 1,offset: '100px'})
                                        }
                                        setTimeout(function(){ location.reload() }, 500);
                                    }
                                    ,error:function(XMLHttpRequest, textStatus, errorThrow){
                                        layer.close(ajax_lod);
                                        var ret_json = XMLHttpRequest.responseJSON;
                                        layer.msg('请求失败,系统异常:'+ret_json.message,{icon: 5,anim:6,offset: '100px'})
                                    }
                                });
                            })
                        }
                    }
                }

                //工具头监听
                table.on('toolbar(LAY-mmk-common-data)', function(obj){
                    var checkStatus = table.checkStatus(obj.config.id);
                    switch(obj.event){
                        case 'Downloadfile':
                            active.ajaxs.call(this,$('#logfile').val(),'dl')
                            break;
                        case 'CleanFile':
                            active.ajaxs.call(this,$('#logfile').val(),'clean')
                            break;
                        case 'DeleteFile':
                            active.ajaxs.call(this,$('#logfile').val(),'del')
                            break;
                        case 'DeleteFileAll':
                            active.ajaxs.call(this,$('#logfile').val(),'delall')
                            break;
                    };
                });
            });
        </script>
        @endsection
