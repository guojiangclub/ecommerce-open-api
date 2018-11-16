<?php

namespace iBrand\EC\Open\Backend\Store\Listeners;

use Carbon\Carbon;
use iBrand\EC\Open\Backend\Store\Repositories\GoodsUserLimitRepository;
use iBrand\EC\Open\Backend\Store\Model\GoodsUserLimit;
use iBrand\EC\Open\Backend\Store\Repositories\GoodsLimitRepository;
use ElementVip\Component\Order\Models\OrderItem;

class GoodsPurchaseEventListener
{

	protected $goodsUserLimit;
	protected $goodsLimit;

	public function __construct(GoodsUserLimitRepository $goodsUserLimitRepository,
	                            GoodsLimitRepository $goodsLimitRepository)
	{
		$this->goodsUserLimit = $goodsUserLimitRepository;
		$this->goodsLimit     = $goodsLimitRepository;
	}

	public function purchaseRecordOnOrderSubmitted($order_id, $user_id)
	{
		$goods = $this->getOrderItems($order_id);
		if (count($goods)) {
			foreach ($goods as $good) {
				$userLimit = GoodsUserLimit::where('goods_id', $good['goods_id'])->where('user_id', $user_id)->first();
				if (!$userLimit) {
					$this->goodsUserLimit->create([
						'goods_id' => $good['goods_id'],
						'user_id'  => $user_id,
						'buy_nums' => $good['quantity'],
					]);
				} else {
					$userLimit->buy_nums = $userLimit->buy_nums + $good['quantity'];
					$userLimit->save();
				}
			}
		}
	}

	public function purchaseRecordOnOrderCancel($order_id, $user_id)
	{
		$goods = $this->getOrderItems($order_id);
		if (count($goods)) {
			foreach ($goods as $good) {
				$userLimit = GoodsUserLimit::where('goods_id', $good['goods_id'])->where('user_id', $user_id)->first();
				if (!$userLimit) {
					continue;
				} else {
					$userLimit->buy_nums = $userLimit->buy_nums - $good['quantity'];
					$userLimit->save();
				}
			}
		}
	}

	public function getOrderItems($order_id)
	{
		$data  = [];
		$items = OrderItem::where('order_id', $order_id)->get();
		if (count($items) > 0) {
			foreach ($items as $item) {
				$meta  = $item->item_meta;
				$check = $this->goodsLimit->findWhere(['goods_id' => $meta['detail_id'], 'activity' => 1, ['starts_at', '<=', Carbon::now()], ['ends_at', '>=', Carbon::now()]])->first();
				if (!$check) {
					continue;
				}

				$data[] = ['goods_id' => $meta['detail_id'], 'quantity' => $item->quantity];
			}
		}

		return $data;
	}

	public function subscribe($events)
	{
		$events->listen(
			'purchase.record.on.order.submitted',
			'iBrand\EC\Open\Backend\Store\Listeners\GoodsPurchaseEventListener@purchaseRecordOnOrderSubmitted'
		);

		$events->listen(
			'purchase.record.on.order.cancel',
			'iBrand\EC\Open\Backend\Store\Listeners\GoodsPurchaseEventListener@purchaseRecordOnOrderCancel'
		);
	}
}