<?php

/*
 * This file is part of ibrand/order.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $prefix = config('ibrand.app.database.prefix') ?? 'ibrand_';

        if (!Schema::hasTable($prefix . 'order')) {
            Schema::create($prefix . 'order', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('order_no'); //订单编号

                //状态类信息
                $table->integer('status')->unsigned()->default(0);  //订单状态：1生成订单,2支付订单,3取消订单,4作废订单,5完成订单,6退款
                $table->tinyInteger('pay_status')->unsigned()->default(0);  //支付状态：0未支付,1已支付
                $table->integer('distribution_status')->unsigned()->default(0); //发货状态：0未发货，1已发货

                //商品金额类信息
                $table->integer('count')->unsigned()->default(0); //商品总数
                $table->integer('items_total');  //商品总金额
                $table->integer('adjustments_total')->default(0);   //优惠金额，负数，包含了促销和优惠券以及其他优惠的总金额,默认为零因为可能没有优惠活动
                $table->integer('payable_freight')->default(0);   //应付运费金额
                $table->integer('real_freight')->default(0);      //实付运费金额
                $table->integer('total');  //订单总金额:  items_total+adjustments_total+real_freight

                //收货人信息
                $table->string('accept_name')->nullable();  //收货人姓名
                $table->string('mobile')->nullable(); //电话号码
                $table->string('address')->nullable();
                $table->string('address_name')->nullable();     //备用:收货地//详细地址址省市区名称

                //时间类信息
                $table->timestamp('submit_time')->nullable();  //付款时间
                $table->timestamp('pay_time')->nullable();  //付款时间
                $table->timestamp('send_time')->nullable();  //发货时间
                $table->timestamp('completion_time')->nullable();  //订单完成时间
                $table->timestamp('accept_time')->nullable();  //客户收货时间
                $table->string('message')->nullable();  //用户留言

                $table->integer('type')->default(0); //各种订单类型，拼团订单，秒杀订单，折扣订单等

                $table->string('cancel_reason')->nullable();  //取消原因
                $table->string('note')->nullable();     //管理员备注
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'order_item')) {
            Schema::create($prefix . 'order_item', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('order_id')->unsigned();

                $table->integer('item_id')->unsigned();
                $table->string('item_name');
                $table->string('type');
                $table->text('item_meta')->nullable()->default(null);


                $table->integer('quantity')->unsigned();  //商品数量
                $table->integer('unit_price');   //商品单价
                $table->integer('units_total')->unsigned();          //商品总价 = unit_price * quantity
                $table->integer('adjustments_total')->default(0)->nullable();     //总的优惠金额,负数

                $table->integer('total');   //unitPrice * quantity + adjustmentsTotal
                $table->integer('shipping_id')->default(0); //发货ID
                $table->tinyInteger('is_send')->default(0); //是否已发货
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'order_adjustment')) {
            Schema::create($prefix . 'order_adjustment', function (Blueprint $table) {
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

        if (!Schema::hasTable($prefix . 'order_comment')) {
            Schema::create($prefix . 'order_comment', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('order_id')->unsigned()->default(0);
                $table->integer('order_item_id')->unsigned()->default(0);
                $table->integer('item_id')->unsigned()->default(0); //评价的商品，应该使用goods_id
                $table->text('item_meta')->nullable();
                $table->integer('user_id')->unsigned();
                $table->text('user_meta')->nullable();
                $table->text('contents')->nullable();
                $table->integer('point')->nullable()->defalut(0); //评价分数
                $table->string('status')->nullable(); //评价状态
                $table->text('pic_list')->nullable();//评价的图片
                $table->tinyInteger('recommend')->default(0);//是否推荐
                $table->timestamp('recommend_at')->nullable(); //推荐时间
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('ibrand.app.database.prefix') ?? 'ibrand_';

        Schema::dropIfExists($prefix . 'order_comment');
        Schema::dropIfExists($prefix . 'order_adjustment');
        Schema::dropIfExists($prefix . 'order_item');
        Schema::dropIfExists($prefix . 'order');
    }
}
