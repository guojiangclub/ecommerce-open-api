@extends('file-manage::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    图片上传
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="row">
        <div class="form-group uploadImage">
            <input type="hidden" value="{{$category_id}}" name="category_id">
            {!! Form::label('name','添加图片：', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                <div class="upload_result"></div>
                <div class="btn_upload_container"> + <input class="btn_upload" id="upload_img" multiple="" type="file" accept="image/*">
                </div>
                <div class="img_comntainer_info" id="imgContainerInfo"></div>

            </div>
        </div>
    </div>
@stop
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}


@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="button" class="ladda-button btn btn-primary" id="confirm_upload">保存
    </button>
    @include('file-manage::image.includes.upload_script')
    <script>

    </script>
@stop






