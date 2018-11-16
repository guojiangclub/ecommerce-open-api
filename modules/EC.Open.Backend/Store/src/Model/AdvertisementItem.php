<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class AdvertisementItem extends Model implements Transformable
{

    use TransformableTrait;
    protected $table = 'el_ad_item';
    protected $guarded = ['id'];

}