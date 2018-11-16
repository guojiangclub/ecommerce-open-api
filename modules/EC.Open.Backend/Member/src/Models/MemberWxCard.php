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
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberWxCard extends Model
{
    use SoftDeletes;

    protected $table = 'el_member_wx_card';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'el_member_card_id',
        'card_id',
        'ticket',
        'url',
        'show_qrcode_url',
    ];

    protected $guarded = ['id'];
}
