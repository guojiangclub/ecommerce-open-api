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
                $table->string('type', 30)->default('default')->comment('存在多种积分时用于分类');
                //  order_item:     订单商品获得积分
                //  order_discount: 订单折扣使用积分
                //  order_canceled: 取消订单返还积分
                //  goods:          线下购物获得积分(TNF导入数据)
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

        if (!Schema::hasTable($prefix . 'point_rules')) {
            Schema::create($prefix . 'point_rules', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->comment('规则名称');
                $table->text('intro')->nullable()->comment('规则说明');
                $table->unsignedTinyInteger('status')->default(1)->comment('状态 1 启用 0 禁用');
                $table->string('type')->comment('积分规则类型');
                $table->mediumText('configuration')->nullable()->comment('规则设置');
                $table->timestamps();
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

		Schema::drop($prefix . 'point_rules');
	}
}
