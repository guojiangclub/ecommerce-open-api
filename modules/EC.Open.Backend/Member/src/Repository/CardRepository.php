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

use iBrand\EC\Open\Backend\Member\Models\Card;
use ElementVip\Store\Backend\Facades\ExcelExportsService;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/12/14
 * Time: 21:01.
 */
class CardRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Card::class;
    }

    /**
     * 获取会员卡数量.
     *
     * @return mixed
     */
    public function getCardsCount()
    {
        return $this->all()->count();
    }

    /**
     * 获取会员卡分页数据.
     *
     * @param $where
     * @param int $limit
     *
     * @return mixed
     */
    public function getCardsPaginated($where, $limit = 20, $time = [])
    {
        $data = $this->scopeQuery(function ($query) use ($where, $time) {
            if (is_array($where) and count($where) > 0) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            if ($time and count($time) > 0) {
                foreach ($time as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            return $query->orderBy('updated_at', 'desc');
        });

        if (0 == $limit) {
            return $data->all();
        }

        return $data->paginate($limit);
    }

    public function exportExcel($name, $data, $title)
    {
        $List = [];
        if (count($data) > 0 && count($title) > 0) {
            $i = 0;
            foreach ($data as $item) {
                $List[$i][] = $item->number;
                $List[$i][] = $item->name;
                $List[$i][] = $item->mobile;
                $List[$i][] = $item->birthday;
                $List[$i][] = $item->created_at;
                $List[$i][] = $item->user_id;

                ++$i;
            }
        }

        return ExcelExportsService::createExcelExport($name, $List, $title);
    }

    /**
     * 格式化导出数据.
     *
     * @param $data
     *
     * @return array
     */
    public function formatToExcelData($data)
    {
        $list = [];
        if (count($data) > 0) {
            $i = 0;
            foreach ($data as $item) {
                $list[$i][] = $item->number;
                $list[$i][] = $item->name;
                $list[$i][] = $item->mobile;
                $list[$i][] = $item->birthday;
                $list[$i][] = $item->created_at;
                $list[$i][] = $item->user_id;
                ++$i;
            }
        }

        return $list;
    }
}
