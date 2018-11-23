<?php

/*
 * This file is part of ibrand/point.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Point\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
	protected $guarded = ['id'];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		$prefix = config('ibrand.app.database.prefix', 'ibrand_');

		$this->setTable($prefix . 'point');
	}

	public function scopeValid($query)
	{
		$current = date('Y-m-d H:i:s', time());

		return $query->where('valid_time', '>=', $current)->orWhere('valid_time', null)->where('status', 1);
	}

	public function scopeOverValid($query)
	{
		$current = date('Y-m-d H:i:s', time());

		return $query->where('valid_time', '<', $current)->where('valid_time', '!=', null)->where('status', 1);
	}

	public function scopeWithinTime($query)
	{
		$current = date('Y-m-d H:i:s', time());

		return $query->whereRaw('valid_time>\'' . $current . '\' or valid_time = null');
	}
}
