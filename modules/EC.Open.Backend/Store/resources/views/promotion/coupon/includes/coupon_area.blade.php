<style type="text/css">
    .coupon_bg {
        background-size: 100% auto;
        width: 325px;
        height: 910px;
        background-image: url("/assets/backend/images/coupon-bg.jpg");
        background-repeat: no-repeat;
        position: relative;
        text-align: center;
    }

    .coupon_bg .title {
        padding-top: 100px
    }

    .coupon_bg .time {
        padding-top: 20px
    }

    .coupon_bg .coupon_area {
        padding: 180px 20px 20px 20px;
        text-align: left;
    }

</style>

<div class="coupon_bg">
    <p class="title" id="coupon_title">{{$discount->title?$discount->title:''}}</p>
    <p class="time">
        <span id="coupon_t_s">{{$discount->starts_at?$discount->starts_at:date("Y-m-d H:m:s",time())}}</span> 至 <span
                id="coupon_t_e">{{$discount->ends_at?$discount->ends_at:date("Y-m-d H:m:s",time()+60*60*24*30)}}</span>
    </p>
    <p class="view_code">BVIPEAK8888888</p>
    <p class="coupon_area">

    </p>
</div>