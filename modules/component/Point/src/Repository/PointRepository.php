<?php

namespace iBrand\Component\Point\Repository;

interface PointRepository
{
	/**
	 * 根据用户id type 获取积分总和.
	 *
	 * @param      $id
	 * @param null $type
	 *
	 * @return mixed
	 */
	public function getSumPoint($id, $type = null);

	/**
	 * 获取用户可用积分.
	 *
	 * @param        $id
	 * @param        $status     1|0
	 * @param string $query_type valid|over_valid
	 * @param null   $type
	 *
	 * @return mixed
	 */
	public function getSumPointValid($id, $type = null);

	/**
	 * @param      $id
	 * @param null $type
	 *
	 * @return mixed
	 */
	public function getSumPointOverValid($id, $type = null);

	/**
	 * @param      $id
	 * @param null $type
	 *
	 * @return mixed
	 */
	public function getSumPointFrozen($id, $type = null);

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
	 * 根据action 获取月度积分总和，如果不传，则获取当月.
	 *
	 * @param     $userId
	 * @param     $action
	 * @param int $month
	 *
	 * @return mixed
	 */
	public function getMonthlySumByAction($userId, $action, $month = 0);

	/**
	 * 根据action 获取某天积分总和，如果不传，则获取当天.
	 *
	 * @param     $userId
	 * @param     $action
	 * @param int $day
	 *
	 * @return mixed
	 */
	public function getDailySumByAction($userId, $action, $day = 0);

    /**
     *
     * @param $itemType
     * @param $itemId
     * @return mixed
     */
    public function getPointByItem($itemType, $itemId);
}
