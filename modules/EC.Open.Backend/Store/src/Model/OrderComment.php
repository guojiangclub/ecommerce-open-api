<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

use Illuminate\Database\Eloquent\SoftDeletes;
use GuoJiangClub\EC\Open\Backend\Store\Model\Relations\BelongToUserTrait;

class OrderComment extends Model implements Transformable
{
    use SoftDeletes;
    use TransformableTrait;
    use BelongToUserTrait;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        $this->setTable($prefix . 'order_comment');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id')->withDefault();
    }

    public function getCommentPicAttribute()
    {
        $images = '';
        $pic = unserialize($this->pic_list);

        if (is_array($pic) AND count($pic) > 0) {
            foreach ($pic as $item) {
                $images = $images . '<img width="50" src="' . $item . '" />&nbsp;&nbsp;';
            }
        }
        return $images;
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class, 'item_id')->withDefault();
    }

    public function getEditNameAttribute()
    {
        if ($user = $this->user) {
            return $user->nick_name ? $user->nick_name : $user->mobile;
        }
       
        return '';
    }
}
