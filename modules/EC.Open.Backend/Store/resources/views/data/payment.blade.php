@extends('store-backend::dashboard')

@section('breadcrumbs')
    <h2>修复支付</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">修复支付</li>
    </ol>
@endsection

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content clearfix" style="display: block;">
            <form method="post" action="{{route('admin.data.runPayment')}}" class="form-horizontal">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="col-sm-2 control-label">支付JSON：</label>

                    <div class="col-sm-10">
                       <textarea class="form-control" name="payment"></textarea>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">提交运行</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}

    <script>

    </script>
@stop