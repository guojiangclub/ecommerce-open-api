<?php

/*
 * This file is part of ibrand/user.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserBind extends Model
{
    protected $guarded = ['id'];

    const TYPE_WECHAT = 'wechat';

    /**
     * Address constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'user_bind');
    }

    public static function ByOpenIdAndType($openId, $openType)
    {
        $model = new self();

        return $model->where('open_id', $openId)->where('type', $openType);
    }

    public static function bindUser($openId, $openType, $userId)
    {
        $model = new self();

        return $model->where('open_id', $openId)->where('type', $openType)->update(['user_id' => $userId]);
    }

    public static function ByUserIdAndType($userId, $openType)
    {
        $model = new self();

        return $model->where('user_id', $userId)->where('type', $openType);
    }

    public static function ByAppID($userId, $openType, $appID)
    {
        $model = new self();

        return $model->where('user_id', $userId)->where('type', $openType)->where('app_id', $appID);
    }
}
