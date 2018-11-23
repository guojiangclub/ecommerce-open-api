<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$prefix = config('ibrand.app.database.prefix', 'ibrand_');

        if(!Schema::hasTable($prefix . 'point')){
            Schema::create($prefix . 'point', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->comment('用户id');
                //  order_item:     订单商品获得积分
                //  order_discount: 订单折扣使用积分
                //  order_canceled: 取消订单返还积分
                $table->string('action')->default('order_item')->comment('action:产生积分变化的动作，用于查询');
                $table->string('note')->nullbale()->comment('积分变化的提示信息');
                $table->decimal('value', 10, 2)->nullable()->default(0)->comment('积分变化数值，可为负数');
                $table->timestamp('valid_time')->nullable()->comment('积分有效期 为空时永久有效');
                $table->unsignedInteger('status')->nullable()->default(1);
                $table->string('item_type')->nullable();
                $table->unsignedInteger('item_id')->nullable()->comment('积分变化动作对应表的id');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['item_type', 'item_id']);
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
		$prefix = config('ibrand.app.database.prefix', 'ibrand_');
		Schema::drop($prefix . 'point');
	}
}
