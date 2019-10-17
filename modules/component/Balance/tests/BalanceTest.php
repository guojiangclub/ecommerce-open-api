<?php

/*
 * This file is part of ibrand/balace.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Balance\Test;

use GuoJiangClub\Component\Balance\Balance;

class BalanceTest extends BaseTest
{
    public function testBalanceModels()
    {
        //How much is the current balance of the user
        $value_user_1 = Balance::getBalanceByUserId(1);
        $this->assertSame('2000', $value_user_1);

        $value_user_2 = Balance::getBalanceByUserId(2);
        $this->assertSame('1000', $value_user_2);

        $value_user_3 = Balance::getBalanceByUserId(100);
        $this->assertSame(0, $value_user_3);

        //Access to a list of users' integral records based on type (recharge or consumption), page reading
        $recharge_list = Balance::getUserBalanceListByType(1, 'recharge');
        $this->assertSame(1, $recharge_list->count());

        $expend_list = Balance::getUserBalanceListByType(2, 'expend');
        $this->assertSame('Illuminate\Pagination\LengthAwarePaginator', get_class($expend_list));
        $this->assertSame(2, $expend_list->count());
    }
}
