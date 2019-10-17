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

class Attribute extends Model
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'goods_attribute');
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function scopeOfModelIds($query, $modelIds)
    {
        return $query->with('values')->whereIn('model_id', $modelIds)->where('is_search', 1);
    }
}
