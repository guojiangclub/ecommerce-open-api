
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.save')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}

                <div class="form-group"><label class="col-sm-3 control-label">包ServiceProvider全称</label>

                    <div class="col-sm-8">
                        <input type="text" name="custom_package_class_name" value="{{settings('custom_package_class_name')}}"
                                                  class="form-control"></div>
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


    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}

    <script>
        $(function () {
            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal("保存成功!", "", "success")
                }
            });
        })
    </script>
