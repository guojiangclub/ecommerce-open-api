<?php

namespace GuoJiangClub\Component\Point\Repository;

interface PointRepository
{

    /**
     * get valid point total.
     * @param $id
     * @return mixed
     */
    public function getSumPointValid($id);

	/**
	 * 获取积分列表.
	 *
	 * @param     $id
	 * @param int $valid
	 *
	 * @return mixed
	 */
	public function getListPoint($id, $valid = 0);

    /**
     *
     * @param $itemType
     * @param $itemId
     * @return mixed
     */
    public function getPointByItem($itemType, $itemId);

	public function getPointsByConditions($where, $limit = 20);
	
	public function distributePercentage($order);
	
}
