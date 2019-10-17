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

use GuoJiangClub\Component\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $model;


    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'order_item');
        parent::__construct($attributes);
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    public function getModel()
    {
        if ($this->model) {
            return $this->model;
        }

        $model = $this->type;
        $model = new $model();
        $this->model = $model->find($this->item_id);

        return $this->model;
    }

    public function getItemKey($type = 'sku')
    {
        return $this->getModel()->getKeyCode($type);
    }

    public function getItemId()
    {
        return $this->getModel()->getDetailIdAttribute();
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = $value * 100;
        $this->recalculateUnitsTotal();
    }

    /**
     *  Recalculates total after units total or adjustments total change.
     */
    public function recalculateTotal()
    {
        $this->total = $this->units_total + $this->adjustments_total;

        if ($this->total < 0) {
            $this->total = 0;
        }

        if (null !== $this->order) {
            $this->order->recalculateItemsTotal();
        }
    }

    public function recalculateAdjustmentsTotal()
    {
        $this->adjustments_total = $this->divide_order_discount + $this->item_discount;

        $this->recalculateTotal();
    }

    public function recalculateUnitsTotal()
    {
        $this->units_total = $this->quantity * $this->unit_price;

        $this->recalculateTotal();
    }

    public function setItemMetaAttribute($value)
    {
        $this->attributes['item_meta'] = json_encode($value);
    }

    public function getItemMetaAttribute($value)
    {
        return json_decode($value, true);
    }


    public function getItemSkuAttribute()
    {
        if ($model = $this->getModel()) {
            if ('GuoJiangClub\\Component\\Product\\Models\\Product' == $this->type) {
                return $this->getItemKey();
            } elseif ('GuoJiangClub\\Component\\Product\\Models\\Goods' == $this->type) {
                return $model->goods_no;
            }
        }

        return null;
    }


    public function getUnitsTotalYuanAttribute()
    {
        return number_format($this->units_total / 100, 2, '.', '');
    }

    public function getTotalYuanAttribute()
    {
        return number_format($this->total / 100, 2, '.', '');
    }
}
