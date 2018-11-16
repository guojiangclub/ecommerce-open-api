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

class Message extends \iBrand\Component\Message\Models\Message
{
    public function setExtraAttribute($value)
    {
        if ($value) {
            $this->attributes['extra'] = json_encode($value);
        }
    }

    public function getExtraAttribute()
    {
        return json_decode($this->attributes['extra']);
    }
}
