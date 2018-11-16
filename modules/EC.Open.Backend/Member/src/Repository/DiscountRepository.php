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

use ElementVip\Component\Discount\Repositories\Eloquent\DiscountRepositoryEloquent;

class DiscountRepository extends DiscountRepositoryEloquent
{
    // 获取线上或线下优惠券促销ID
    public function getDiscountIDByType($type)
    {
        return  $this->findWhere(['type' => $type, 'coupon_based' => 1])->pluck('id')->toArray();
    }

    /**
     * 获取促销活动、优惠券列表数据.
     *
     * @param $where
     * @param $orWhere
     *
     * @return mixed
     */
    public function getDiscountList($where, $orWhere)
    {
        return $this->scopeQuery(function ($query) use ($where,$orWhere) {
            $query = $query->Where(function ($query) use ($where) {
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
            });

            if (count($orWhere)) {
                $query = $query->orWhere(function ($query) use ($orWhere) {
                    if (is_array($orWhere)) {
                        foreach ($orWhere as $key => $value) {
                            if (is_array($value)) {
                                list($operate, $va) = $value;
                                $query = $query->where($key, $operate, $va);
                            } else {
                                $query = $query->where($key, $value);
                            }
                        }
                    }
                });
            }

            return $query->orderBy('created_at', 'desc');
        })->all();
    }
}
