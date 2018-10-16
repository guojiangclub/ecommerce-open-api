<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Discount\Policies;

use iBrand\Component\Discount\Models\Coupon;
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
