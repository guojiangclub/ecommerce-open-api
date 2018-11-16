@extends ('store-backend::layouts.default')
@section ('title','线下数据导入')
@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
@stop

@section ('breadcrumbs')
    <h2>线下数据导入</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">批量导入线下订单数据</li>
    </ol>

@stop

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="nav-tabs-custom">
                <div class="tab-content div_1">

                    <div class="tab-pane active" id="tab_1">
                        <div class="form-group">
                            {!! Form::label('name','批量导入线下订单数据：', ['class' => 'col-lg-4 control-label']) !!}
                            <div class="col-lg-8">
                                <div id="filePicker">选择文件</div>
                                <p class="update_true"></p>
                                <input type="hidden" name="excel_file">
                                <div class="myss"></div>
                            </div>
                        </div>
                        <div class="well">
                            <div class="pull-left">
                                <button class="btn btn-success add-button" data-toggle="modal-filter"
                                        disabled
                                        data-target="#modal" data-backdrop="static" data-keyboard="false"
                                        data-url="{{route('admin.dataimport.importOrderModal')}}">
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
    @include('store-backend::dataimport.script')
@stop
