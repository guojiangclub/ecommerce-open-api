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

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User.
 */
class User extends Authenticatable
{
    use  Notifiable;

    /**
     * User Status.
     */
    const STATUS_FORBIDDEN = 2;
    const STATUS_ENABLED = 1;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Address constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'user');
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        if (\Hash::needsRehash($value)) {
            $value = bcrypt($value);
        }

        return $this->attributes['password'] = $value;
    }
}
