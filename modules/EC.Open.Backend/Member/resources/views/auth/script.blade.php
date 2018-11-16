{{--@section('after-scripts-end')--}}
    <script>
        $('.integrals a').attr('href','javascript:;');
        $('.coupons a').attr('href','javascript:;');
        $('.orders a').attr('href','javascript:;');

        $('.div_3 .pagination li').click(function(){
            var num=$(this).text();
            var dang=$(this);
            if(num!=='»'&&num!=='«'){
                $(".integral").load("integrallist?page="+num,function(){
                    dang.addClass('active').siblings("li").removeClass('active');
                    return false;
                });
            }else{
                dang.addClass('disabled');
            }
        });


        $('.div_4 .pagination li').click(function(){
            var num=$(this).text();
            var dang=$(this);

            if(num!=='»'&&num!=='«'){
                $(".coupon").load("couponslist?page="+num,function(){
                    dang.addClass('active').siblings("li").removeClass('active');
                    return false;
                });
            }else{
                dang.addClass('disabled');
            }
        });


        $('.div_5 .pagination li').click(function(){
            var num=$(this).text();
            var dang=$(this);

            if(num!=='»'&&num!=='«'){
                $(".order").load("orderslist?page="+num,function(){
                    dang.addClass('active').siblings("li").removeClass('active');
                    return false;
                });
            }else{
                dang.addClass('disabled');
            }
        });







    </script>

{{--@stop--}}