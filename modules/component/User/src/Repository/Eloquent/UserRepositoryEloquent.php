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

use iBrand\Component\User\Models\User;
use iBrand\Component\User\Repository\UserRepository;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Get a user by the given credentials.
     *
     * @param array $credentials
     *
     * @return mixed
     */
    public function getUserByCredentials(array $credentials)
    {
        $query = $this->model;
        foreach ($credentials as $key => $value) {
            if (!Str::contains($key, 'password') and !empty($value)) {
                $query = $query->where($key, $value);
            }
        }

        return $query->first();
    }
}
