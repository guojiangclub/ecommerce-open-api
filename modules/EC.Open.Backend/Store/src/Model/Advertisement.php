<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Advertisement extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'el_ad';
    protected $guarded = ['id'];

    public function  hasManyData(){
        return $this->hasMany('\iBrand\EC\Open\Backend\Store\Model\AdvertisementItem','ad_id','id');
    }

}
