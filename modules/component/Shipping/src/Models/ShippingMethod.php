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

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/29
 * Time: 16:01.
 */
class ShippingMethod extends Model
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'shipping_method');

        parent::__construct($attributes);
    }
}
