<?php

namespace GuoJiangClub\EC\Open\Server\Schedule;

use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\User\Models\User;
use iBrand\Scheduling\Scheduling;

class OrderRemindSchedule extends Scheduling
{
	public function schedule()
	{
		$this->schedule->call(function () {
			$orders = Order::where('status', 1)->where('pay_status', 0)->where('is_remind', 0)->where('type', '!=', 7)->get();
			if (count($orders)) {
				foreach ($orders as $order) {
					$order_created_at = strtotime($order->created_at);
					$order_close_time = app('system_setting')->getSetting('order_auto_cancel_time');
					if ($order_close_time > 15 && (time() - $order_created_at) >= ($order_close_time - 15) * 60) {
						$order->update(['is_remind' => 1]);
					}
				}
			}
		})->everyMinute();
	}
}
