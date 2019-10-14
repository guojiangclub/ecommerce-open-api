<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Attribute extends Model implements Transformable
{
    use TransformableTrait;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'goods_attribute');
    }

    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['value'] = implode(',', $value);
        } else {
            $this->attributes['value'] = '';
        }

    }

    public function getSelectValueAttribute()
    {
        if ($this->type == 1) {
            return explode(',', $this->attributes['value']);
        }
        return [];

    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

}
