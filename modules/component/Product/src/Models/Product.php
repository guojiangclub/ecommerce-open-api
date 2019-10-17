<?php

/*
 * This file is part of ibrand/product.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['cost_price'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'goods_product');
    }

    public function getStockQtyAttribute()
    {
        return $this->store_nums;
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function getNameAttribute()
    {
        return $this->goods->name;
    }

    public function getIsDelAttribute()
    {
        return $this->goods->is_del;
    }

    public function reduceStock($quantity)
    {
        $this->store_nums = $this->store_nums - $quantity;
        $this->goods->reduceStock($quantity);
    }

    public function restoreStock($quantity)
    {
        $this->store_nums = $this->store_nums + $quantity;
        $this->goods->restoreStock($quantity);
    }

    public function increaseSales($quantity)
    {
        $this->goods->sale = $this->goods->sale + $quantity;
    }

    public function restoreSales($quantity)
    {
        $this->goods->sale = $this->goods->sale - $quantity;
    }

    public function getIsInSale($quantity)
    {
        return 0 == $this->goods->is_del && $this->stock_qty >= $quantity;
    }

    public function getPhotoUrlAttribute()
    {
        if ($specIds = $this->specID) {
            foreach ($specIds as $value) {
                $specValue = SpecificationValue::find($value);
                if (2 != $specValue->spec_id) {
                    continue;
                }
                if ($specRelation = SpecificationRelation::where('goods_id', $this->goods_id)->where('spec_value_id', $value)->first()) {
                    return $specRelation->img;
                }
            }
        }

        return '';
    }

    public function getDetailIdAttribute()
    {
        return $this->goods_id;
    }

    public function getSpecsTextAttribute()
    {
        $specText = [];

        if ($specIds = $this->spec_ids) {
            foreach ($specIds as $value) {
                $specValue = SpecificationValue::find($value);
                if ($specRelation = SpecificationRelation::where('goods_id', $this->goods_id)->where('spec_value_id', $value)->first()
                    and $specRelation->alias) {
                    $specText[$specValue->spec_id] = $specRelation->alias;
                } else {
                    $specText[$specValue->spec_id] = $specValue->name;
                }
            }
            if (array_key_exists(1, $specText)) {
                krsort($specText);
            } else {
                ksort($specText);
            }
        }

        return implode(' ', array_values($specText));
    }

    public function getKeyCode($type = 'sku')
    {
        if ('spu' == $type) {
            return $this->goods_id;
        }

        return $this->sku;
    }

    public function getSpecIdsAttribute($value)
    {
        return json_decode($value);
    }

    public function getItemType()
    {
        return 'product';
    }

    public function getChildKeyCodes()
    {
        return [];
    }

    public function getMarketPriceAttribute($value)
    {
        if (empty($value)) {
            return $this->goods->market_price;
        }

        return $value;
    }
}
