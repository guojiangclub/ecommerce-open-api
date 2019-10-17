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

use GuoJiangClub\Component\Product\Brand;
use Illuminate\Database\Eloquent\Model as LaravelModel;

class Goods extends LaravelModel
{
    protected $guarded = ['id'];

    protected $hidden = ['cost_price'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'goods');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getKeyCode($type = null)
    {
        return $this->id;
    }

    public function getStockQtyAttribute()
    {
        return $this->store_nums;
    }

    public function getPhotoUrlAttribute()
    {
        return $this->img;
    }

    public function getSpecsTextAttribute()
    {
        return '';
    }

    public function getDetailIdAttribute()
    {
        return $this->id;
    }

    public function getIsInSale($quantity)
    {
        return 0 == $this->is_del && $this->stock_qty >= $quantity;
    }

    public function photos()
    {
        return $this->hasMany(GoodsPhoto::class, 'goods_id');
    }

    public function reduceStock($quantity)
    {
        $this->store_nums = $this->products()->sum('store_nums');
    }

    public function restoreStock($quantity)
    {
        $this->store_nums = $this->store_nums + $quantity;
    }

    public function getArrayTagsAttribute()
    {
        return explode(',', $this->attributes['tags']);
    }

    /**
     * 详情页获取产品规格
     *
     * @return mixed
     */
    public function getSpecValueAttribute()
    {
        return json_decode($this->attributes['spec_array'], true);
    }

    public function specificationValue()
    {
        return $this->belongsToMany(SpecificationValue::class, config('ibrand.app.database.prefix', 'ibrand_').'goods_spec_relation', 'goods_id', 'spec_value_id')
            ->withPivot('spec_id', 'alias', 'img', 'sort')->withTimestamps();
    }

    public function getItemType()
    {
        return 'goods';
    }
}
