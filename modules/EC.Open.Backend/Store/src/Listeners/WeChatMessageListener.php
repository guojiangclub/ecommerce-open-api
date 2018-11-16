<?php

namespace iBrand\EC\Open\Backend\Store\Listeners;

use ElementVip\Component\User\Models\User;
use ElementVip\Component\Order\Models\Order;
use ElementVip\Notifications\GoodDeliver;
use ElementVip\Notifications\MoneyChanged;
use ElementVip\Notifications\SalesService;
use ElementVip\Notifications\CustomerPaid;
use ElementVip\Notifications\SalesNotice;
use iBrand\EC\Open\Backend\Store\Model\Admin;

class WeChatMessageListener
{
	/**
	 * 新订单通知admin
	 *
	 * @param $order
	 */
	public function CustomerPaid($order)
	{
		$users = $this->getAdminUsers('wx_orders');
		if (count($users)) {
			foreach ($users as $item) {
				$item->notify(new CustomerPaid(['order' => $order]));
			}
		}
	}

	protected function getAdminUsers($type)
	{
		$users = Admin::where('status', 1)->whereHas('adminNotifications', function ($query) use ($type) {
			$query->where('el_admin_notifications.type', $type);
		})->get();

		return $users;
	}

	/**
	 * 新售后通知管理员
	 *
	 * @param $refund
	 */
	public function salesNotice($refund, $note)
	{
		$users = $this->getAdminUsers('wx_refund');
		if (count($users)) {
			foreach ($users as $item) {
				$item->notify(new SalesNotice(['refund' => $refund, 'note' => $note]));
			}
		}
	}

	/**
	 * 售后动态通知用户
	 *
	 * @param $refund
	 * @param $message
	 */
	public function salesService($refund, $message)
	{
		$user = User::find($refund->user->id);
		$user->notify(new SalesService(['refund' => $refund, 'message' => $message]));
	}

	/**
	 * 发货通知用户
	 *
	 * @param $deliver
	 */
	public function goodsDeliver($deliver)
	{
		$order = Order::find($deliver->order_id);
		if ($order) {
			$user = User::find($order->user_id);
			$user->notify(new GoodDeliver(['shipping' => $deliver]));
		}
	}

	/**
	 * 帐户资金变动提醒
	 *
	 * @param $balance
	 */
	public function moneyChanged($balance)
	{
		$user = User::find($balance['user_id']);
		$user->notify(new MoneyChanged(['money' => $balance]));
	}

	public function subscribe($events)
	{
		$events->listen(
			'order.goods.deliver',
			'iBrand\EC\Open\Backend\Store\Listeners\WeChatMessageListener@goodsDeliver'
		);

		$events->listen(
			'refund.service.changed',
			'iBrand\EC\Open\Backend\Store\Listeners\WeChatMessageListener@salesService'
		);

		$events->listen(
			'goods.sales.notice',
			'iBrand\EC\Open\Backend\Store\Listeners\WeChatMessageListener@salesNotice'
		);

		$events->listen(
			'order.customer.paid',
			'iBrand\EC\Open\Backend\Store\Listeners\WeChatMessageListener@CustomerPaid'
		);

		$events->listen(
			'member.balance.changed',
			'iBrand\EC\Open\Backend\Store\Listeners\WeChatMessageListener@moneyChanged'
		);
	}
}