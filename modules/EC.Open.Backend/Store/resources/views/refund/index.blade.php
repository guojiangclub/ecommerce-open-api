{{--@extends('store-backend::dashboard')

@section ('title','售后申请列表')

@section('breadcrumbs')

    <h2>售后申请列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">售后申请列表</li>
    </ol>

@endsection

@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--@stop

@section('content')--}}

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="{{ Active::query('status',0) }}"><a no-pjax href="{{route('admin.refund.index',['status'=>0])}}">待处理
                </a></li>
            <li class="{{ Active::query('status',1) }}"><a no-pjax href="{{route('admin.refund.index',['status'=>1])}}"> 处理中
                </a></li>
            <li class="{{ Active::query('status',8) }}"><a no-pjax href="{{route('admin.refund.index',['status'=>8])}}"> 待退款
                </a></li>
            <li class="{{ Active::query('status',2) }}"><a no-pjax href="{{route('admin.refund.index',['status'=>2])}}"> 已完成
                </a></li>
            <li class=""><a aria-expanded="false" data-toggle="tab" href="#tab-2">数据导出</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                        {!! Form::open( [ 'route' => ['admin.refund.index'], 'method' => 'get', 'id' => 'ordersurch-form','class'=>'form-horizontal'] ) !!}
                        <input type="hidden" name="status" value="{{request('status')?request('status'):0}}">
                        <div class="col-md-2">
                            <select class="form-control" name="field">
                                <option value="refund_no">售后申请编号</option>
                                <option value="order_no">订单编号</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="value" placeholder="Search"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">查找</button></span></div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <tbody>
                            <!--tr-th start-->
                            <tr>
                                <th>申请编号</th>
                                <th>订单号</th>
                                <th>申请类型</th>
                                <th>申请时间</th>
                                <th>申请会员</th>
                                <th>退款金额</th>
                                <th>订单支付方式</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            <!--tr-th end-->

                            @foreach ($refunds as $item)
                                <tr>
                                    <td>{{$item->refund_no}}</td>
                                    <td>{{$item->order->order_no}}</td>
                                    <td>{{$item->TypeText}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{isset($item->user->mobile)?$item->user->mobile:''}}</td>
                                    <td>{{$item->amount}} 元</td>
                                    <td>{{$item->order->payment?$item->order->payment->ChannelText:''}}</td>
                                    <td>{{$item->StatusText}}</td>
                                    <td>
                                        {{--@if($item->status !== 3 AND $item->status !== 4)--}}

                                        {{--<a class="btn btn-xs btn-success" id="chapter-create-btn"--}}
                                        {{--data-toggle="modal"--}}
                                        {{--data-target="#modal" data-backdrop="static" data-keyboard="false"--}}
                                        {{--data-url="{{route('admin.refund.getStatus',['id' => $item->id])}}">--}}
                                        {{--<i class="fa fa-send" data-toggle="tooltip" data-placement="top"--}}
                                        {{--title="修改状态"></i>--}}
                                        {{--</a>--}}
                                        {{--@endif--}}

                                        <a class="btn btn-xs btn-primary"
                                           href="{{route('admin.refund.show', ['id' => $item->id])}}">
                                            <i data-original-title="编辑" data-toggle="tooltip" data-placement="top"
                                               class="fa fa-pencil-square-o" title=""></i></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="9" class="footable-visible">
                                    {!! $refunds->render() !!}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div>
            </div>

            <div id="tab-2" class="tab-pane">
                <div class="panel-body form-horizontal">
                    @include('store-backend::refund.include.refund_export')
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal inmodal fade"></div>
{{--@endsection

@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    @include('store-backend::refund.include.export_script')
{{--@endsection--}}