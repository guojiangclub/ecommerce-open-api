<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    /**
     * Address constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'discount_rule');

        parent::__construct($attributes);
    }
}
