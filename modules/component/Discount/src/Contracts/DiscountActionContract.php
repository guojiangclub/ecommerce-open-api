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
 * Interface DiscountActionContract.
 */
interface DiscountActionContract
{
    /**
     * @param DiscountSubjectContract $subject
     * @param array                   $configuration
     * @param DiscountContract        $discount
     *
     * @return mixed
     */
    public function execute(DiscountSubjectContract $subject, array $configuration, DiscountContract $discount);

    /**
     * @param DiscountSubjectContract $subject
     * @param array                   $configuration
     * @param DiscountContract        $discount
     *
     * @return mixed
     */
    public function calculate(DiscountSubjectContract $subject, array $configuration, DiscountContract $discount);
}
