<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Registrations extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'el_registration';
    protected $guarded = ['id'];
    
    public function product()
    {
        return $this->hasOne('iBrand\EC\Open\Backend\Store\Model\Product', 'sku', 'sku');
    }
    
    public function photo()
    {
        return $this->hasOne('iBrand\EC\Open\Backend\Store\Model\GoodsPhoto', 'sku', 'sku');
    }

    public function brand(){
        return $this->belongsTo('iBrand\EC\Open\Backend\Store\Model\Brand','brand_id','id');
    }

    public function order(){
        return $this->hasOne('iBrand\EC\Open\Backend\Store\Model\Order','id','order_id');
    }

    public function deliveries_data()
    {
        return $this->hasOne('ElementVip\Component\Registration\Models\ErpDeliveriesData','sn','sn');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



}
