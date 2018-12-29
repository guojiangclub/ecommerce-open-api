<?php

/*
 * This file is part of ibrand/user.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\User\Repository\Eloquent;

use iBrand\Component\User\Models\UserBind;
use iBrand\Component\User\Repository\UserBindRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

class UserBindRepositoryEloquent extends BaseRepository implements UserBindRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return UserBind::class;
    }

    /**
     * get user bind model by openid.
     *
     * @param $openid
     *
     * @return mixed
     */
    public function getByOpenId($openid)
    {
        return $this->findByField('open_id', $openid)->first();
    }

    /**
     * get user bind model by user'id and app'id.
     *
     * @param $userId
     * @param $appId
     *
     * @return mixed
     */
    public function getByUserIdAndAppId($userId, $appId)
    {
        return $this->findWhere(['user_id' => $userId, 'app_id' => $appId]);
    }

    /**
     * @param $openId
     * @param $userId
     *
     * @return mixed
     *
     * @throws \Exception
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function bindToUser($openId, $userId)
    {
        $userBind = $this->getByOpenId($openId);
        if (!$userBind) {
            throw new \Exception('This user bind model does not exist.');
        }

        return $this->update(['user_id' => $userId], $userBind->id);
    }
}
