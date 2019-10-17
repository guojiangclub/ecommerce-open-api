<?php

/*
 * This file is part of ibrand/balace.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceTestTable extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		$prefix = config('ibrand.app.database.prefix', 'ibrand_');

		Schema::create($prefix . 'balance', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->comment('用户id，外键关联 user 表 id 字段');
			$table->string('type')->default('expend')->comment('余额变动的类型：recharge(充值), expend(消费)');
			$table->string('note')->nullable()->comment('文字描述，展示给用户');
			$table->unsignedInteger('value')->default(0)->comment('单位：分');
			$table->unsignedInteger('current_balance')->default(0)->comment('当前余额');
			$table->unsignedInteger('origin_id')->comment('引起变动的数据来源的id');
			$table->string('origin_type')->comment('引起变动的数据来源类型，值通常为 class 全称');
			$table->string('channel')->nullable()->comment('渠道：可能的值 online (线上) offline (线下)，');
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create($prefix . 'balance_order', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->comment('用户id，外键关联 user 表 id 字段');
			$table->string('order_no')->comment('订单编号');
			$table->string('pay_type')->nullable()->comment('支付方式 包含支付宝，微信，余额支付');
			$table->unsignedTinyInteger('pay_status')->default(0)->comment('支付状态，0 待支付，1 已支付 2 已退款');
			$table->timestamp('pay_time')->nullable()->comment('完成支付时间');
			$table->unsignedInteger('amount')->comment('实际到账金额，单位:分');
			$table->unsignedInteger('pay_amount')->comment('实际支付金额，单位:分');
			$table->unsignedInteger('origin_id')->nullable()->comment('支付优惠活动的id');
			$table->string('origin_type')->nullable()->comment('支付优惠活动类型的class name');
			$table->string('note')->nullable()->comment('备注信息');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down()
	{
		$prefix = config('ibrand.app.database.prefix', 'ibrand_');

		Schema::dropIfExists($prefix . 'balance');
		Schema::dropIfExists($prefix . 'balance_order');
	}
}
