<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model implements Transformable
{
    use SoftDeletes;
    use TransformableTrait;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        $this->setTable($prefix . 'order_item');
    }

    public function units()
    {
        return $this->hasMany('GuoJiangClub\EC\Open\Backend\Store\Model\OrderItemUnit','order_item_id');
    }

    public function order()
    {
        return $this->belongsTo('GuoJiangClub\EC\Open\Backend\Store\Model\Order', 'order_id')->withDefault();
    }

    public function product()
    {
        return $this->hasOne('GuoJiangClub\EC\Open\Backend\Store\Model\Product', 'id', 'item_id');
    }

    /**
     * 获取订单商品信息
     */
    public function getItemInfoAttribute()
    {
        return  json_decode($this->attributes['item_meta'], true);
    }

    public function getUnitPriceAttribute($value)
    {
        return $value / 100;
    }

    public function getTotalAttribute($value)
    {
        return $value / 100;
    }

    public function getAdjustmentsTotalAttribute($value)
    {
        return $value / 100;
    }

    public function getModel()
    {
        $model = $this->type;
        $model = new $model();
        return $model->find($this->item_id);
    }

    public function getItemKey($type = 'sku')
    {
        if ($model = $this->getModel())
            return $model->getKeyCode($type);
        return 0;
    }

    public function getItemKeyAttribute()
    {
        return $this->getItemKey('spu');
    }
    
    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }
}
