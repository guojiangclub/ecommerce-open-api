{{--@extends('store-backend::dashboard')

@section ('title','修改秒杀活动')

@section('after-styles-end')--}}
{!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
<style type="text/css">
    input[type=file] {
        width: 72px;
        margin-top: 10px;
    }

    .btn-circle {
        width: 25px;
        height: 25px;
        line-height: 0.5;
    }
</style>
{{--@stop


@section('breadcrumbs')
    <h2>修改秒杀活动</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">修改秒杀活动</li>
    </ol>
@endsection

@section('content')--}}

<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;">
        <div class="row">

            <div class="panel-body">
                <input type="hidden" id="selected_spu" value="{{$ids}}">

                @if($seckill->check_status==1)
                    @include('store-backend::seckill.includes.disable_edit_form')
                @elseif($seckill->check_status==2)
                    @include('store-backend::seckill.includes.invalid_edit_form')
                @else
                    @include('store-backend::seckill.includes.edit_form')
                @endif

            </div>

        </div>
    </div>
</div>

<div id="modal" class="modal inmodal fade" data-keyboard=false data-backdrop="static"></div>
{{--@stop

@section('after-scripts-end')--}}

@include('store-backend::seckill.includes.scripts')
<script>
    $(function () {
        @if($seckill->check_status==1)
             swal("提示", '活动已经开始，只能修改活动商品的参与和推荐状态', "warning");
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
                    location = '{{route('admin.promotion.seckill.index')}}';
                });
            }
        }
    });
</script>
{{--@stop--}}
