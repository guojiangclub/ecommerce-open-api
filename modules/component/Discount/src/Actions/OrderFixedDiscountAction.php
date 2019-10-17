<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Actions;

use GuoJiangClub\Component\Discount\Contracts\DiscountActionContract;
use GuoJiangClub\Component\Discount\Contracts\DiscountContract;
use GuoJiangClub\Component\Discount\Contracts\DiscountSubjectContract;
use GuoJiangClub\Component\Discount\Distributors\PercentageIntegerDistributor;
use Illuminate\Support\Collection;

/**
 * Class OrderFixedDiscountAction.
 */
class OrderFixedDiscountAction extends DiscountAction implements DiscountActionContract
{
    const TYPE = 'order_fixed_discount';

    /**
     * @var IntegerDistributor
     */
    private $distributor;

    /**
     * OrderFixedDiscountAction constructor.
     *
     * @param IntegerDistributor $distributor
     */
    public function __construct(PercentageIntegerDistributor $distributor)
    {
        $this->distributor = $distributor;
    }

    /**
     * @param DiscountSubjectContract $subject
     * @param array                   $configuration
     * @param DiscountContract        $discount
     *
     * @return mixed|void
     */
    public function execute(DiscountSubjectContract $subject, array $configuration, DiscountContract $discount)
    {
        $discountAmount = $this->calculateAdjustmentAmount($subject->getCurrentTotal(), $configuration['amount']);

        if (0 === $discountAmount) {
            return;
        }

        $adjustment = $this->createAdjustment($discount, $discountAmount);
        $subject->addAdjustment($adjustment);

        $splitDiscountAmount = $this->distributor->distribute($subject->getItems()->pluck('total')->toArray(), $discountAmount);

        $i = 0;
        foreach ($subject->getItems() as $item) {
            $item->divide_order_discount += $splitDiscountAmount[$i++];
            $item->recalculateAdjustmentsTotal();
        }
    }

    /**
     * @param $discountSubjectTotal
     * @param $targetDiscountAmount
     *
     * @return float|int
     */
    private function calculateAdjustmentAmount($discountSubjectTotal, $targetDiscountAmount)
    {
        return -1 * min($discountSubjectTotal, $targetDiscountAmount);
    }

    /**
     * @param DiscountSubjectContract $subject
     * @param array                   $configuration
     * @param DiscountContract        $discount
     *
     * @return float|int|mixed
     */
    public function calculate(DiscountSubjectContract $subject, array $configuration, DiscountContract $discount)
    {
        $discount->adjustments = new Collection();

        $discountAmount = $this->calculateAdjustmentAmount($subject->getCurrentTotal(), $configuration['amount']);

        $discount->adjustments->push(['order_id' => $subject->id, 'amount' => $discountAmount]);

        $discount->adjustmentTotal = $discountAmount;

        return $discountAmount;
    }
}
