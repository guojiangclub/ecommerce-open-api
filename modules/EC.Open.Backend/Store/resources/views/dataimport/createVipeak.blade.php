@extends ('store-backend::layouts.default')
@section ('title','线下数据导入')
@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
@stop

@section ('breadcrumbs')
    <h2>线下数据导入</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">批量导入线下会员卡数据</li>
    </ol>
@stop

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
    <div class="nav-tabs-custom">
        <div class="tab-content div_1" >
            {!! Form::open( [ 'url' => [route('admin.dataimport.importVipeak')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}

                        <div class="tab-pane active" id="tab_1">
                            <div class="form-group">
                                {!! Form::label('name','批量导入线下会员卡数据：', ['class' => 'col-lg-4 control-label']) !!}
                                <div class="col-lg-8">
                                    <input type="hidden" name="upload_excel"/>
                                    <div id="filePicker">选择文件</div>
                                    <p class="update_true"></p>
                                </div>
                            </div>
                                <div class="well">
                                    <div class="pull-left">
                                        <input type="button" class="btn btn-success disabled addbut"   aria-disabled="true" value="保存">
                                    </div>

                                    <div class="clearfix"></div>
                                </div>


            {!! Form::close() !!}
            </div>
    </div>
        </div>
@endsection

@section('before-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    @include('store-backend::dataimport.script2')

@stop
