<?php

/*
 * This file is part of ibrand/balance.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Balance;

use Illuminate\Database\Eloquent\Model;

class BalanceOrder extends Model
{
    const STATUS_NEW = 0;    //待支付

    const STATUS_PAY = 1;    //已支付

    const STATUS_REFUND = 2; //已退款

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        $this->setTable($prefix.'balance_order');
    }
}
