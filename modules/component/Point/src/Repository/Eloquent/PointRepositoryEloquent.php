<?php

/*
 * This file is part of ibrand/point.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Point\Repository\Eloquent;

use Carbon\Carbon;
use iBrand\Component\Point\Models\Point;
use iBrand\Component\Point\Repository\PointRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class PointRepositoryEloquent extends BaseRepository implements PointRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Point::class;
    }

    public function getSumPointValid($user_id)
    {
        return $this->model->where('user_id', $user_id)->valid()->sum('value');
    }


    public function getListPoint($id, $valid = 0)
    {
        $query = $this->model->where('user_id', $id);
        if (0 == $valid) {
            $query = $query->get();
        } elseif (1 == $valid) {
            $query = $query->valid()->get();
        } else {
            $query = $query->overValid()->get();
        }

        return $query;
    }

    /**
     * get point by item's type and item's id;
     * @param $itemType
     * @param $itemId
     * @return mixed
     */
    public function getPointByItem($itemType, $itemId)
    {
        return $this->findWhere(['item_type' => $itemType, 'item_id' => $itemId])->first();
    }

    public function getPointsByConditions($where, $limit = 20)
    {
        $this->applyConditions($where);
        return $this->orderBy('created_at', 'desc')->paginate($limit);
    }
}
