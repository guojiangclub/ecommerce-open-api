<?php

/*
 * This file is part of ibrand/product.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Brand.
 */
class Brand extends Model
{
    /**
     * open status value.
     */
    const STATUS_OPEN = 1;
    /**
     * close status value.
     */
    const STATUS_CLOSE = 0;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Brand constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'brand');
    }
}
