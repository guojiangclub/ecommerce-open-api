@extends('store-backend::dashboard')


@section('breadcrumbs')
    <h2>促销活动使用记录</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">促销活动使用记录</li>
    </ol>
@endsection

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/admin/css/plugins/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
@stop


@section('content')

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="tabs-container">
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group">
                                <a class="btn btn-primary ladda-button dropdown-toggle batch" data-toggle="dropdown" href="javascript:;" data-style="zoom-in">批量操作 <span
                                            class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a id="recordSaleBatchExport" href="javascript:;">批量导出</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button class="ladda-button btn btn-primary"  id="sale_use_export" data-style="slide-right">导出</button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-primary "  id="reset">重置搜索</button>
                            </div>
                        </div>
                    </div>
                 </div>

                    {!! Form::open( [ 'route' => ['admin.promotion.saleUseRecord'], 'method' => 'get', 'id' => 'recordSearch-form','class'=>'form-horizontal'] ) !!}
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="col-sm-6">
                                    <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;时间</span>
                                        <input  type="text" class="form-control inline" name="stime"  value="{{request('stime')}}"   placeholder="开始" readonly>
                                        <span class="add-on"><i class="icon-th"></i></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" name="etime"   value="{{request('etime')}}"    placeholder="截止" readonly>
                                        <span class="add-on"><i class="icon-th"></i></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="order_no" value="{{request('order_no')}}"   placeholder="订单号"
                                           class=" form-control"> <span
                                            class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                            </div>
                        </div>

                    {!! Form::close() !!}

                    <div class="table-responsive">
                        <div id="coupons">
                            <div class="hr-line-dashed"></div>
                            <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                        <!--tr-th start-->
                                        <tr>
                                            <th><input type="checkbox" class="check-all"></th>

                                            <th>促销描述</th>
                                            {{--<th>优惠券码</th>--}}
                                            <th>订单编号</th>
                                            <th>订单总金额</th>
                                            <th>订单状态</th>
                                            <th>会员名</th>
                                            <th>使用时间</th>
                                            {{--<th>摘要</th>--}}
                                        </tr>
                                        @if($coupons->count()>0)
                                        <!--tr-th end-->
                                            @foreach ($coupons as $coupon)
                                                <tr class="coupon{{$coupon->id}}">
                                                <td><input class="checkbox"   type="checkbox" value="{{$coupon->id}}" name="ids[]"></td>

                                                    <td>{{$coupon->label}}</td>
                                                    {{--<td>{{$coupon->code}}</td>--}}
                                                    <td><a href="{{route('admin.orders.show',['id'=>$coupon->order->id])}}" target="_blank">{{$coupon->order->order_no}}</a></td>
                                                        {{--@foreach($coupon['relations'] as $item)--}}
                                                            <td>{{$coupon->order->total}}</td>
                                                            <td>
                                                                @if($coupon->order->status==7)
                                                                    退款中
                                                                @elseif($coupon->order->status==1)
                                                                    待付款
                                                                @elseif($coupon->order->status==0)
                                                                    临时订单
                                                                @elseif($coupon->order->status==2)
                                                                    待发货
                                                                @elseif($coupon->order->status==3)
                                                                    配送中待收货
                                                                @elseif($coupon->order->status==4)
                                                                    已收货待评价
                                                                @elseif($coupon->order->status==5)
                                                                    已完成
                                                                @elseif($coupon->order->status==6)
                                                                    已取消
                                                                @elseif($coupon->order->status==9)
                                                                    已取消
                                                                @elseif($coupon->order->status==8)
                                                                    已作废
                                                                @endif
                                                            </td>
                                                        {{--@endforeach--}}
                                                    <td>{!! ElementVip\Store\Backend\Model\User::find($coupon->order->user_id)->name!!}</td>
                                                    <td>{{$coupon->created_at}}</td>
                                                    {{--<td>{{$coupon->note}}</td>--}}
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="pull-left">
                                        &nbsp;&nbsp;共&nbsp;{!! $coupons->total() !!} 条记录
                                    </div>

                                    <div class="pull-right">
                                        {!! $coupons->render() !!}
                                    </div>
                                    <!-- /.box-body -->
                            </div>

                        </div>
                    </div><!-- /.box-body -->
                </div>

            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade"></div>
@endsection

@section('before-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/admin/js/plugins/ladda/spin.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/admin/js/plugins/ladda/ladda.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/admin/js/plugins/ladda/ladda.jquery.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
    @include('store-backend::promotion.includes.script')
@endsection







