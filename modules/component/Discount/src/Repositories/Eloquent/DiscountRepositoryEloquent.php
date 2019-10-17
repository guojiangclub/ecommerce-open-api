<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Repositories\Eloquent;

use Carbon\Carbon;
use GuoJiangClub\Component\Discount\Models\Discount;
use GuoJiangClub\Component\Discount\Repositories\DiscountRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class DiscountRepositoryEloquent extends BaseRepository implements DiscountRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Discount::class;
    }

    /**
     * get active discount.
     *
     * @param int $isCoupon 0:discount 1:coupon 2:all
     *
     * @return mixed
     */
    public function findActive($isCoupon = 0)
    {
        $query = $this->model->where('status', 1);

        if (2 != $isCoupon) {
            $query = $query->where('coupon_based', $isCoupon);
        }

        return $query
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere(function ($query) {
                        $query->where('starts_at', '<', Carbon::now());
                    });
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere(function ($query) {
                        $query->where('ends_at', '>', Carbon::now());
                    });
            })->with('rules', 'actions')->get();
    }
}
