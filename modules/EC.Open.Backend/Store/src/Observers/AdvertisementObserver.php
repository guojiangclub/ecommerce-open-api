<?php

namespace iBrand\EC\Open\Backend\Store\Observers;

use Cache;
use iBrand\EC\Open\Backend\Store\Model\Advertisement;

class AdvertisementObserver
{
    public function saved(Advertisement $advertisement)
    {
        /*Cache::forget('mobile-home-index');*/
    }
}
