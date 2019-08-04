@extends('company.layouts.app')

@section('content')

    <div class="layui-fluid">

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        绑定状态
                    </div>
                    <div class="layui-card-body">
                        <table class="layui-table">
                            <tbody>
                            <tr>
                                <td>{{$data->name}}</td>
                                <td>{{$data->role_str}}</td>
                                <td>{{$data->phonenum}}</td>
                                <td>{{isset($data->city)?$data->city->name:'--'}}</td>
                                <td>绑定用户：{{isset($data->user)?$data->user->nick_name:'--'}}</td>
                                <td style="text-align: center;">
                                    <span class="c-999">如需解绑请联系业务管理员</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-row layui-col-space15">
            <div class="layui-col-md8">
                <div class="layui-card">
                    <div class="layui-card-header">
                        员工工卡使用FAQ
                    </div>
                    <div class="layui-card-body">
                        <ul class="layuiadmin-card-status layuiadmin-home2-usernote">
                            <li>
                                <h3>员工工卡是什么</h3>
                                <p>
                                    为了提升企业形象，一般企业会为工作人员生成工卡，工卡中展示员工的姓名、工号、职务、所属公司等信息，以便标识员工的角色和权益，进而使得企业的经营更具有仪式感和规范性。本项目生成的员工工卡为750*1335尺寸，足够一般的工卡尺寸印刷使用。</p>
                            </li>
                            <li>
                                <h3>本项目中如何应用工卡</h3>
                                <p>
                                    本项目中生成的工卡，更加具有实际意义。根据前期的调研和沟通，本项目为众包平台，需要物业管理员/项目管理员在手机端完成员工工作打卡、工作汇报审核等操作。为了方便物业管理员/项目管理员使用小程序，可以通过扫码工卡中的二维码，完成小程序账号的绑定，只要您将工卡转发给相应的管理员，管理员通过扫描工卡二维码的方式，即可绑定账号。绑定账号后，在小程序中可以进行员工考核、代打卡等工作，其功能与Web版一致，从而解决了可能存在的终端不足、网络环境不具备的问题，进而也提升了运营了灵活性，助力业务发展。</p>
                            </li>
                            <li>
                                <h3>员工工卡的有效期以及错误绑定怎么办</h3>
                                <p>
                                    员工工卡中的二维码是根据员工的信息实时生成的，员工有效性的控制、角色的控制和有效期的控制均在管理后台，因此员工绑定工卡后，仍会受到管理后台的控制。如果员工的工卡被错误的绑定了，那么可以进行解绑，从而取消小程序用户与员工的绑定关系。另外，针对店员工卡绑定功能，生成的小程序码和员工工号都进行了金融级别的加密处理，很难伪造，因此请放心使用吧。</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="layui-card">
                    <div class="layui-card-header">
                        样式预览
                        <a href="{{URL::asset('/img/companyUserCard/'.$filename)}}" target="_blank"
                           class="layui-a-tips">下载</a>
                    </div>
                    <div class="layui-card-body">
                        <div style="text-align: center;">
                            <img src="{{URL::asset('/img/companyUserCard/'.$filename)}}" style="width: 90%;">
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
        });


        /*重置密码*/
        function unbindWX(obj, id) {
            //此处请求后台程序，下方是成功后的前台处理
            layer.confirm('确认要解绑微信？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    user_id: '',
                    _token: "{{ csrf_token() }}"
                }
                ajaxRequest('{{URL::asset('')}}' + "company/role0/companyUser/unbindWX/" + id, param, "GET", function (ret) {
                    if (ret.result == true) {
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    }
                });
                layer.msg('解绑成功', {icon: 6, time: 1000});
            });
        }


    </script>
@endsection