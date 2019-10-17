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
 * Interface DiscountContract.
 */
/**
 * Interface DiscountContract.
 */
interface DiscountContract
{
    /**
     * @return mixed
     */
    public function hasRules();

    /**
     * @return mixed
     */
    public function isCouponBased();

    /**
     * @return mixed
     */
    public function getActions();

    /**
     * @return mixed
     */
    public function getRules();

    /**
     * @return mixed
     */
    public function setCouponUsed();

    /**
     * @return mixed
     */
    public function getStartsAt();

    /**
     * @return mixed
     */
    public function getEndsAt();

    /**
     * @return mixed
     */
    public function getUsed();

    /**
     * @return mixed
     */
    public function getUsageLimit();
}
