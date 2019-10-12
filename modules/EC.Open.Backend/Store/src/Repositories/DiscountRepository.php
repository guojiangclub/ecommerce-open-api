<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use GuoJiangClub\EC\Open\Backend\Store\Model\ElDiscount;

/**
 * Class DiscountConditionRepositoryEloquent
 *
 * @package namespace App\Repositories;
 */
class DiscountRepository extends BaseRepository
{
	/**
	 * Specify Model class name
	 *
	 * @return string
	 */
	public function model()
	{
		return ElDiscount::class;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot()
	{
		$this->pushCriteria(app(RequestCriteria::class));
	}

	public function getCouponList($where)
	{
		return $this->scopeQuery(function ($query) use ($where) {
			$query = $query->Where(function ($query) use ($where) {
				if (is_array($where)) {
					foreach ($where as $key => $value) {
						if (is_array($value)) {
							list($operate, $va) = $value;
							$query = $query->where($key, $operate, $va);
						} else {
							$query = $query->where($key, $value);
						}
					}
				}
			});

			return $query->orderBy('created_at', 'desc');
		})->paginate(15);
	}

	/**
	 * 获取促销活动、优惠券列表数据
	 *
	 * @param $where
	 * @param $orWhere
	 *
	 * @return mixed
	 */
	public function getDiscountList($where, $orWhere)
	{
		return $this->scopeQuery(function ($query) use ($where, $orWhere) {
			$query = $query->Where(function ($query) use ($where) {
				if (is_array($where)) {
					foreach ($where as $key => $value) {
						if (is_array($value)) {
							list($operate, $va) = $value;
							$query = $query->where($key, $operate, $va);
						} else {
							$query = $query->where($key, $value);
						}
					}
				}
			});

			if (count($orWhere)) {
				$query = $query->orWhere(function ($query) use ($orWhere) {
					if (is_array($orWhere)) {
						foreach ($orWhere as $key => $value) {
							if (is_array($value)) {
								list($operate, $va) = $value;
								$query = $query->where($key, $operate, $va);
							} else {
								$query = $query->where($key, $value);
							}
						}
					}
				});
			}

			return $query->with('discountActions')->orderBy('created_at', 'desc');
		})->paginate(15);
	}
}
