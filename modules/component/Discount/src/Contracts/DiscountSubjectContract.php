<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Contracts;

/**
 * Interface DiscountSubjectContract.
 */
interface DiscountSubjectContract
{
    /**
     * get subject total amount.
     *
     * @return int
     */
    public function getSubjectTotal();

    /**
     * get subject count item.
     *
     * @return int
     */
    public function getSubjectCount();

    /**
     * get subject items.
     *
     * @return mixed
     */
    public function getItems();

    /**
     * get subject count.
     *
     * @return mixed
     */
    public function countItems();

    /**
     * @param $adjustment
     *
     * @return mixed
     */
    public function addAdjustment($adjustment);

    /**
     * get subject user.
     *
     * @return mixed
     */
    public function getSubjectUser();

    /**
     * get current total.
     *
     * @return mixed
     */
    public function getCurrentTotal();

    /**
     * get subject is paid.
     *
     * @return mixed
     */
    public function isPaid();
}
