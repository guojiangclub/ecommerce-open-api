@extends ('cms::default.layout')
@section ('title','子推广管理')

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
@stop

@section ('breadcrumbs')
    <h2>添加子推广</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="">{!! link_to_route('ad.index', '推广位列表') !!}</li>
        <li class="active">添加子推广</li>
    </ol>
@stop

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('aditem.store')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
            <input type="hidden" name="ad_id" value="{{$ad_id}}">
            <input type="hidden" name="id" value="{{$aditem_list->id}}">


            <div class="form-group">
                {!! Form::label('name','子推广名称：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="name" placeholder="" value="{{$aditem_list->name}}">
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','子推广展示图片：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="hidden" name="image"  value="{{$aditem_list->image}}"/>
                    <img class="banner-image" style="max-height: 186px;"  src="{{$aditem_list->image}}">
                    <div id="filePicker">选择图片</div>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('name','链接地址：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="link"  placeholder="" value="{{$aditem_list->link}}">
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','排序：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="sort"  placeholder="" value="{{$aditem_list->sort}}">
                </div>
            </div>


            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8 controls">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>
        {!! Form::close() !!}

        <!-- /.tab-content -->
        </div>

    </div>
@endsection

@section('before-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    @include('store-backend::advertisement.ad_item.script')
@stop