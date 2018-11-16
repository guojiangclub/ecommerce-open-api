{{--@extends('member-backend::layout')--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/css/member_card.css') !!}
    {!! Html::style(env("APP_URL").'/assets/wechat-backend/libs/ladda/ladda-themeless.min.css') !!}
    <style type="text/css">
        table.category_table > tbody > tr > td {
            border: none
        }

        .sp-require {
            color: red;
            margin-right: 5px
        }

        .card-color-show {
            float: left;
            width: 60px;
            height: 20px;
            background-color: #{{$colors[0]['value']}};
        }

        .caret {
            float: left;
            margin-right: -5px;
        }

        .dropdown-menu-color li {
            box-sizing: border-box;
            float: left;
            margin-top: 5px;
        }

        .dropdown-menu-color {
            background-color : #fff;
            width: 190px;
            padding: 10px 0 6px;
            margin-left: 15px;
            margin-top: 5px;
            height:90px;
        }

        .dropdown-menu-color .card-color-box {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-left: 10px;
            cursor: pointer;
        }

        .dropdown-menu-color {
            border: medium none;
            border-radius: 3px;
            box-shadow: 0 0 3px rgba(86, 96, 117, 0.7);
            display: none;
            float: left;
            font-size: 12px;
            left: 0;
            list-style: none outside none;
            padding: 0;
            position: absolute;
            text-shadow: none;
            top: 100%;
            z-index: 1000;
        }
        .dropdown-menu-color > li > a {
            border-radius: 3px;
            color: inherit;
            line-height: 25px;
            margin: 4px;
            text-align: left;
            font-weight: normal;
        }
        .dropdown-menu-color > li > a.font-bold {
            font-weight: 600;
        }
        .navbar-top-links .dropdown-menu-color li {
            display: block;
        }
        .navbar-top-links .dropdown-menu-color li:last-child {
            margin-right: 0;
        }
        .navbar-top-links .dropdown-menu-color li a {
            padding: 3px 20px;
            min-height: 0;
        }
        .navbar-top-links .dropdown-menu-color li a div {
            white-space: normal;
        }

        .dropdown-toggle-color {
            display: block;
        }
    </style>
{{--@stop--}}


{{--@section('breadcrumbs')--}}
    {{--<h2>新建会员卡</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li>--}}
            {{--<a href="{!!route('admin.users.index')!!}">--}}
                {{--<i class="fa fa-dashboard"></i> 首页--}}
            {{--</a>--}}
        {{--</li>--}}
        {{--<li><a href="{{route('admin.member.card')}}">会员卡列表</a></li>--}}
        {{--<li class="active">新建会员卡</li>--}}
    {{--</ol>--}}
{{--@endsection--}}

{{--@section('content')--}}
    <div class="tabs-container">

        {!! Form::open( [ 'url' => [route('admin.member.card.store')], 'method' => 'POST', 'id' => 'create-card-form','class'=>'form-horizontal'] ) !!}

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="col-sm-8">
                        <h4>会员卡基础信息</h4>
                            <hr class="hr-line-solid">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="sp-require">*</span>商家名称：</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="store_name" placeholder="请填写商家名称" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)" required maxlength="20" />
                                </div>
                            </div>

                            <div class="form-group" id="card_cover_img">
                                <label class="col-md-2 control-label">商家logo：</label>
                                <div class="col-md-7">
                                    <div class="pull-left" id="activity-poster-logo">
                                        <img src="/assets/backend/activity/backgroundImage/pictureBackground.png" alt="" class="img" width="226px" height="91px" style=" margin-right: 23px;">
                                        <input type="hidden" class="form-control" id="store_logo" name="store_logo" value="">
                                    </div>

                                    <div id="filePickerLogo" class="pull-left clearfix" style="margin-left:30px; padding-top: 22px;">
                                        <span id="imguping_logo" data-style="expand-right" class="ladda-button">上传图片</span>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="store_logo_wx" name="store_logo_wx" value="">
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">卡片封面：</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline i-checks">
                                        <input name="card_cover" type="radio" class="card_cover" value="1" checked> 背景色
                                    </label>
                                    <label class="checkbox-inline i-checks">
                                        <input name="card_cover" type="radio" class="card_cover" value="2"> 背景图片
                                    </label>
                                </div>
                                <input type="hidden" name="card_cover_tmp" id="card_cover_tmp" value="1" />
                            </div>

                            <div class="form-group" id="card_cover_color">
                                <label class="col-sm-2 control-label">背景色：</label>
                                <div class="col-sm-3">
                                    <button class="btn dropdown-toggle-color" type="button" style="border-radius: inherit; background-color:white; border: 1px #e0dddd solid;">
                                        <div class="card-color-show"></div>
                                        <span class="caret" style="margin-left: 5px; margin-top: 5px;"></span>
                                    </button>
                                    <ul class="dropdown-menu-color">
                                        @foreach($colors as $color)
                                            <li><div class="card-color-box" style="background-color: #{{$color['value']}}" data-name="{{$color['name']}}" data-code="{{$color['value']}}"></div></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <input type="hidden" name="bg_color" id="bg_color" value="{{$colors[0]['value']}}" />
                                <input type="hidden" name="bg_color_name" id="bg_color_name" value="{{$colors[0]['name']}}" />
                            </div>

                            <div class="form-group" id="card_cover_img">
                                <label class="col-md-2 control-label">背景图片：</label>
                                <div class="col-md-7">
                                    <div class="pull-left" id="activity-poster">
                                        <img src={{isset($card) && !empty($card->bg_img) ? $card->bg_img : "/assets/backend/activity/backgroundImage/pictureBackground.png"}} alt="" class="img" width="226px" height="91px" style=" margin-right: 23px;">
                                        <input type="hidden" class="form-control" id="bg_img" name="bg_img" value="">
                                        <p id="error_message" style="color: #f44; display: none; margin-top: 5px; font-size: 12px;">请选择封面图片</p>
                                    </div>

                                    <div id="filePicker" class="pull-left clearfix" style="margin-left:30px; padding-top: 22px;">
                                        <span id="imguping" data-style="expand-right" class="ladda-button">上传图片</span>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="bg_img_wx" name="bg_img_wx" value="">
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="sp-require">*</span>会员卡名称：</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" placeholder="请填写会员卡名称" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)" required maxlength="9" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">添加会员信息类目：<br /><span style="color: red;margin-right: 14px;font-size: 10px;">激活后显示</span></label>
                                <div class="col-sm-6" id="custom_field_container">

                                </div>
                                <div>
                                    <button id="addButton" type="button" class="btn btn-w-m btn-primary">继续添加</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">添加中心按钮：<br /><span style="color: red;margin-right: 14px;font-size: 10px;">激活后显示</span></label>
                                <div class="col-sm-6" id="center_title_container">

                                </div>
                                <div>
                                    <button id="centerTitleButton" type="button" class="btn btn-w-m btn-primary">继续添加</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">添加营销入口：<br /><span style="color: red;margin-right: 14px;font-size: 10px;">第一个需激活后显示</span></label>
                                <div class="col-sm-6" id="custom_detail_container">

                                </div>
                                <div>
                                    <button id="customDetailButton" type="button" class="btn btn-w-m btn-primary">继续添加</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color: red">*</span>Code展示类型：</label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="code_type" required>
                                        <option value="">请选择</option>
                                        <option value="CODE_TYPE_TEXT">文本</option>
                                        <option value="CODE_TYPE_BARCODE">一维码</option>
                                        <option value="CODE_TYPE_QRCODE" selected>二维码</option>
                                        <option value="CODE_TYPE_ONLY_QRCODE">仅显示二维码</option>
                                        <option value="CODE_TYPE_ONLY_BARCODE">仅显示一维码</option>
                                        <option value="CODE_TYPE_NONE">不显示任何码型</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color: red">*</span>特权说明：</label>
                                <div class="col-sm-10">
                                    <textarea id="privilege_desc" class="form-control" name="privilege_desc" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color: red">*</span>使用说明：</label>
                                <div class="col-sm-10">
                                    <textarea id="use_description" class="form-control" name="use_description" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span style="color: red">*</span>使用提醒：</label>
                                <div class="col-sm-10">
                                    <textarea id="use_notice" class="form-control" name="use_notice" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">客服电话：</label>
                                <div class="col-sm-4">
                                    <input type="text" name="service_phone" id="service_phone" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">状态：</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline i-checks">
                                        <input name="status" type="radio" value="1" checked> 启用
                                    </label>
                                    <label class="checkbox-inline i-checks">
                                        <input name="status" type="radio" value="0"> 禁用
                                    </label>
                                </div>
                            </div>

                        {{--<h4>领取设置</h4>
                        <hr class="hr-line-solid">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color: red">*</span>升级条件：</label>
                            <div class="col-sm-10">
                               累计消费金额 <input type="text" name="upgrade_amount" class="sw-value" required />元
                            </div>
                        </div>--}}

                        {{--<div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color: red">*</span>等级计算周期：</label>
                            <div class="col-sm-10">
                                <input type="text" name="period" class="sw-value" value="730" required />天
                            </div>
                        </div>--}}

                        {{--<div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color: red">*</span>等级：</label>
                            <div class="col-sm-3">
                                <select class="form-control" name="grade" required>
                                    <option value="">请选择</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                                <p style="color: #b6b3b3; margin-top: 5px;">数字越大等级越高，当会员满足条件时会自动发放上一等级对应的会员卡</p>
                            </div>
                        </div>--}}

                        {{--<div class="form-group">
                            <label class="col-sm-2 control-label">激活设置：</label>
                            <div class="col-sm-10">
                                <label class="checkbox-inline i-checks">
                                    <input name="isActivate" type="radio" value="1" checked> 需要激活
                                </label>
                                <label class="checkbox-inline i-checks">
                                    <input name="isActivate" type="radio" value="0"> 无需激活
                                </label>
                                <p style="color: #b6b3b3; margin-top: 5px;">如需在线下门店使用，建议设置为“需要激活”</p>
                            </div>
                        </div>--}}

                        {{--<h4>同步设置</h4>
                        <hr class="hr-line-solid">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">核销方式：</label>
                            <div class="col-sm-10">
                                <label class="checkbox-inline i-checks">
                                    <input name="consumeType" type="radio" value="1" checked> 仅卡号
                                </label>
                                <label class="checkbox-inline i-checks">
                                    <input name="consumeType" type="radio" value="2"> 卡号和条形码
                                </label>
                                <label class="checkbox-inline i-checks">
                                    <input name="consumeType" type="radio" value="3"> 卡号和二维码
                                </label>
                                <label class="checkbox-inline i-checks">
                                    <input name="consumeType" type="radio" value="4"> 卡号,条形码,二维码
                                </label>
                            </div>
                        </div>--}}
                    </div>
                    <div class="col-sm-4">
                        @include('member-backend::.member_card.public.coupon_area')
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
{{--@stop--}}

{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    {!! Html::script(env("APP_URL").'/assets/wechat-backend/libs/ladda/spin.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/wechat-backend/libs/ladda/ladda.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/wechat-backend/libs/ladda/ladda.jquery.min.js') !!}
{{--@stop--}}

{{--@section('after-scripts-end')--}}
    @include('member-backend::member_card.public.script')
    <script>
        $(function () {
            // save return
            $('#create-card-form').ajaxForm({
                success: function (result) {
                    if (200 == result.code) {
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            window.location = '{{route('admin.member.card')}}';
                        });
                    } else {
                        swal("保存失败!", result.message, "error")
                    }

                }
            });
        });

        // 初始化Web Uploader
        $(document).ready(function () {
            var uploader = WebUploader.create({
                auto: true,
                swf: '{{url('libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('admin.wechat-api.upload.img',['_token'=>csrf_token()])}}',
                pick: '#filePicker',
                fileVal: 'file',
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });

            // 当有文件被添加进队列的时候
            uploader.on('fileQueued', function (file) {
                $('#imguping').ladda().ladda('start');
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function (file, response) {
                $('#imguping').ladda().ladda('stop');
                $('#activity-poster img').attr("src", response.source_url);
                $('#activity-poster input').val(response.source_url);
                $('#bg_img_wx').val(response.wechat_url);
                if ($('#card_cover_tmp').val() == 2) {
                    $('.card-region').attr('style', 'background-image:url("' + response.source_url + '")');
                }
                $('#error_message').hide();
            });

            var uploaderLogo = WebUploader.create({
                auto: true,
                swf: '{{url('libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('admin.wechat-api.upload.img',['_token'=>csrf_token()])}}',
                pick: '#filePickerLogo',
                fileVal: 'file',
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });

            // 当有文件被添加进队列的时候
            uploaderLogo.on('fileQueued', function (file) {
                $('#imguping_logo').ladda().ladda('start');
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploaderLogo.on('uploadSuccess', function (file, response) {
                $('#imguping_logo').ladda().ladda('stop');
                $('#activity-poster-logo img').attr("src", response.source_url);
                $('#activity-poster-logo input').val(response.source_url);
                $('#store_logo_wx').val(response.wechat_url);
                $('.shop-logo').attr('style', 'background-image:url("' + response.source_url + '")');
            });
        });
    </script>
{{--@stop--}}
