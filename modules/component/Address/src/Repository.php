<?php

/*
 * This file is part of ibrand/address.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Address;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class Repository.
 */
class Repository extends BaseRepository implements RepositoryContract
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Address::class;
    }


    /**
     * @param array $attributes
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(array $attributes = [])
    {
        $address = parent::create($attributes);

        $this->setDefault($address);

        return $address;
    }


    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(array $attributes, $id)
    {
        $address = parent::update($attributes, $id);

        $this->setDefault($address);

        return $address;
    }

    /**
     * @param Address $address
     */
    protected function setDefault(Address $address)
    {
        if (1 == $address->is_default) {
            $this->model->where('user_id', $address->user_id)
                ->where('id', '!=', $address->id)->update(['is_default' => 0]);
        }
    }

    /**
     * @param $userId
     *
     * @return mixed
     */
    public function getByUser($userId)
    {
        return $this->orderBy('updated_at', 'desc')->findByField('user_id', $userId);
    }


    /**
     * @param $userId
     * @return mixed|null
     */
    public function getDefaultByUser($userId)
    {
        $addresses = $this->getByUser($userId);

        if (0 == count($addresses)) {
            return null;
        }

        $default = $addresses->filter(function ($address) {
            return 1 == $address->is_default;
        })->first();

        if (!$default) {
            $default = $addresses->first();
        }

        return $default;
    }


    /**
     * @param array $attributes
     * @param $id
     * @param $userId
     * @return bool|mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateByUser(array $attributes, $id, $userId)
    {
        $address = $this->find($id);

        if ($address->user_id != $userId) {
            return false;
        }

        return $this->update($attributes, $id);
    }
}
