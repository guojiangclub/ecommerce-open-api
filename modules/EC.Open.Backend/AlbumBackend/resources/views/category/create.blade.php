@extends('file-manage::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    添加图片分组
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="row">
        {!! Form::model($category,['route' => ['admin.image-category.store']
           , 'class' => 'form-horizontal'
           , 'role' => 'form'
           , 'method' => 'post'
           ,'id'=>'edit_category_form']) !!}
        <div class="col-md-12">
            @include('file-manage::category.form')
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

    <button type="submit"  class="ladda-button btn btn-primary" data-style="slide-right" data-toggle="form-submit" data-target="#edit_category_form">保存
    </button>
    <script>
        $(document).ready(function () {
            $('#edit_category_form').ajaxForm({
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






