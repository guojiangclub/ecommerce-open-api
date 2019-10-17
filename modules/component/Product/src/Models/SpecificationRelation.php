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

class SpecificationRelation extends Model
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'goods_spec_relation');
    }

    public function spec()
    {
        return $this->belongsTo(Specification::class, 'spec_id');
    }

    public function specValue()
    {
        return $this->belongsTo(SpecificationValue::class, 'spec_value_id');
    }
}
