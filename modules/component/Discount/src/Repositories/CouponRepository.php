<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface CouponRepository extends RepositoryInterface
{
    /**
     * 获取有效优惠券列表.
     *
     * @param $userId
     * @param int $paginate
     *
     * @return mixed
     */
    public function findActiveByUser($userId, $paginate = 15);

    /**
     * 获取已过期优惠券.
     *
     * @param $userId
     * @param int $paginate
     *
     * @return mixed
     */
    public function findInvalidByUser($userId, $paginate = 15);

    /**
     * 获取已使用的优惠券.
     *
     * @param $userId
     * @param int $paginate
     *
     * @return mixed
     */
    public function findUsedByUser($userId, $paginate = 15);
}
