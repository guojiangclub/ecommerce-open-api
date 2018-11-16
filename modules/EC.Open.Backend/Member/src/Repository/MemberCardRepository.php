<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Repository;

use iBrand\EC\Open\Backend\Member\Models\MemberCard;
use Prettus\Repository\Eloquent\BaseRepository;

class MemberCardRepository extends BaseRepository
{
    public function model()
    {
        return MemberCard::class;
    }

    /**
     * 获取会员卡数据.
     *
     * @param     $where
     * @param int $limit
     *
     * @return mixed
     */
    public function getCardsPaginated($where, $limit = 20, $time = [])
    {
        $data = $this->scopeQuery(function ($query) use ($where, $time) {
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

            if ($time) {
                foreach ($time as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            return $query->orderBy('grade', 'DESC');
        });

        if (0 == $limit) {
            return $data->all();
        }

        return $data->paginate($limit);
    }

    /**
     * 查询等级一会员卡
     *
     * @return array
     */
    public function getLowestGrade()
    {
        $data = [];

        $res = $this->model->first();
        if ($res) {
            $data = $res->toArray();
        }

        return $data;
    }
}
