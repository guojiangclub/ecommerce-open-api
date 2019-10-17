<?php

/*
 * This file is part of ibrand/shipping.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Shipping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/29
 * Time: 16:01.
 */
class Shipping extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['name'];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'shipping');

        parent::__construct($attributes);
    }

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function getNameAttribute()
    {
        if (isset($this->method->name)) {
            return $this->method->name;
        }

        return '';
    }
}
