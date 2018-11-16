<?php
/**
 * Created by PhpStorm.
 * User: eddy
 * Date: 2017/5/20
 * Time: 22:41
 */

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use iBrand\Backend\Http\Controllers\Controller;
use iBrand\EC\Open\Backend\Store\Model\BalanceCash;

class BalanceController extends Controller
{
    public function index()
    {

        $status = request('status');
        if (!$status OR $status == 'STATUS_AUDIT') {
            $status = 0;
        } elseif ($status == 'STATUS_WAIT_PAY') {
            $status = 1;
        } elseif ($status == 'STATUS_PAY') {
            $status = 2;
        } else {
            $status = 3;
        }
        $cash = BalanceCash::where('status', $status)->orderBy('updated_at','desc')->paginate(20);

        return view('store-backend::balance_cash.index', compact('cash'));
    }

    public function show($id)
    {
        $cash = BalanceCash::find($id);

        return view('store-backend::balance_cash.show', compact('cash'));
    }


    public function review()
    {
        $cash = BalanceCash::find(request('id'));
        $cash->status = request('status');
        $cash->save();

        //TODO::The operation of money

        //TODO::Restore balance

        return $this->ajaxJson();
    }


    public function operatePay($id)
    {
        $cash = BalanceCash::find($id);
        return view('store-backend::balance_cash.review', compact('cash'));
    }

    public function applyPay()
    {
        if (!$status = request('status')) {
            return $this->ajaxJson(false, [], 404, '请先确认已打款');
        }

        if (!$settle_time = request('settle_time')) {
            return $this->ajaxJson(false, [], 404, '请输入打款时间');
        }

        $cash = BalanceCash::find(request('id'));
        $cash->status = $status;
        $cash->settle_time = $settle_time;
        $cash->cert = request('cert');
        $cash->save();
        return $this->ajaxJson();

    }
}