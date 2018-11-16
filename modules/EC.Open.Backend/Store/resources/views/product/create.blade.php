@extends('store-backend::dashboard')
@section ('title','导入SKU编码')
@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
@stop

@section ('breadcrumbs')
    <h2>导入SKU编码</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">导入SKU编码</li>
    </ol>

@stop

@section('content')
    {{--<h2 class="page-header">@if($add==1) 添加商品注册码 @else 批量导入商品注册码 @endif</h2>--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="nav-tabs-custom">
                <div class="tab-content div_1">


                    <div class="tab-pane active" id="tab_1">
                        <div class="form-group">
                            {!! Form::label('name','导入SKU编码：', ['class' => 'col-lg-2 control-label']) !!}
                            <div class="col-lg-9">
                                <input type="hidden" name="upload_excel"/>
                                <div id="filePicker">选择文件</div>
                                <p class="update_true"></p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="well">
                            <div class="pull-left">

                                <button class="btn btn-success add-button" data-toggle="modal-filter"
                                        disabled
                                        data-target="#modal" data-backdrop="static" data-keyboard="false"
                                        data-url="{{route('admin.goods.barCode.importBarCodeModal')}}">
                                    执行导入
                                </button>

                            </div>

                            <div class="clearfix"></div>
                        </div>


                    </div>
                </div>
            </div>

            <div id="modal" class="modal inmodal fade" data-keyboard=false data-backdrop="static"></div>
@endsection

@section('before-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    @include('store-backend::product.script')
@stop
