<?php

/*
 * This file is part of ibrand/user.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\User\Repository;

use Prettus\Repository\Contracts\RepositoryInterface;

interface UserBindRepository extends RepositoryInterface
{
    /**
     * get user bind model by openid.
     *
     * @param $openid
     *
     * @return mixed
     */
    public function getByOpenId($openid);

    /**
     * get user bind model by user'id and app'id.
     *
     * @param $userId
     * @param $appId
     *
     * @return mixed
     */
    public function getByUserIdAndAppId($userId, $appId);

    /**
     * bind model to user.
     *
     * @param $openId
     * @param $userId
     *
     * @return mixed
     */
    public function bindToUser($openId, $userId);
}
