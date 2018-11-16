<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/1
 * Time: 13:37
 */

namespace iBrand\EC\Open\Backend\Store\Observers;


use ElementVip\Component\Suit\Models\Suit;

class SuitObserver
{
    public function saved(Suit $suit)
    {
        if ($suit->recommend == 1) {
            Suit::where('id', '<>', $suit->id)->update(['recommend' => 0]);
        }
    }
}