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
 * Class OrderPercentageDiscountAction.
 */
class OrderPercentageDiscountAction extends DiscountAction implements DiscountActionContract
{
    const TYPE = 'order_percentage_discount';

    /**
     * @var IntegerDistributor
     */
    private $integerDistributor;

    /**
     * OrderPercentageDiscountAction constructor.
     *
     * @param PercentageIntegerDistributor $integerDistributor
     * @param IntegerDistributor           $distributor
     */
    public function __construct(PercentageIntegerDistributor $integerDistributor)
    {
        $this->integerDistributor = $integerDistributor;
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
        $discountAmount = $this->calculateAdjustmentAmount($subject->getCurrentTotal(), $configuration['percentage']);
        if (0 === $discountAmount) {
            return;
        }

        $adjustment = $this->createAdjustment($discount, $discountAmount);
        $subject->addAdjustment($adjustment);

        $splitDiscountAmount = $this->integerDistributor->distribute($subject->getItems()->pluck('total')->toArray(), $discountAmount);

        $i = 0;
        foreach ($subject->getItems() as $item) {
            $item->divide_order_discount += $splitDiscountAmount[$i++];
            $item->recalculateAdjustmentsTotal();
        }
    }

    /**
     * @param $discountSubjectTotal
     * @param $percentage
     *
     * @return float|int
     */
    private function calculateAdjustmentAmount($discountSubjectTotal, $percentage)
    {
        return -1 * (int) round($discountSubjectTotal * (1 - $percentage / 100));
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

        $discountAmount = $this->calculateAdjustmentAmount($subject->getCurrentTotal(), $configuration['percentage']);

        $discount->adjustments->push(['order_id' => $subject->id, 'amount' => $discountAmount]);

        $discount->adjustmentTotal = $discountAmount;

        return $discountAmount;
    }
}
