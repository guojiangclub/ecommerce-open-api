<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use GuoJiangClub\EC\Open\Backend\Store\Model\Relations\BelongToUserTrait;

class ElDiscountCoupon extends Model
{
    use BelongToUserTrait;
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'discount_coupon');
    }

    public function order()
    {
        return $this->belongsToMany('GuoJiangClub\EC\Open\Backend\Store\Model\Order', config('ibrand.app.database.prefix', 'ibrand_').'order_adjustment', 'origin_id', 'order_id');
    }

    public function getOrder()
    {
        return $this->order()->wherePivot('origin_type', 'coupon')->first();
    }

    public function discount()
    {
        return $this->belongsTo(ElDiscount::class, 'discount_id');
    }

}
