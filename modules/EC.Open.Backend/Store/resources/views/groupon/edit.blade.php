{{--@extends('store-backend::dashboard')

@section ('title','修改拼团活动')

@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    <style type="text/css">
        input[type=file] {
            width: 72px;
            margin-top: 10px;
        }
    </style>
{{--@stop


@section('breadcrumbs')
    <h2>修改拼团活动</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">修改拼团活动</li>
    </ol>
@endsection

@section('content')--}}

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="row">

                <div class="panel-body">
                    <input type="hidden" id="selected_spu" value="{{$ids}}">

                    @if($groupon->check_status)
                        @include('store-backend::groupon.includes.disable_edit_form')
                    @else
                        @include('store-backend::groupon.includes.edit_form')
                    @endif

                </div>

            </div>
        </div>
    </div>

    <div id="modal" class="modal inmodal fade" data-keyboard=false data-backdrop="static"></div>
{{--@stop

@section('after-scripts-end')--}}

    @include('store-backend::groupon.includes.scripts')
    <script>
        $(function () {
            @if($groupon->check_status)
                 swal("提示", '活动已经开始，只能修改活动商品的参与状态', "warning");
            @endif
        });

        $('#base-form').ajaxForm({
            success: function (result) {
                if (!result.status) {
                    swal("保存失败!", result.message, "error")
                } else {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.promotion.groupon.index')}}';
                    });
                }
            }
        });
    </script>
{{--@stop--}}
