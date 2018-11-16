<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Discount_conditionRepository;
use App\Entities\DiscountCondition;
use App\Validators\DiscountConditionValidator;

/**
 * Class DiscountConditionRepositoryEloquent
 * @package namespace App\Repositories;
 */
class DiscountConditionRepositoryEloquent extends BaseRepository implements DiscountConditionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DiscountCondition::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getValidCondition($discountID)
    {
        return $this->findWhere(['discount_id' => $discountID, 'status' => 1]);
    }

    public function setFailStatus($discountID)
    {
        $condition = $this->findWhere(['discount_id' => $discountID]);
        foreach ($condition as $val)
        {
            $this->update(['status' => 0], $val->id);
        }
    }

    public function getItemDiscountSku($discountID)
    {
        return $this->findWhere(['discount_id' => $discountID, 'status' => 1],['name'])->toArray();
    }
}
