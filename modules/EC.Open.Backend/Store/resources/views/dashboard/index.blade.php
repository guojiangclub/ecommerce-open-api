{!! Html::style(env("APP_URL").'/assets/backend/libs/css/store-dashboard.css') !!}

<div id="summary-content" class="col-lg-12">
    <div class="today-sales row">
        <div class="title">
            今日实时销售
        </div>
        <div class="sales-detail">
            <div class="col-sm-2 detail-item bor-r">
                <div class="num yellow">{{$total}}</div>
                <div class="text">付款金额</div>
            </div>
            <div class="col-sm-2 detail-item bor-r">
                <div class="num yellow">{{$per_total}}</div>
                <div class="text">客单价</div>
            </div>
            <div class="col-sm-2 detail-item bor-r">
                <div class="num">{{$countWaitPayOrders}}</div>
                <div class="text">待付款订单数</div>
            </div>
            <div class="col-sm-2 detail-item bor-r">
                <div class="num">{{$waitPayTotal}}</div>
                <div class="text">待付款金额</div>
            </div>
            <div class="col-sm-2 detail-item bor-r">
                <div class="num">{{$paidCount}}</div>
                <div class="text">待发货</div>
            </div>
            <div class="col-sm-2 detail-item">
                <div class="num red">{{$refund}}</div>
                <div class="text">售后</div>
            </div>
        </div>
    </div>
    <div class="seven-income row">
        <div class="col-lg-5 seven-content">
            <div class="order col-sm-6">
                <div class="much-order clearfix">
                    <span class="info-l pull-left">7天订单</span>
                    <span class="info-r pull-right">笔</span>
                </div>
                <div class="detail-order">
                    <div class="order-num">{{$countSevOrders}}</div>
                    <div class="order-text">今日订单：{{$todayCount}} 笔</div>
                </div>
            </div>
            <div class="order col-sm-6">
                <div class="much-order clearfix">
                    <span class="info-l pull-left">7天收入</span>
                    <span class="info-r pull-right">元</span>
                </div>
                <div class="detail-order">
                    <div class="order-num">{{$sevTotal}}</div>
                    <div class="order-text">今日收入：{{$total}} 元</div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 order-tendency">
            <div class="much-order clearfix">
                <span class="info-l pull-left">7天下单趋势</span>
                <span class="info-r pull-right">笔</span>
            </div>
            <div id="main-seven" style="width: 100%;height:190px">
            </div>
        </div>
    </div>
    <div class="transaction-summary row">
        <div class="transaction-content">
            <div class="much-order clearfix">
                <span class="info-l pull-left">交易汇总</span>
                <span class="info-r pull-right radiu-d" id="Tday">日</span>
                <span class="info-r pull-right radiu-m active" id="Tmonth">月</span>
            </div>
            <div id="main-money" style="width: 100%;height:400px"></div>
        </div>
    </div>
    <div class="use-growth row">
        <div class="growth-content">
            <div class="much-order clearfix">
                <span class="info-l pull-left">用户增长数</span>
                <span class="info-r pull-right radiu-d" id="Gday">日</span>
                <span class="info-r pull-right radiu-m active" id="Gmonth">月</span>
            </div>
            <div id="main-growth" style="width: 100%;height:400px;">

            </div>
        </div>

    </div>
</div>

{!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/echarts/4.1.0/echarts.js') !!}
@include('store-backend::dashboard.script')