{{--@extends ('member-backend::layout')--}}

{{--@section ('title',  '员工管理 | 创建员工')--}}



{{--@section ('breadcrumbs')--}}
    {{--<h2>修改员工信息</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li>{!! link_to_route('admin.staff.index', '员工管理') !!}</li>--}}
        {{--<li class="active">修改员工信息</li>--}}
    {{--</ol>--}}
{{--@stop--}}

{{--@section('content')--}}
    <div class="ibox float-e-margins">


        <div class="ibox-content" style="display: block;">
            {!! Form::model($staff, ['route' => ['admin.staff.update', $staff->id],
            'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH'
            ,'id'=>'create-user-form']) !!}
            {{--{!! Form::open(['route' => 'admin.staff.store', 'class' => 'form-horizontal',--}}
            {{--'role' => 'form', 'method' => 'post','id'=>'create-user-form']) !!}--}}

            <div class="form-group">
                {!! Form::label('staff_id', '员工号', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('staff_id',null, ['class' => 'form-control', 'placeholder' => '','readonly'=>'readonly']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name', '员工姓名', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('name',null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('email', '工作邮箱', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' =>'']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('mobile', '手机号码', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('mobile', null, ['class' => 'form-control', 'placeholder' =>'']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('hiredate_at', '入职时间', ['class' => 'col-lg-2 control-label']) !!}
                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                    <input type="text" class="form-control inline" name="hiredate_at" value="{{$staff->hiredate_at}}"
                           placeholder="点击选择开始时间" readonly>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('locationType', '职位类型', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('locationType', null, ['class' => 'form-control', 'placeholder' =>'']) !!}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('active_status', '活跃状态', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    <select class="form-control" name="active_status">

                        <option value="1" {{!empty($staff->active_status==1)?'selected ':''}} >在职</option>
                        <option value="2" {{!empty($staff->active_status==2)?'selected ':''}} >离职</option>
                    </select>
                </div>
            </div><!--form control-->




            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <input type="submit" class="btn btn-success" value="保存"/>
                    <a href="{{route('admin.staff.index')}}" class="btn btn-danger">取消</a>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
{{--@stop--}}


{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    @include('member-backend::staff.includes.script')
{{--@stop--}}
