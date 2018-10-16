<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Discount\Contracts;

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
