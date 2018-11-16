<?php
namespace ElementVip\Component\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItemUnit extends Model implements Transformable
{
    use SoftDeletes;
    use TransformableTrait;

    protected $table = 'el_order_item_unit';

    protected $guarded = ['id'];

    public function orderItem()
    {
        return $this->belongsTo('iBrand\EC\Open\Backend\Store\Model\OrderItem', 'order_item_id');
    }
}
