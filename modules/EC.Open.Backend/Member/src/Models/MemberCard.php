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

class MemberCard extends Model
{
    use SoftDeletes;

    protected $table = 'el_member_card';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'store_name',
        'store_logo',
        'store_logo_wx',
        'code_type',
        'card_cover',
        'bg_color',
        'bg_color_name',
        'bg_img',
        'bg_img_wx',
        'privilege_desc',
        'use_notice',
        'use_description',
        'service_phone',
        'custom_field1_name',
        'custom_field1_url',
        'custom_field2_name',
        'custom_field2_url',
        'custom_field3_name',
        'custom_field3_url',
        'center_title',
        'center_url',
        'custom_cell1_name',
        'custom_cell1',
        'custom_cell1_tips',
        'custom_url_name',
        'custom_url',
        'custom_url_tips',
        'promotion_url_name',
        'promotion_url',
        'promotion_url_tips',
        'upgrade_amount',
        'period',
        'grade',
        'isActivate',
        'status',
        'isSyncWeixin',
        'consumeType',
    ];

    protected $guarded = ['id'];

    public function wxcard()
    {
        return $this->hasOne('iBrand\EC\Open\Backend\Member\Models\MemberWxCard', 'el_member_card_id', 'id');
    }
}
