<?php

/*
 * This file is part of ibrand/order.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Order\Models;

use GuoJiangClub\Component\Discount\Contracts\DiscountSubjectContract;
use GuoJiangClub\Component\Payment\Models\Payment;
use GuoJiangClub\Component\Shipping\Models\Shipping;
use GuoJiangClub\Component\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model implements DiscountSubjectContract
{
    use SoftDeletes;

    const STATUS_TEMP = 0;   //临时订单
    const STATUS_NEW = 1;    //有效订单，待付款
    const STATUS_PAY = 2;    //已支付订单，待发货

    const STATUS_DELIVERED = 3;    //已发货，待收货
    const STATUS_RECEIVED = 4;    //已收货，待评价
    const STATUS_COMPLETE = 5;    //已评价，订单完成

    const STATUS_CANCEL = 6; //已取消订单
    const STATUS_INVALID = 8; //已作废订单
    const STATUS_REFUND = 7; //有退款订单
    const STATUS_DELETED = 9; //已删除订单

    const TYPE_DEFAULT = 0; //默认类型
    const TYPE_DISCOUNT = 1; //折扣订单

    /*distribution_status*/
    const DELIVERED_WAIT = 0;  //待发货
    const DELIVERED_STATUS = 1; //已全部发货
    const DELIVERED_PARTLY = 2; //部分发货

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'order');

        parent::__construct($attributes);

        $this->status = self::STATUS_TEMP;
        $this->order_no = build_order_no();
    }

    public function save(array $options = [])
    {
        $order = parent::save($options);

        $this->items()->saveMany($this->getItems());

        $this->adjustments()->saveMany($this->getAdjustments());

        $this->comments()->saveMany($this->comments);

        return $order;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }

    /**
     * get subject total amount.
     *
     * @return int
     */
    public function getSubjectTotal()
    {
        return $this->getItemsTotal();
    }

    /**
     * get subject count item.
     *
     * @return int
     */
    public function getSubjectCount()
    {
        return $this->getTotalQuantity();
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalQuantity()
    {
        $quantity = 0;

        foreach ($this->items as $item) {
            $quantity += $item->quantity;
        }

        return $quantity;
    }

    private function getItemsTotal()
    {
        return $this->items_total;
    }

    public function countItems()
    {
        return $this->items->count();
    }

    public function countComments()
    {
        return $this->comments->count();
    }

    /**
     * get subject items.
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getAdjustments()
    {
        return $this->adjustments;
    }

    public function addItem(OrderItem $item)
    {
        if ($this->hasItem($item) and !isset($item->item_meta['dynamic_sku'])) {
            return;
        }

        $this->items_total += $item->getTotal();
        $this->items->add($item);
        $this->recalculateTotal();
        $this->recalculateCount();
    }

    public function hasItem(OrderItem $item)
    {
        return $this->items->contains(function ($value, $key) use ($item) {
            return $item->item_id == $value->item_id and $item->type = $value->type;
        });

        // return $this->items->contains('goods_id', $item->goods_id);
    }

    public function recalculateItemsTotal()
    {
        $items_total = 0;

        foreach ($this->items as $item) {
            $items_total += $item->total;
        }

        $this->items_total = $items_total;
        $this->recalculateTotal();
    }

    public function recalculateTotal()
    {
        $this->total = $this->items_total + $this->adjustments_total;

        if ($this->total < 0) {
            $this->total = 0;
        }
    }

    protected function recalculateCount()
    {
        $this->count = $this->getTotalQuantity();
    }

    /**
     * @param $adjustment
     *
     * @return mixed
     */
    public function addAdjustment($adjustment)
    {
        if (!$this->hasAdjustment($adjustment)) {
            $this->adjustments->add($adjustment);
            $this->addToAdjustmentsTotal($adjustment);
        }
    }

    public function hasAdjustment(Adjustment $adjustment)
    {
        return $this->adjustments->contains(function ($value, $key) use ($adjustment) {
            if ($adjustment->order_item_id) {
                return $adjustment->origin_type == $value->origin_type
                and $adjustment->origin_id == $value->origin_id
                and $adjustment->order_item_id == $value->order_item_id;
            }

            return $adjustment->origin_type == $value->origin_type
            and $adjustment->origin_id == $value->origin_id;
        });
    }

    protected function addToAdjustmentsTotal(Adjustment $adjustment)
    {
        $this->adjustments_total += $adjustment->amount;
        $this->recalculateTotal();
    }

    public function addPayment(Payment $payment)
    {
        if (!$this->hasPayment($payment)) {
            $this->payments->add($payment);
        }
    }

    public function hasPayment(Payment $payment)
    {
        return $this->payments->contains(function ($value, $key) use ($payment) {
            return $payment->channel = $value->channel;
        });
    }

    public function getPaidAmountAttribute()
    {
        return $this->getPaidAmount();
    }

    public function getPaidAmount()
    {
        if (0 === $this->payments->count()) {
            return 0;
        }

        $amount = 0;
        foreach ($this->payments as $item) {
            if (Payment::STATUS_COMPLETED == $item->status) {
                $amount += $item->amount;
            }
        }

        return $amount;
    }

    public function getNeedPayAmount()
    {
        return $this->total - $this->getPaidAmount();
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getSubject()
    {
        if ($this->countItems() > 0) {
            return $this->getItems()->first()->item_name.' 等'.$this->countItems().'件商品';
        }
        throw new \Exception('no items on order');
    }

    /**
     * get subject user.
     *
     * @return mixed
     */
    public function getSubjectUser()
    {
        return $this->user;
    }

    public function getCurrentTotal()
    {
        return $this->total;
    }

    /**
     * get subject is paid.
     *
     * @return mixed
     */
    public function isPaid()
    {
        return $this->pay_status;
    }
}
