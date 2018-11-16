    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.save')}}" class="form-horizontal"
                  id="setting_site_form">

                {{csrf_field()}}
                <div class="form-group"><label class="col-sm-3 control-label">*微信公众号appid</label>
                    <div class="col-sm-9"><input type="text" name="wechat_app_id" placeholder="" class="form-control"
                                                 value="{{settings('wechat_app_id')}}"></div>
                </div>

                <div class="form-group"><label class="col-sm-3 control-label">*微信公众号appsecret</label>
                    <div class="col-sm-9"><input type="text" name="wechat_app_secret" placeholder="" class="form-control"
                                                 value="{{settings('wechat_app_secret')}}"></div>
                </div>


                <div class="form-group"><label class="col-sm-3 control-label">商城小程序APPID</label>
                    <div class="col-sm-9"><input type="text" name="mini_program_app_id" placeholder="" class="form-control"
                                                 value="{{settings('mini_program_app_id')}}"></div>
                </div>

                <div class="form-group"><label class="col-sm-3 control-label">商城小程序SECRET</label>
                    <div class="col-sm-9"><input type="text" name="mini_program_secret" placeholder="" class="form-control"
                                                 value="{{settings('mini_program_secret')}}"></div>
                </div>

                <div class="form-group"><label class="col-sm-3 control-label">活动小程序APPID</label>
                    <div class="col-sm-9"><input type="text" name="activity_mini_program_app_id" placeholder="" class="form-control"
                                                 value="{{settings('activity_mini_program_app_id')}}"></div>
                </div>

                <div class="form-group"><label class="col-sm-3 control-label">活动小程序SECRET</label>
                    <div class="col-sm-9"><input type="text" name="activity_mini_program_secret" placeholder="" class="form-control"
                                                 value="{{settings('activity_mini_program_secret')}}"></div>
                </div>


                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">保存设置</button>
                    </div>
                </div>
            </form>
        </div>
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