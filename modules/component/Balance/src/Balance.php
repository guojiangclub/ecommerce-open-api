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

class Balance extends Model
{
    const TYPE_RECHARGE = 'recharge'; //充值

    const TYPE_EXPEND = 'expend';     //消费

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        $this->setTable($prefix.'balance');
    }

    public static function getBalanceByUserId($userId)
    {
        $sum = self::where('user_id', $userId)->sum('value');
        if (is_null($sum) || $sum <= 0) {
            return 0;
        }

        return $sum;
    }

    public static function getUserBalanceListByType($userId, $type = 'recharge', $limit = 15)
    {
        return self::where('user_id', $userId)->where('type', $type)->orderBy('created_at', 'desc')->paginate($limit);
    }
}
