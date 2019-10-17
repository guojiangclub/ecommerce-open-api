<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Policies;

use GuoJiangClub\Component\Discount\Models\Coupon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

class CouponPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Coupon $coupon)
    {
        return $user->id === $coupon->user_id;
    }
}
