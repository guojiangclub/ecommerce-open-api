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

use ElementVip\TNF\Core\Models\CardVipCode;

class Card extends \ElementVip\Component\Card\Models\Card
{
    protected $table = 'el_card';

    protected $guarded = ['id'];

    public function codes()
    {
        return $this->hasMany(CardVipCode::class, 'card_id');
    }
}
