{{--@extends('store-backend::dashboard')

@section ('title','优惠券编辑')

@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}
    {!! Html::style('assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    <style type="text/css">
        table.category_table > tbody > tr > td {
            border: none
        }

        .sp-require {
            color: red;
            margin-right: 5px
        }
    </style>
{{--@stop


@section('breadcrumbs')--}}
   {{-- <h2>优惠券编辑</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li><a href="{{route('admin.promotion.coupon.index')}}">优惠券列表</a></li>
        <li class="active">优惠券编辑</li>
    </ol>
@endsection

@section('content')--}}
    <div class="tabs-container">

        {!! Form::open( [ 'url' => [route('admin.promotion.coupon.store')], 'method' => 'POST', 'id' => 'create-discount-form','class'=>'form-horizontal'] ) !!}
        <input type="hidden" value="{{$discount->id}}" name="id">
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="col-sm-8">
                        <fieldset class="form-horizontal">
                            @include('store-backend::promotion.coupon.includes.edit_base')
                        </fieldset>

                        <fieldset class="form-horizontal">
                            @include('store-backend::promotion.public.edit_rule')
                        </fieldset>

                        <fieldset class="form-horizontal">
                            @include('store-backend::promotion.coupon.includes.edit_action')
                        </fieldset>
                    </div>

                    <div class="col-sm-4">
                        @include('store-backend::promotion.coupon.includes.coupon_area')
                    </div>

                </div>
            </div>
        </div>

        <div class="hr-line-dashed"></div>
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
                <button class="btn btn-primary" type="submit">保存设置</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div id="spu_modal" class="modal inmodal fade"></div>
    <div id="wechat_modal" class="modal inmodal fade"></div>
{{--@stop

@section('after-scripts-end')--}}
{{--    {!! Html::script('assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}--}}
    @include('store-backend::promotion.public.script')
    @include('store-backend::promotion.coupon.includes.coupon_area_script')
    <script>
        $(function () {
            $('.goodsSku').tagator({});

            $.iCheckAll({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%',
                prefix: 'dep'
            });

            //action Initialization
            var value = $('.action-select').children('option:selected').val();

            if (value == 'order_fixed_discount') {
                var action_html = $('#discount_action_template').html();
            }

            if (value == 'goods_fixed_discount') {
                var action_html = $('#goods_discount_action_template').html();
            }

            if (value == 'order_percentage_discount') {
                var action_html = $('#percentage_action_template').html();
            }

            if (value == 'goods_percentage_discount' || value == 'goods_percentage_by_market_price_discount') {
                var action_html = $('#goods_percentage_action_template').html();
            }

            $('#promotion-action').html(action_html.replace(/{VALUE}/g, '{{$discount->discountAction?$discount->discountAction->ActionValue:0}}'));


            // save return
            $('#create-discount-form').ajaxForm({
                success: function (result) {
                    if (result.status) {
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            window.location = '{{route('admin.promotion.coupon.index')}}';
                        });
                    } else {
                        swal("保存失败!", result.message, "error")
                    }

                }
            });
        });

        // 初始化Web Uploader
        $(document).ready(function () {
            var postImgUrl = '{{route('upload.image',['_token'=>csrf_token()])}}';
            // 初始化Web Uploader
            var uploader = WebUploader.create({
                auto: true,
                swf: '{{url('assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                server: postImgUrl,
                pick: '#filePicker',
                fileVal: 'upload_image',
                accept: {
                    title: 'Images',
                    extensions: 'jpg,jpeg,png',
                    mimeTypes: 'image/jpg,image/jpeg,image/png'
                }
            });
            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function (file, response) {
                $('#activity-poster img').attr("src", response.url);
                $('#activity-poster input').val( response.url);
            });
        });
    </script>
{{--@stop--}}
