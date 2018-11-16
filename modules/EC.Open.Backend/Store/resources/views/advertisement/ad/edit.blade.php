@extends ('cms::default.layout')
@section ('title','推广管理')

@section ('breadcrumbs')

    <h2>编辑推广位</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="">{!! link_to_route('ad.index', '推广位列表') !!}</li>
        <li class="active">编辑推广位</li>
    </ol>

@stop

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('ad.store')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
            <input type="hidden" name="id" value="{{$advertisement_list->id}}">
            <div class="form-group">
                {!! Form::label('name','推广位名称：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="name" placeholder="" value="{{$advertisement_list->name}}">
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','Code编码：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="code"  placeholder="" value="{{$advertisement_list->code}}">
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('name','是否可用：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <div class="radio">
                        <label>
                            <input type="radio" name="status" value="1"  {{$advertisement_list->status==1?"checked":''}}>
                            是
                        </label>
                        <label>
                            <input type="radio" name="status" value="0"  {{$advertisement_list->status==0?"checked":''}} >
                            否
                        </label>
                    </div>
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
    @include('store-backend::advertisement.ad.script')
@stop