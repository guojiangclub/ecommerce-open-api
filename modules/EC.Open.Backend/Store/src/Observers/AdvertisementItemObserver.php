<?php

namespace iBrand\EC\Open\Backend\Store\Observers;

use Cache;
use iBrand\EC\Open\Backend\Store\Model\AdvertisementItem;

class AdvertisementItemObserver
{
    public function saved(AdvertisementItem $advertisement)
    {
        /*Cache::forget('mobile-home-index');*/
    }
}
