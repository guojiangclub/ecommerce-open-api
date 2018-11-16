<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="tabs.html#tab-1" aria-expanded="true">短信设置</a></li>
        <li class=""><a data-toggle="tab" href="tabs.html#tab-2" aria-expanded="false">通道设置</a></li>
    </ul>
    <form method="post" action="{{route('admin.setting.save')}}" class="form-horizontal"
          id="setting_site_form">
        {{csrf_field()}}
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">启用通道：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks">
                                <input name="phpsms[scheme][]" type="radio" value="Luosimao"
                                       @if(in_array('Luosimao',config('phpsms.scheme'))) checked @endif >
                                Luosimao</label>
                            <label class="checkbox-inline i-checks">
                                <input name="phpsms[scheme][]" type="radio" value="WE"
                                       @if(in_array('WE',config('phpsms.scheme'))) checked @endif> WE</label>
                            <label class="checkbox-inline i-checks">
                                <input name="phpsms[scheme][]" type="radio" value="Aliyun"
                                       @if(in_array('Aliyun',config('phpsms.scheme'))) checked @endif> Aliyun</label>

                            <label class="checkbox-inline i-checks">
                                <input name="phpsms[scheme][]" type="radio" value="AliyunMsn"
                                       @if(in_array('AliyunMsn',config('phpsms.scheme'))) checked @endif>
                                AliyunMsn</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Product</label>
                        <div class="col-sm-10">
                            <input type="text" name="laravel-sms[templateData][product]"
                                   {{--value="{{$phpSms['agents']['WE']['key'] or ''}}"--}}
                                   value="{{config('laravel-sms.templateData.product')}}"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">验证码短信通用内容</label>
                        <div class="col-sm-10">
                            <input type="text" name="laravel-sms[verifySmsContent]"
                                   value="{{$smsSetting['verifySmsContent'] or '【iBrand艾游】亲爱的用户，您的验证码是%s。有效期为%s分钟，请尽快验证。'}}"
                                   class="form-control">
                        </div>
                    </div>

                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle"></i> Luosimao
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">apikey</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phpsms[agents][Luosimao][apikey]"
                                           {{--value="{{$phpSms['agents']['Luosimao']['apikey'] or ''}}"--}}
                                           value="{{config('phpsms.agents.Luosimao.apikey')}}"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle"></i> WE
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">key</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phpsms[agents][WE][key]"
                                           {{--value="{{$phpSms['agents']['WE']['key'] or ''}}"--}}
                                           value="{{config('phpsms.agents.WE.key')}}"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle"></i> Aliyun
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">region_id</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phpsms[agents][Aliyun][region_id]"
                                           {{--value="{{$phpSms['agents']['WE']['key'] or ''}}"--}}
                                           value="{{config('phpsms.agents.Aliyun.region_id')}}"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">access_key</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phpsms[agents][Aliyun][access_key]"
                                           {{--value="{{$phpSms['agents']['WE']['key'] or ''}}"--}}
                                           value="{{config('phpsms.agents.Aliyun.access_key')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">access_secret</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phpsms[agents][Aliyun][access_secret]"
                                           {{--value="{{$phpSms['agents']['WE']['key'] or ''}}"--}}
                                           value="{{config('phpsms.agents.Aliyun.access_secret')}}"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">sign_name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phpsms[agents][Aliyun][sign_name]"
                                           {{--value="{{$phpSms['agents']['WE']['key'] or ''}}"--}}
                                           value="{{config('phpsms.agents.Aliyun.sign_name')}}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">key</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phpsms[agents][Aliyun][template_code]"
                                           {{--value="{{$phpSms['agents']['WE']['key'] or ''}}"--}}
                                           value="{{config('phpsms.agents.Aliyun.template_code')}}"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-content m-b-sm border-bottom text-right">
                <button class="btn btn-primary" type="submit">保存设置</button>
            </div>
        </div>

    </form>
</div>

<script>
    $(function () {
        $('#setting_site_form').ajaxForm({
            success: function (result) {
                swal("保存成功!", "", "success")
            }
        });
    })
</script>