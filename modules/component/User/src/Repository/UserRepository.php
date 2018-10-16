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

interface UserRepository extends RepositoryInterface
{
    /**
     * Get a user by the given credentials.
     *
     * @param array $credentials
     *
     * @return mixed
     */
    public function getUserByCredentials(array $credentials);
}
