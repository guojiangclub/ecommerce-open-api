<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix','ibrand_');

        Schema::create($prefix.'order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();

            $table->integer('count')->unsigned()->default(0); //商品总数

            $table->integer('status')->unsigned()->default(0);  //订单状态：1生成订单,2支付订单,3取消订单,4作废订单,5完成订单,6退款

            $table->integer('pay_status')->unsigned()->default(0);  //支付状态：0未支付,1已支付


            $table->integer('items_total');  //商品总金额

            $table->integer('adjustments_total')->default(0);   //优惠金额，负数，包含了促销和优惠券以及其他优惠的总金额,默认为零因为可能没有优惠活动

            $table->integer('total');  //订单总金额:  items_total+adjustments_total+real_freight


            $table->timestamp('pay_time')->nullable();  //付款时间
            $table->string('note')->nullable();     //管理员备注

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create($prefix.'order_item', function (Blueprint $table) use($prefix){
            $table->increments('id');
            $table->unsignedInteger('order_id')->unsigned();

            $table->integer('item_id')->unsigned();
            $table->string('item_name');
            $table->string('type');
            $table->text('item_meta')->nullable()->default(null);

            $table->integer('quantity')->unsigned();  //商品数量
            $table->integer('unit_price');   //商品单价
            $table->integer('units_total')->unsigned();          //商品总价 = unit_price * quantity
            $table->integer('adjustments_total')->default(0)->nullable();     //总的优惠金额

            $table->integer('total');   //unitPrice * quantity - adjustmentsTotal

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create($prefix.'order_adjustment', function (Blueprint $table)  use($prefix){
            $table->increments('id');
            $table->integer('order_id')->unsigned()->nullable();
            $table->integer('order_item_id')->unsigned()->nullable();

            $table->string('type');  //优惠对象，订单, 商品，运费等
            $table->string('label')->nullable();  // 文案描述："9折"
            $table->integer('amount')->default(0);  //优惠金额，统一用分来表示

            $table->string('origin_type')->nullable();   //优惠类型  discount, coupon ,membership,vip
            $table->integer('origin_id')->default(0);     //优惠券ID或者discount ID,或者用户组group id

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('ibrand.app.database.prefix','ibrand_');

        Schema::dropIfExists($prefix.'order');
        Schema::dropIfExists($prefix.'order_item');
        Schema::dropIfExists($prefix.'adjustment');
    }
}
