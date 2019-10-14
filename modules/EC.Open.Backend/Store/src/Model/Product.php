<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Product extends Model implements Transformable
{
    use TransformableTrait;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'goods_product');
    }

    public function goods()
    {
        return $this->belongsTo('GuoJiangClub\EC\Open\Backend\Store\Model\Goods', 'goods_id');
    }

    public function setSpecIdsAttribute($value)
    {
        $this->attributes['spec_ids'] = json_encode(explode('-', $value));
    }

    public function getSpecIdsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function scopeJudge($query, $sku, $id = 0)
    {
        return $query->where('sku', $sku)->where('id', '<>', $id)->get();
    }
}
