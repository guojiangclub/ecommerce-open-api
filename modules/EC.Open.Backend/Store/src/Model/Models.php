<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Traits\TransformableTrait;

class Models extends Model
{
    use TransformableTrait;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'goods_model');
    }

    public function setSpecIdsAttribute($value)
    {
        $this->attributes['spec_ids'] = implode(',', $value);
    }

    public function getSpecIdsAttribute($value)
    {
        return explode(',', $value);
    }

    public function hasManyAttribute()
    {
        return $this->belongsToMany(Attribute::class, config('ibrand.app.database.prefix', 'ibrand_') . 'goods_model_attribute_relation', 'model_id', 'attribute_id');
    }

}
