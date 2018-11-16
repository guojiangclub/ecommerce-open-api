@section('after-scripts-end')
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
        })


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
        })


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
        })





        $('.tab_1').click(function(){
            $('.div_5').css('display','none');
            $('.div_4').css('display','none');
            $('.div_3').css('display','none');
            $('.div_2').css('display','none');
            $('.div_1').css('display','block');

        });
        $('.tab_2').click(function(){
            $('.div_2').css('display','block');
            $('.div_1').css('display','none');
            $('.div_3').css('display','none');
            $('.div_4').css('display','none');
            $('.div_5').css('display','none');

        });

        $('.tab_3').click(function(){
            $('.div_3').css('display','block');
            $('.div_2').css('display','none');
            $('.div_1').css('display','none');
            $('.div_4').css('display','none');
            $('.div_5').css('display','none');
        });

        $('.tab_4').click(function(){
            $('.div_5').css('display','none');
            $('.div_4').css('display','block');
            $('.div_3').css('display','none');
            $('.div_2').css('display','none');
            $('.div_1').css('display','none');

        });

        $('.tab_5').click(function(){
            $('.div_5').css('display','block');
            $('.div_4').css('display','none');
            $('.div_3').css('display','none');
            $('.div_2').css('display','none');
            $('.div_1').css('display','none');

        });


    </script>

@stop