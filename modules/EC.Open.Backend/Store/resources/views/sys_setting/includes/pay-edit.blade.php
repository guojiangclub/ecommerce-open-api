<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;">
        <h2 class="box-header with-border text-center">
            {{$title}}
        </h2>
        <form method="post" action="{{route('admin.setting.pay')}}" class="form-horizontal"
              id="setting_site_form">
            {{csrf_field()}}


            @if($type=='wechat')
                @include('store-backend::sys_setting.includes.pay-wechat')
            @elseif($type=='mini_program')
                @include('store-backend::sys_setting.includes.pay-mini')
            @elseif($type=='alipay_wap' OR $type=='alipay_web')
                @include('store-backend::sys_setting.includes.pay-alipay-wap')
            @endif

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8 controls">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>

            {!! Form::close() !!}
                    <!-- /.tab-content -->
    </div>
</div>

<script>
    $(function () {
        $('#setting_site_form').ajaxForm({
            success: function () {
                swal("保存成功!", "", "success")
            }
        });
    });
</script>
