<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/1
 * Time: 13:12
 */

namespace iBrand\EC\Open\Backend\Store\Observers;


use iBrand\EC\Open\Backend\Store\Model\SeckillItem;

class SeckillItemObserver
{
    public function saved(SeckillItem $seckillItem)
    {
        if ($seckillItem->recommend == 1) {
            SeckillItem::where('id', '<>', $seckillItem->id)->update(['recommend' => 0]);
        }
    }
}