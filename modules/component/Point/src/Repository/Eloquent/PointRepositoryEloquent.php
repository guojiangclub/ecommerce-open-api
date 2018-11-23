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

    protected function getSumNumeric($num)
    {
        if (!is_numeric($num) || $num <= 0) {
            return 0;
        }

        return $num;
    }

    public function getSumPoint($id, $type = null)
    {
        $query = $this->model->where('user_id', $id);
        if (null != $type) {
            $query = $query->where('type', $type);
        }

        $sum = $query->sum('value');

        return $this->getSumNumeric($sum);
    }

    public function getSumPointValid($id, $type = null)
    {
        if ($type !== null) {
            $sum = $this->model->where([
                'user_id' => $id,
                'type' => $type,
            ])->valid()->sum('value');
        } else {
            $sum = $this->model->where('user_id', $id)->valid()->sum('value');
        }

        return $this->getSumNumeric($sum);
    }

    public function getSumPointOverValid($id, $type = null)
    {
        if ($type !== null) {
            $sum = $this->model->where([
                'user_id' => $id,
                'type' => $type,
            ])->overValid()->sum('value');
        } else {
            $sum = $this->model->where('user_id', $id)->overValid()->sum('value');
        }

        return $this->getSumNumeric($sum);
    }

    public function getSumPointFrozen($id, $type = null)
    {
        if ($type !== null) {
            $sum = $this->model->where([
                'user_id' => $id,
                'type' => $type,
                'status' => 0,
            ])->withinTime()->sum('value');
        } else {
            $sum = $this->model->where('user_id', $id)->where('status', 0)->withinTime()->sum('value');
        }

        return $this->getSumNumeric($sum);
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

    public function getMonthlySumByAction($userId, $action, $month = 0)
    {
        $query = $this->model->where('action', $action)->where('user_id', $userId);
        if (0 == $month) {
            $query = $query->whereMonth('created_at', date('m', time()));
        }

        $sum = $query->sum('value');

        return $this->getSumNumeric($sum);
    }

    public function getDailySumByAction($userId, $action, $day = 0)
    {
        $query = $this->model->where('action', $action)->where('user_id', $userId);
        if (0 == $day) {
            $query = $query->whereDate('created_at', date('Y-m-d', time()));
        }

        $sum = $query->sum('value');

        return $this->getSumNumeric($sum);
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
}
