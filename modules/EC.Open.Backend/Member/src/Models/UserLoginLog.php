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

class UserLoginLog extends Model
{
    protected $table = 'el_admin_login_log';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getInfoAttribute()
    {
        $info = json_decode($this->ip_info, true);
        if (0 == $info['code']) {
            $data = $info['data'];

            return '国家：'.$data['country'].' 省：'.$data['region'].' 市：'.$data['city'].' 运营商：'.$data['isp'];
        }

        return '';
    }
}
