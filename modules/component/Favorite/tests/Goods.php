<?php

/*
 * This file is part of ibrand/favorite.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Favorite\Test;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $guarded = ['id'];

	/**
	 * Address constructor.
	 *
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		$prefix = config('ibrand.app.database.prefix', 'ibrand_');

		$this->setTable($prefix . 'goods');
	}
}
