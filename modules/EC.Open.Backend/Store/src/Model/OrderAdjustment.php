<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class OrderAdjustment extends Model implements Transformable
{
    use TransformableTrait;    

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'order_adjustment');
    }

    public function discount()
    {
        return $this->hasOne('ElementVip\Component\Discount\Models\Discount', 'id', 'origin_id');
    }

    public function order()
    {
        return $this->hasOne('GuoJiangClub\EC\Open\Backend\Store\Model\Order', 'id', 'order_id');
    }


}
