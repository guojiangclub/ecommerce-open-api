<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Repository;

use Carbon\Carbon;
use ElementVip\Component\Discount\Repositories\Eloquent\CouponRepositoryEloquent;

class CouponRepository extends CouponRepositoryEloquent
{
    /**
     * 优惠券领用统计
     *
     * @param $coupon_id
     * @param $time
     *
     * @return mixed
     */
    public function getCouponCodeCount($coupon_id, $where, $time)
    {
        return  $this->scopeQuery(function ($query) use ($coupon_id, $where, $time) {
            $query = $query->where('discount_id', $coupon_id);

            if (is_array($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            if ($time) {
                foreach ($time as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            return $query->orderBy('updated_at', 'desc');
        })->all()->count();
    }

    /**
     * 根据导入的优惠券数据更新优惠券使用状态
     *
     * @param $couponCode
     *
     * @return mixed
     */
    public function updateOfflineCouponStatus($couponCode)
    {
        $couponCode = $this->findWhere(['code' => $couponCode])->first();

        if ($couponCode) {
            $couponCode->is_used = 1;
            $couponCode->used_at = Carbon::now();
            $couponCode->save();

            return $couponCode;
        }

        return false;
    }
}
