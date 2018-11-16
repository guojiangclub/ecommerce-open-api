<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class OrderProduce extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'el_order_produce';

    protected $guarded = ['id'];

    public function setMetaAttribute($value)
    {
        $this->attributes['meta'] = json_encode($value);
    }


    public function getMetaAttribute($value)
    {
        if (!empty($value)) {
            return json_decode($value,true);
        }
        return $value;
    }
}
