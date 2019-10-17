<?php

/*
 * This file is part of ibrand/order.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Order\Models;

use GuoJiangClub\Component\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'order_comment');
        parent::__construct($attributes);
    }

    const STATUS_SHOW = 'show';
    const STATUS_HIDDEN = 'hidden';

    public function setItemMetaAttribute($value)
    {
        $this->attributes['item_meta'] = json_encode($value);
    }

    public function getItemMetaAttribute($value)
    {
        return json_decode($value, true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setPicListAttribute($value)
    {
        $this->attributes['pic_list'] = serialize($value);
    }

    public function getPicListAttribute($value)
    {
        return unserialize($value);
    }
}
