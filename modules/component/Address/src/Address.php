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

use Illuminate\Database\Eloquent\Model;

/**
 * Class Address.
 */
class Address extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'addresses');
    }
}
