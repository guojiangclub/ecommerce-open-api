{{--@extends ('member-backend::layout')--}}

{{--@section ('title','会员分组管理')--}}

{{--@section ('breadcrumbs')--}}

    {{--<h2>会员分组管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li>{!! link_to_route('admin.users.group.list', '会员分组管理') !!}</li>--}}
        {{--<li class="active">修改会员分组</li>--}}
    {{--</ol>--}}

{{--@stop--}}
{{--@section('content')--}}


    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('admin.users.group.store')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
            <input type="hidden" name="id" value="{{$group->id}}">
            <div class="form-group">
                {!! Form::label('name', '分组名称：', ['class' => 'col-md-2 control-label']) !!}

                <div class="col-md-10">
                    {!! Form::text('name', $group->name, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->
            <div class="form-group">
                {!! Form::label('description', '分组说明：', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    <textarea class="form-control" name="description">{{$group->description}}</textarea>

                </div>
            </div><!--form control-->


            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <input type="submit" class="btn btn-success" value="保存"/>
                    <a href="{{route('admin.users.group.list')}}" class="btn btn-danger">取消</a>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
{{--@endsection--}}

{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    @include('member-backend::group.script')
{{--@stop--}}