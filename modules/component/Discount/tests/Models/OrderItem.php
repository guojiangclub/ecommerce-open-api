<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 12:35
 */
namespace GuoJiangClub\Component\Discount\Test\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded=['id'];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'order_item');

        parent::__construct($attributes);
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function recalculateAdjustmentsTotal()
    {
        $this->adjustments_total = $this->divide_order_discount + $this->item_discount;

        $this->recalculateTotal();
    }

    public function recalculateTotal()
    {
        $this->total = $this->units_total + $this->adjustments_total;

        if ($this->total < 0) {
            $this->total = 0;
        }

        if (null !== $this->order) {
            $this->order->recalculateItemsTotal();
        }
    }

}