<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Product extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'el_goods_product';
    protected $guarded = ['id'];

    public function goods()
    {
        return $this->belongsTo('iBrand\EC\Open\Backend\Store\Model\Goods','goods_id');
    }

    public function o2oProducts()
    {
        return $this->hasMany('ElementVip\Shop\Core\Models\O2oGoodsProducts', 'product_id');
    }
    
    public function getSpecStringAttribute()
    {
        $specStr = '';       
        if ($this->attributes['spec_array']) {
            $specArr = json_decode($this->attributes['spec_array'],TRUE);
            foreach ($specArr as $key => $val) {
                $specStr = $specStr . ',' . $val['value'];
            }

        }
        return ltrim($specStr, ",");

    }

    public function photo()
    {
        return $this->hasOne('iBrand\EC\Open\Backend\Store\Model\GoodsPhoto', 'sku', 'sku');
    }

    public function setSpecIDAttribute($value)
    {
        $this->attributes['specID']  = json_encode(explode('-', $value));
//        $this->attributes['specID']  = json_encode($value);
    }
    
    public function getSpecIDAttribute($value)
    {
        return json_decode($value);
    }

//    public function setIsShowAttribute($value)
//    {
//        if($value == '不启用'){
//            $this->attributes['is_show'] = 1;
//        }else{
//            $this->attributes['is_show'] = 0;
//        }
//
//    }
//
//    public function getIsShowAttribute($value)
//    {
//        return $value == 1 ? '不启用' : '启用';
//    }

    public function scopeJudge($query, $sku, $id = 0)
    {
        return $query->where('sku', $sku)->where('id', '<>' ,$id)->get();
    }
}
