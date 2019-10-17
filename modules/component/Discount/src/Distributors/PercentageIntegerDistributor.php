<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Distributors;

class PercentageIntegerDistributor
{
    public function distribute(array $integers, int $amount)
    {
        $total = array_sum($integers);

        $distributedAmounts = [];

        foreach ($integers as $element) {
            $distributedAmounts[] = (int) round(($element * $amount) / $total, 0, PHP_ROUND_HALF_DOWN);
        }

        $missingAmount = $amount - array_sum($distributedAmounts);
        for ($i = 0; $i < abs($missingAmount); ++$i) {
            $distributedAmounts[$i] += $missingAmount >= 0 ? 1 : -1;
        }

        return $distributedAmounts;
    }
}
