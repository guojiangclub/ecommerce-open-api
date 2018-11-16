<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

use Illuminate\Database\Eloquent\SoftDeletes;
use iBrand\EC\Open\Backend\Store\Model\Relations\BelongToUserTrait;

class OrderComment extends Model implements Transformable
{
    use SoftDeletes;
    use TransformableTrait;
    use BelongToUserTrait;

    protected $table = 'el_order_comment';
    protected $guarded = ['id'];

    public function orderItem()
    {
        return $this->belongsTo('iBrand\EC\Open\Backend\Store\Model\OrderItem', 'order_item_id')->withDefault();
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
        return $this->belongsTo('iBrand\EC\Open\Backend\Store\Model\Goods', 'goods_id')->withDefault();
    }

    public function getEditNameAttribute()
    {
        if ($user = $this->user) {
            return $user->nick_name ? $user->nick_name : $user->mobile;
        }
        if ($user = $this->attributes['user_meta']) {
           
            return json_decode($user, true)['nick_name'];
        }
        return '';
    }
}
