@extends('store-backend::dashboard')
@section ('title','查看提现详情')

@section ('breadcrumbs')
    <h2>查看提现详情</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="">{!! link_to_route('admin.balance.cash.index', '提现管理') !!}</li>
        <li class="active">查看提现详情</li>
    </ol>
@stop

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('admin.balance.cash.review')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
            <input type="hidden" name="id" value="{{$cash->id}}">
            <div class="form-group">
                <label class="control-label col-lg-2">申请人：</label>
                <div class="col-lg-9">
                    <p class="form-control-static">{{$cash->user->nick_name}}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">申请金额：</label>
                <div class="col-lg-9">
                    <p class="form-control-static">{{$cash->amount}} 元</p>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">申请时间：</label>
                <div class="col-lg-9">
                    <p class="form-control-static">{{$cash->created_at}} </p>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">状态：</label>
                <div class="col-lg-9">
                    <p class="form-control-static">{{$cash->status_text}}</p>
                </div>
            </div>

            @if($cash->status != 0)
                <div class="form-group">
                    <label class="control-label col-lg-2">处理时间：</label>
                    <div class="col-lg-9">
                        <p class="form-control-static">{{$cash->updated_at}}</p>
                    </div>
                </div>
                @endif
                        @if($cash->status==2)
                <div class="form-group">
                    <label class="control-label col-lg-2">打款时间：</label>
                    <div class="col-lg-9">
                        <p class="form-control-static">{{$cash->settle_time}}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-lg-2">打款凭证：</label>
                    <div class="col-lg-9">
                        @foreach($cash->cert as $key=>$item)
                        <img src="{{$item}}" width="100">
                        @endforeach
                    </div>
                </div>
                        @endif

                        <!----申请 待审核操作-->
                @if($cash->status == 0)
                    <div class="form-group">
                        <label class="control-label col-lg-2">处理：</label>
                        <div class="col-lg-9">
                            <label>
                                <input type="radio" name="status" value="1" checked="">
                                通过
                            </label>
                            &nbsp;&nbsp;
                            <label>
                                <input type="radio" name="status" value="2">
                                不通过
                            </label>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8 controls">
                            <button type="submit" class="btn btn-primary">提交</button>

                            <a href="{{route('admin.balance.cash.index',['status'=>'STATUS_AUDIT'])}}"
                               class="btn btn-danger">返回</a>
                        </div>
                    </div>
                @else
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8 controls">
                            <a href="{{route('admin.balance.cash.index',['status'=>'STATUS_AUDIT'])}}"
                               class="btn btn-danger">返回</a>
                        </div>
                    </div>
                    @endif
                    {!! Form::close() !!}
                            <!-- /.tab-content -->
        </div>

        @endsection

        @section('before-scripts-end')
            {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
        @endsection

        @section('after-scripts-end')
            <script>
                $('#base-form').ajaxForm({
                    success: function (result) {
                        if (!result.status) {
                            swal("操作失败!", result.error, "error")
                        } else {
                            swal({
                                title: "操作成功！",
                                text: "",
                                type: "success"
                            }, function () {
                                window.location.reload();
                            });
                        }

                    }
                });
            </script>
@endsection
