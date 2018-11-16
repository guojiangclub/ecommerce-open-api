<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Models;

use Illuminate\Database\Eloquent\Model;

class CardVipCode extends Model
{
    protected $table = 'card_vip_code';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }
}
