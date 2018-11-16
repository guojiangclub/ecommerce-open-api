<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use iBrand\EC\Open\Backend\Store\Model\Relations\BelongToUserTrait;

class ElDiscountCoupon extends Model
{
    use BelongToUserTrait;
    protected $table = 'el_discount_coupon';
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsToMany('iBrand\EC\Open\Backend\Store\Model\Order', 'el_order_adjustment', 'origin_id', 'order_id');
    }

    public function getOrder()
    {
        return $this->order()->wherePivot('origin_type', 'coupon')->first();
    }
    
    public function discount()
    {
        return $this->belongsTo(ElDiscount::class,'discount_id');
    }

}
