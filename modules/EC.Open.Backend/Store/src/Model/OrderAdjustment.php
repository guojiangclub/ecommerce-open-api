<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class OrderAdjustment extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'el_order_adjustment';

    protected $guarded = ['id'];

    public function discount()
    {
        return $this->hasOne('ElementVip\Component\Discount\Models\Discount', 'id', 'origin_id');
    }

    public function order()
    {
        return $this->hasOne('iBrand\EC\Open\Backend\Store\Model\Order', 'id', 'order_id');
    }


}
