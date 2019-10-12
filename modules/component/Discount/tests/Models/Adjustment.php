<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 12:36
 */
namespace GuoJiangClub\Component\Discount\Test\Models;

use GuoJiangClub\Component\Discount\Contracts\AdjustmentContract;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model implements AdjustmentContract
{
    protected $guarded=['id'];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'order_adjustment');

        parent::__construct($attributes);
    }

    /**
     * create a adjustment.
     *
     * @param $type
     * @param $label
     * @param $amount
     * @param $originId
     * @param $originType
     *
     * @return mixed
     */
    public function createNew($type, $label, $amount, $originId, $originType)
    {
        return new self(['type'=>$type,'label'=>$label,'amount'=>$amount,'origin_id'=>$originId,'origin_type'=>$originType]);
    }
}