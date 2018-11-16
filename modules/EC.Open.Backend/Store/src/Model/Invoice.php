<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Invoice extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'el_invoice_order';

    protected $guarded = ['id'];

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (int)($value * 100);
    }
    
    public function getAmountAttribute()
    {
        return $this->attributes['amount'] / 100;
    }
}
