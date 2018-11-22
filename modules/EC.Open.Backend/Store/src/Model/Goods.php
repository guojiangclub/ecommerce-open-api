<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Goods extends Model implements Transformable
{
    use TransformableTrait;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'goods');
    }

    public function hasOnePoint()
    {
        return $this->hasOne('ElementVip\Component\Point\Model\PointGoods', 'item_id');
    }

    public function hasManyProducts()
    {
        return $this->hasMany('iBrand\EC\Open\Backend\Store\Model\Product', 'goods_id');
    }

    public function hasManyAttribute()
    {
        return $this->belongsToMany('iBrand\EC\Open\Backend\Store\Model\Attribute', config('ibrand.app.database.prefix', 'ibrand_').'goods_attribute_relation', 'goods_id', 'attribute_id')
            ->withPivot('attribute_value_id', 'model_id', 'attribute_value');
    }

    public function model()
    {
        return $this->belongsTo('iBrand\EC\Open\Backend\Store\Model\Models', 'model_id', 'id');
    }

    public function hasManySpec()
    {
        return $this->belongsToMany('iBrand\EC\Open\Backend\Store\Model\Spec', config('ibrand.app.database.prefix', 'ibrand_').'goods_spec_relation', 'goods_id', 'spec_id');
    }

    public function category()
    {
        return $this->belongsTo('iBrand\EC\Open\Backend\Store\Model\Category', 'category_id');
    }

    public function categories()
    {
        return $this->belongsToMany('iBrand\EC\Open\Backend\Store\Model\Category', config('ibrand.app.database.prefix', 'ibrand_').'goods_category', 'goods_id', 'category_id');
    }

    public function specs()
    {
        return $this->belongsToMany('iBrand\EC\Open\Backend\Store\Model\Spec', config('ibrand.app.database.prefix', 'ibrand_').'goods_spec_relation', 'goods_id', 'spec_id')
            ->withPivot('spec_value', 'category_id')->withTimestamps();
    }


    public function GoodsPhotos()
    {
        return $this->hasMany('iBrand\EC\Open\Backend\Store\Model\GoodsPhoto', 'goods_id');
    }

    public function SearchSpec()
    {
        return $this->hasMany('iBrand\EC\Open\Backend\Store\Model\SearchSpec', 'goods_id');
    }

    public function getArrayTagsAttribute()
    {
        return explode(',', $this->attributes['tags']);
    }


    public function setImglistAttribute($value)
    {
        $this->attributes['imglist'] = serialize($value);
    }

    public function getImglistAttribute($value)
    {
        $data = unserialize($value);
        return $data ? $data : [];
    }

//    public function setSpecArrayAttribute($value)
//    {
//        if(count($value)) {
//            $goods_spec_array = array();
//            foreach ($value as $key => $val) {
//                foreach ($val as $v) {
//                    $tempSpec = json_decode($v, true);
//                    if (!isset($goods_spec_array[$tempSpec['id']])) {
//                        $goods_spec_array[$tempSpec['id']] = array('id' => $tempSpec['id'], 'name' => $tempSpec['name'], 'type' => $tempSpec['type'], 'value' => array());
//                    }
//                    $goods_spec_array[$tempSpec['id']]['value'][] = $tempSpec['value'];
//                }
//            }
//            foreach ($goods_spec_array as $key => $val) {
//                $val['value'] = array_unique($val['value']);
//                $goods_spec_array[$key]['value'] = join(',', $val['value']);
//            }
//            return $this->attributes['spec_array'] = json_encode($goods_spec_array, JSON_UNESCAPED_UNICODE);
//        }else{
//            return $this->attributes['spec_array'] = '';
//        }
//
//    }
//
//    public function getSpecValueAttribute()
//    {
//        return json_decode($this->attributes['spec_array'], true);
//    }

    public function specValue()
    {
        return $this->belongsToMany('iBrand\EC\Open\Backend\Store\Model\SpecsValue', 'el_goods_spec_relation', 'goods_id', 'spec_value_id')
            ->withPivot('spec_id', 'alias', 'img', 'sort')->withTimestamps();
    }

    public function setExtraAttribute($value)
    {
        if ($value) {
            $this->attributes['extra'] = json_encode($value);
        }
    }

    public function getExtraAttribute($value)
    {
        if ($value) {
            return json_decode($value);
        }
        return '';
    }

    /**
     * 获取商品价格区间
     * @return string
     */
    public function getGoodsSectionSellPriceAttribute()
    {
        $min = $this->sell_price;
        $max = $this->sell_price;
        if ($min_price = $this->min_price) {
            $min = $min_price;
        }

        if ($max_price = $this->max_price) {
            $max = $max_price;
        }

        if ($min == $max) {
            return '￥ ' . $min;
        }
        return '￥ ' . $min . ' - ￥ ' . $max;
    }

    public function setExtendImageAttribute($value)
    {
        if ($value) {
            $this->attributes['extend_image'] = json_encode($value);
        }
    }

    public function getExtendImageAttribute()
    {
        if ($this->attributes['extend_image']) {
            return json_decode($this->attributes['extend_image'], true);
        }
    }
}
