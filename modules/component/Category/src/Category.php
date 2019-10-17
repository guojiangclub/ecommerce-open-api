<?php

/*
 * This file is part of ibrand/category.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Category;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    const STATUS_OPEN = 1; // 可用状态

    const STATUS_CLOSE = 0;  // 关闭状态

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
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'category');

        parent::__construct($attributes);

    }
}
