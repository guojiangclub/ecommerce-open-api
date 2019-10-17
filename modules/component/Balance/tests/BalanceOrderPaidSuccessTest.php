<?php

/*
 * This file is part of ibrand/balace.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Balance\Test;

use GuoJiangClub\Component\Balance\Balance;
use GuoJiangClub\Component\Balance\BalanceOrder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BalanceOrderPaidSuccessTest extends BaseTest
{
	use DatabaseMigrations;

	public function testPaidSuccessEvent()
	{
		$order = BalanceOrder::create(['user_id' => 3, 'order_no' => 'R123456', 'pay_type' => 'alipay_wap', 'pay_status' => BalanceOrder::STATUS_NEW, 'amount' => '8000', 'pay_amount' => '8000']);

		$this->assertSame('GuoJiangClub\Component\Balance\BalanceOrder', get_class($order));

		event('balance.order.paid.success', [$order]);

		$balance = Balance::where('user_id', 3)
			->where('type', Balance::TYPE_RECHARGE)
			->where('origin_id', $order->id)
			->where('origin_type', 'GuoJiangClub\Component\Balance\BalanceOrder')
			->first();

		$this->assertSame('8000', $balance->value);
	}
}
