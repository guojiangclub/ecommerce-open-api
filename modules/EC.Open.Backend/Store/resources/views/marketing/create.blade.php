@extends ('store-backend::dashboard')

@section ('title','新建事件')

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}
@stop


@section ('title','新建事件')


@section ('breadcrumbs')
    <h2>新建事件</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="">{!! link_to_route('admin.goods.model.index', '事件管理',['type'=>$type]) !!}</li>
        <li class="active">新建{{$name}}事件</li>
    </ol>
@stop

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('admin.marketing.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}

            @include('store-backend::marketing.includes.create_base')

            @if($type=='GOODS_REGISTER')
                @include('store-backend::marketing.includes.create_rule')
            @endif

            @include('store-backend::marketing.includes.create_action')

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

    <div id="spu_modal" class="modal inmodal fade"></div>
@endsection

@section('after-scripts-end')
    <script type="text/html" id="selected-coupons-temp">
        <tr>
            <td>
                {#title#}
            </td>
            <td>
                {#starts_at#}至<br>
                {#ends_at#}
            </td>
            <td>
                {#status_text#}
            </td>
            <td>
                <button onclick="delete_selected(this)" class="btn btn-xs btn-danger"
                        type="button" data-id="{#id#}">
                    <i data-toggle="tooltip" data-placement="top"
                       class="fa fa-trash"
                       title="删除"></i>
                </button>
            </td>
        </tr>
    </script>

    @include('store-backend::marketing.includes.script')

    <script>
        $('#base-form').ajaxForm({
            success: function (result) {
                if (result.status) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.marketing.index',['type'=>$type])}}';
                    });
                } else {
                    swal("保存失败!", result.message, "error");
                }
            }
        });
    </script>
@stop
