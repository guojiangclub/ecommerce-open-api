@extends('file-manage::layouts.bootstrap_modal')

@section('modal_class')
    modal-md
@stop
@section('title')
    修改名称
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="row">
        {!! Form::open( [ 'url' => [route('admin.image.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}
        <div class="form-group">
            <input value="{{$image->id}}" type="hidden" name="id">
            {!! Form::label('name','图片名称：', ['class' => 'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                <input type="text" class="form-control" name="name" placeholder="" value="{{$image->name}}">
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}


@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="submit"  class="ladda-button btn btn-primary" data-style="slide-right" data-toggle="form-submit" data-target="#base-form">保存
    </button>
<script>
    $(document).ready(function () {
        $('#base-form').ajaxForm({
            success: function (result) {
                if (result.status) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location.reload();
                    });
                } else {

                    swal("", result.message, "error");
                }

            }
        });
    });
</script>
@stop






