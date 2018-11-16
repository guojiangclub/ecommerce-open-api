@extends ('store-backend::dashboard')

@section ('title','会员等级管理')

@section ('breadcrumbs')

    <h2>会员等级管理</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li >{!! link_to_route('admin.users.grouplist', '会员等级管理') !!}</li>
        <li class="active">{!! link_to_route('admin.users.groupcreate', '新增会员等级') !!}</li>
    </ol>

@stop
@section('content')


    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            @if($action=='edit')
                {!! Form::model($group_edit, ['route' => ['admin.users.groupstore'], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST'
            ,'id'=>'base-form']) !!}
            @else
                {!! Form::open( [ 'url' => [route('admin.users.groupstore')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
            @endif
                <input type="hidden" name="id" value="{{$group_edit->id}}"/>
            <div class="form-group">
                {!! Form::label('name', '会员等级名称', ['class' => 'col-lg-2 control-label']) !!}

                <div class="col-lg-10">
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->
            <div class="form-group">
                {!! Form::label('name', $title, ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-3">

                    <div class="col-md-5" >{!! Form::text('min', null, ['class' => 'form-control', 'placeholder' => '','style'=>'margin-left: 0px']) !!}</div>
                    <div class="col-sm-1">~</div>
                    <div class="col-md-5">{!! Form::text('max', null, ['class' => 'form-control', 'placeholder' => '']) !!}</div>
                </div>
            </div><!--form control-->


            <div class="form-group">
                {!! Form::label('name', '等级', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    @if($group_edit->grade!=1)
                    {!! Form::text('grade', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    @else
                        <input type="text" value="{{$group_edit->grade}}" name="grade" class="form-control" readonly/>
                    @endif
                </div>
            </div><!--form control-->
                @if(env('CUSTOMIZATION'))
                    <div class="form-group">
                        {!! Form::label('pic', '微信卡券背景图', ['class' => 'col-lg-2 control-label']) !!}

                        <div class="col-lg-10">
                            {!! Form::text('pic', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                        </div>
                    </div>
                @endif

            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <input type="submit" class="btn btn-success" value="保存"/>
                    <a href="{{route('admin.users.grouplist')}}" class="btn btn-danger">取消</a>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('before-scripts-end')
            {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
            @include('store-backend::user_group.script')
@stop