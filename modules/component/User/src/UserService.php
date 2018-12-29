<?php

/*
 * This file is part of ibrand/user.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\User;

use iBrand\Component\User\Repository\UserBindRepository;

class UserService
{
    /**
     * bind third party platform to user.
     *
     * @param $userId
     * @param $openId
     * @param $appId
     * @param string $type
     *
     * @throws \Exception
     */
    public function bindPlatform($userId, $openId, $appId, $type = 'wechat')
    {
        if (empty($userId) || empty($openId) || empty($appId)) {
            throw  new \Exception('missing parameter');
        }

        $repository = app(UserBindRepository::class);

        $userBind = $repository->getByOpenId($openId);

        if ($userBind) {
            $repository->update(['user_id' => $userId], $userBind->id);
        } else {
            $repository->create(['open_id' => $openId, 'type' => $type, 'user_id' => $userId, 'app_id' => $appId]);
        }
    }
}
