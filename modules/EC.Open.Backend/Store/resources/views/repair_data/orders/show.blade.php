@extends ('store-backend::dashboard')

@section('breadcrumbs')
    <h2>查看订单</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">{!! link_to_route('admin.orders.index', '订单管理') !!}</li>
        <li class="active">查看订单</li>
    </ol>
@endsection

@section('content')
    <div class="tabs-container">
        <ul class="nav nav-tabs">

            <li class="active"><a href="#tab_1"  data-toggle="tab" aria-expanded="true">基本信息</a></li>
            <li class=""><a href="#tab_2"  data-toggle="tab" aria-expanded="false">收货人信息</a></li>
            <li class=""><a href="#tab_3"  data-toggle="tab" aria-expanded="false">商品清单</a></li>
            <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">费用信息</a></li>
            <li class=""><a href="#tab_8" data-toggle="tab" aria-expanded="false">优惠信息</a></li>
            <li class=""><a href="#tab_9" data-toggle="tab" aria-expanded="false">积分信息</a></li>
            <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">发票信息</a></li>
            <li class=""><a href="#tab_6"  data-toggle="tab" aria-expanded="false">用户留言</a></li>
            <li class=""><a href="#tab_7"  data-toggle="tab" aria-expanded="false">订单评论</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="panel-body">
                    @include('store-backend::orders.includes.order_basic')
                </div>

            </div>

            <div class="tab-pane" id="tab_2">
                <div class="panel-body">
                    @include('store-backend::orders.includes.order_address')
                </div>
            </div>

            <div class="tab-pane" id="tab_3">
                <div class="panel-body">
                    @include('store-backend::orders.includes.order_goods')
                </div>
            </div>

            <div class="tab-pane" id="tab_4">
                <div class="panel-body">
                    @include('store-backend::orders.includes.order_cost')
                </div>
            </div>

            <div class="tab-pane" id="tab_5">
                <div class="panel-body">
                    @include('store-backend::orders.includes.order_invoice')
                </div>
            </div>


            <div class="tab-pane" id="tab_6">
                <div class="panel-body">
                    <div class="ibox-content">
                        <div class="well well-lg col-md-8" style="text-indent:25px">
                            {{ $order->note ? $order->note: '暂无留言' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_7">
                <div class="panel-body">
                    @include('store-backend::orders.includes.order_comment')
                </div>
            </div>

            <div class="tab-pane" id="tab_8">
                <div class="panel-body">
                    @include('store-backend::orders.includes.order_discount')
                </div>
            </div>

            <div class="tab-pane" id="tab_9">
                <div class="panel-body">
                    @include('store-backend::orders.includes.order_points')
                </div>
            </div>

        </div>

    </div>


@endsection

@section('before-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    @include('store-backend::orders.includes.script')
@stop





