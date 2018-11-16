<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Repository;

use iBrand\EC\Open\Backend\Member\Models\MemberWxCard;
use Prettus\Repository\Eloquent\BaseRepository;

class MemberWxCardRepository extends BaseRepository
{
    public function model()
    {
        return MemberWxCard::class;
    }

    /**
     * 微信卡卷二维码信息.
     *
     * @param int $el_member_card_id
     *
     * @return mixed
     */
    public function searchWxCard($el_member_card_id)
    {
        return $this->model->where('el_member_card_id', '=', $el_member_card_id)->first();
    }
}
