@extends('store-backend::dashboard')

@section('breadcrumbs')
    <h2>运行SQL</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">运行SQL</li>
    </ol>
@endsection

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content clearfix" style="display: block;">
            <form method="get" action="{{route('admin.data.runSql')}}" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">SQL类型：</label>

                    <div class="col-sm-10">
                        <select name="type" class="form-control">
                            <option value="insert">新增</option>
                            <option value="delete">删除</option>
                            <option value="update">修改</option>
                            <option value="select">查询</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">SQL语句：</label>

                    <div class="col-sm-10">
                       <textarea class="form-control" name="sql"></textarea>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">提交运行</button>
                    </div>
                </div>
            </form>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-12">
                    运行结果：{{$result}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}

    <script>

    </script>
@stop