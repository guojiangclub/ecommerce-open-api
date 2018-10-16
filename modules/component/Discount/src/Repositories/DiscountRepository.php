<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Discount\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface DiscountRepository.
 */
interface DiscountRepository extends RepositoryInterface
{
    /**
     * @param int $isCoupon
     *
     * @return mixed
     */
    public function findActive($isCoupon = 0);
}
