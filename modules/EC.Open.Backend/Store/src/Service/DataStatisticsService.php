<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/9
 * Time: 11:58
 */

namespace iBrand\EC\Open\Backend\Store\Service;


use ElementVip\Distribution\Core\Models\Agent;
use ElementVip\Distribution\Core\Models\AgentOrder;
use iBrand\EC\Open\Backend\Store\Model\Order;
use iBrand\EC\Open\Backend\Store\Model\Refund;
use iBrand\EC\Open\Backend\Store\Model\User;

class DataStatisticsService
{
    protected $cache;

    public function __construct()
    {
        $this->cache = cache();
    }


    /**
     * 获取需要导出的数据
     */
    public function getExportData($page)
    {
        $time = $this->computingTime(request('stime'), request('etime'));

        $data = $this->getData($time[$page - 1]);

        $lastPage = count($time);

        // $excelData = $this->formatToExcelData($data);

        if ($page == 1) {
            session(['export_shop_data_cache' => generate_export_cache_name('export_shop_data_cache_')]);
        }
        $cacheName = session('export_shop_data_cache');

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $data), 300);
        } else {
            $this->cache->put($cacheName, $data, 300);
        }

        return ['lastPage' => $lastPage, 'cacheName' => $cacheName];
    }

    /**
     * 计算时间区间
     * @param $start
     * @param $end
     * @return array
     */
    protected function computingTime($start, $end)
    {
        $timeSection = [];

        $e_t = ' 23:59:59';
        if ($start == $end) {
            return [[$start, $end . $e_t]];
        }

        $df = ceil((strtotime($end) - strtotime($start)) / 86400);

        for ($i = 0; $i <= $df; $i++) {
            $time = date('Y-m-d', strtotime("+$i day", strtotime($start)));
            $timeSection[] = [$time, $time . $e_t];
        }
        return $timeSection;
    }


    protected function getData($time)
    {
        /*日期*/
        $date = $time[0];

        /*总订单数*/
        $orderCount = Order::whereBetween('pay_time', $time)->where('pay_status', 1)->count();

        /*交易额*/
        $orderTotal = 0;
        if ($orderCount > 0) {
            $orderTotal = Order::whereBetween('pay_time', $time)->where('pay_status', 1)->sum('total') / 100;
        }

        /*分销订单数*/
        $agentOrderObject = AgentOrder::where('level', 1)->whereHas('order', function ($query) use ($time) {
            return $query->whereBetween('pay_time', $time)->where('pay_status', 1);
        });

        $agentOrderCount = $agentOrderObject->count();

        /*分销订单交易额*/
        $agentOrderTotal = 0;
        if ($agentOrderCount > 0) {
            $orderIds = $agentOrderObject->pluck('order_id')->toArray();
            $agentOrderTotal = Order::whereIn('id', $orderIds)->sum('total') / 100;
        }

        /*新会员数*/
        $usersCount = User::whereBetween('created_at', $time)->count();

        /*累计会员数*/
        $usersTotal = User::where('created_at', '<=', $time[1])->count();

        /*新推客数*/
        $agentCount = Agent::whereBetween('created_at', $time)->where('status', 1)->count();

        /*累计推客*/
        $agentTotal = Agent::where('created_at', '<=', $time[1])->where('status', 1)->count();

        /*售后退款金额*/
        $refundAmount = Refund::whereBetween('paid_time', $time)->where('status', 3)->sum('amount') / 100;

        return [[$date, $orderCount, $orderTotal, $agentOrderCount, $agentOrderTotal, $usersCount, $usersTotal, $agentCount, $agentTotal, $refundAmount]];
    }

}