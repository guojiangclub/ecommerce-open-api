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

use GuoJiangClub\Component\Discount\Contracts\AdjustmentContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adjustment extends Model implements AdjustmentContract
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'order_adjustment');
        parent::__construct($attributes);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * create a adjustment.
     *
     * @param $type
     * @param $label
     * @param $amount
     * @param $originId
     * @param $originType
     *
     * @return mixed
     */
    public function createNew($type, $label, $amount, $originId, $originType)
    {
        return new self(['type' => $type, 'label' => $label, 'amount' => $amount, 'origin_id' => $originId, 'origin_type' => $originType]);
    }
}
