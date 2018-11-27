<div class="tabs-container">
    <ul class="nav nav-tabs">

        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">基本信息</a></li>
        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">收货人信息</a></li>
        <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">发货信息</a></li>
        <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">商品清单</a></li>
        <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">费用信息</a></li>
        <li class=""><a href="#tab_9" data-toggle="tab" aria-expanded="false">优惠信息</a></li>
        <li class=""><a href="#tab_10" data-toggle="tab" aria-expanded="false">积分信息</a></li>
        <li class=""><a href="#tab_8" data-toggle="tab" aria-expanded="false">订单评论</a></li>
        <li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false">用户留言</a></li>
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
                @include('store-backend::orders.includes.order_deliver_message')
            </div>
        </div>

        <div class="tab-pane" id="tab_4">
            <div class="panel-body">
                @include('store-backend::orders.includes.order_goods')
            </div>
        </div>

        <div class="tab-pane" id="tab_5">
            <div class="panel-body">
                @include('store-backend::orders.includes.order_cost')
            </div>
        </div>

        <div class="tab-pane" id="tab_7">
            <div class="panel-body">
                <div class="ibox-content">
                    <div class="well well-lg col-md-8" style="text-indent:25px">
                        {{ $order->note ? $order->note: '暂无留言' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="tab_8">
            <div class="panel-body">
                @include('store-backend::orders.includes.order_comment')
            </div>
        </div>

        <div class="tab-pane" id="tab_9">
            <div class="panel-body">
                @include('store-backend::orders.includes.order_discount')
            </div>
        </div>

        <div class="tab-pane" id="tab_10">
            <div class="panel-body">
                @include('store-backend::orders.includes.order_points')
            </div>
        </div>


    </div>

</div>

<div id="modal" class="modal inmodal fade"></div>

{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}





