<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Http\Controllers;

use Carbon\Carbon;
use DB;
use ElementVip\Component\Recharge\Models\RechargeRule;
use ElementVip\Component\Recharge\Repositories\BalanceOrderRepository;
use ElementVip\Component\Recharge\Repositories\GiftCouponRepository;
use ElementVip\Component\Recharge\Repositories\RechargeRuleRepository;
use iBrand\EC\Open\Backend\Member\Repository\DiscountRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class RechargeController extends Controller
{
    protected $discountRepository;

    protected $rechargeRuleRepository;

    protected $giftCouponRepository;

    protected $balanceOrderRepository;

    public function __construct(
        DiscountRepository $discountRepository,
        RechargeRuleRepository $rechargeRuleRepository,
        GiftCouponRepository $giftCouponRepository,
        BalanceOrderRepository $balanceOrderRepository
    ) {
        $this->discountRepository = $discountRepository;
        $this->rechargeRuleRepository = $rechargeRuleRepository;
        $this->giftCouponRepository = $giftCouponRepository;
        $this->balanceOrderRepository = $balanceOrderRepository;
    }

    public function index()
    {
        $condition = $this->getCondition();
        $coupons = [];
        $where = $condition[0];
        $orWhere = $condition[1];
        $where['status'] = 1;
        $where['starts_at'] = ['<=', Carbon::now()];
        $where['ends_at'] = ['>', Carbon::now()];
        $coupons = $this->discountRepository->getDiscountList($where, $orWhere);
        if (count($coupons) > 0) {
            $coupons = $coupons->pluck('id')->toArray();
        }

        $name = request('name');

        $lists = $this->rechargeRuleRepository->getAll($name);

        //return view('member-backend::recharge.index', compact('lists', 'coupons'));

        return LaravelAdmin::content(function (Content $content) use ($lists,$coupons) {
            $content->header('储值管理');

            $content->breadcrumb(
                ['text' => '储值管理', 'url' => 'member/recharge', 'no-pjax' => 1,'left-menu-active'=>'储值管理']
            );

            $content->body(view('member-backend::recharge.index', compact('lists', 'coupons')));
        });
    }

    public function create()
    {
        //return view('member-backend::recharge.create');

        return LaravelAdmin::content(function (Content $content) {
            $content->header('储值管理');

            $content->breadcrumb(
                ['text' => '储值管理', 'url' => 'member/recharge', 'no-pjax' => 1],
                ['text' => '创建储值规则', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'储值管理']
            );

            $content->body(view('member-backend::recharge.create'));
        });
    }

    public function edit($id)
    {
        $recharge = $this->rechargeRuleRepository->getRechargeByIDStatusOff($id);
        $coupon = [];
        if (count($recharge->gift) > 0) {
            foreach ($recharge->gift as $k => $item) {
                $coupon[$k] = $item->coupon->id;
            }
        }
        $coupon = json_encode($coupon, true);

        //return view('member-backend::recharge.edit', compact('recharge', 'coupon'));

        return LaravelAdmin::content(function (Content $content) use ($recharge,$coupon) {
            $content->header('储值管理');

            $content->breadcrumb(
                ['text' => '储值管理', 'url' => 'member/recharge', 'no-pjax' => 1],
                ['text' => '编辑储值规则', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'储值管理']
            );

            $content->body(view('member-backend::recharge.edit', compact('recharge', 'coupon')));
        });
    }

    public function update($id)
    {
        $coupon = [];
        $input = request()->except(['_token']);
        $input['amount'] = ceil(100 * ($input['amount']));
        $input['payment_amount'] = ceil(100 * $input['payment_amount']);
        unset($input['coupon_title']);
        $input['point'] = !empty($input['point']) ? $input['point'] : 0;
        try {
            DB::beginTransaction();
            $coupon_id = $input['coupon'];
            unset($input['coupon']);
            $res = $this->rechargeRuleRepository->update($input, $id);
            if ($input['open_coupon']) {
                $gift = $this->giftCouponRepository->findWhere(['type' => 'gift_recharge', 'type_id' => $id])->first();
                $coupon['type'] = $input['type'];
                $coupon['type_id'] = $res->id;
                $coupon['coupon_id'] = intval($coupon_id);
                $coupon['num'] = 1;
                $coupon['status'] = 1;
                if (isset($gift->id)) {
                    $this->giftCouponRepository->update($coupon, $gift->id);
                } else {
                    $this->giftCouponRepository->create($coupon);
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception->getMessage().$exception->getTraceAsString());

            return $this->ajaxJson(false, [], 400, '');
        }

        return $this->ajaxJson(true, [], 200, '');
    }

    public function store()
    {
        $coupon = [];
        $input = request()->except(['_token']);
        $input['amount'] = ceil(100 * ($input['amount']));
        $input['payment_amount'] = ceil(100 * $input['payment_amount']);
        unset($input['coupon_title']);
        $input['point'] = !empty($input['point']) ? $input['point'] : 0;
        try {
            DB::beginTransaction();
            $coupon_id = $input['coupon'];
            unset($input['coupon']);
            $res = $this->rechargeRuleRepository->create($input);
            if ($input['open_coupon']) {
                $coupon['type'] = $input['type'];
                $coupon['type_id'] = $res->id;
                $coupon['coupon_id'] = intval($coupon_id);
                $coupon['num'] = 1;
                $coupon['status'] = 1;
                $this->giftCouponRepository->create($coupon);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception->getMessage().$exception->getTraceAsString());

            return $this->ajaxJson(false, [], 400, '');
        }

        return $this->ajaxJson(true, [], 200, '');
    }

    public function coupon_api()
    {
        $condition = $this->getCondition();
        $where = $condition[0];
        $orWhere = $condition[1];
        $coupons = $this->discountRepository->getDiscountList($where, $orWhere);

        return $this->ajaxJson(true, $coupons, 200, '');
    }

    /**
     * 获取筛选条件.
     *
     * @return array
     */
    private function getCondition()
    {
        $where['coupon_based'] = 1;
        $orWhere = [];
        $status = request('status');
        if ('nstart' == $status) {
            $where['status'] = 1;
            $where['starts_at'] = ['>', Carbon::now()];
        }

        if ('ing' == $status) {
            $where['status'] = 1;
            $where['starts_at'] = ['<=', Carbon::now()];
            $where['ends_at'] = ['>', Carbon::now()];
        }

        if ('end' == $status) {
            $where['ends_at'] = ['<', Carbon::now()];

            $orWhere['coupon_based'] = 1;
            $orWhere['status'] = 0;
        }

        if ('' != request('title')) {
            $where['title'] = ['like', '%'.request('title').'%'];
        }

        $where['type'] = ['<>', 1];

        return [$where, $orWhere];
    }

    public function destroy($id)
    {
        $this->rechargeRuleRepository->delete($id);

        return $this->ajaxJson();
    }

    public function toggleStatus()
    {
        $status = request('status');
        $id = request('aid');
        $item = $this->rechargeRuleRepository->findWhere(['id' => $id])->first();
        if ($item) {
            $user = RechargeRule::find($id);
            $user->status = $status;
            $user->save();

            return $this->ajaxJson(true, [], 200, '');
        }

        return $this->ajaxJson(false, [], 400, '操作失败');
    }

    public function log()
    {
        $where = [];
        $id = 0;
        $where['pay_status'] = 1;
        $condition = $this->getListWhere();
        $where = $condition[1];
        $time = $condition[0];
        $lists = $this->balanceOrderRepository->getLists($where, 20, $time);

        if (isset($where['recharge_rule_id'])) {
            $id = $where['recharge_rule_id'];
        }
//        $data=$lists=$this->balanceOrderRepository->getLists($where,0,$time);
        //return view('member-backend::recharge.log', compact('lists', 'id'));

        return LaravelAdmin::content(function (Content $content) use ($lists,$id) {
            $content->header('储值管理');

            $content->breadcrumb(
                ['text' => '储值管理', 'url' => 'member/recharge', 'no-pjax' => 1],
                ['text' => '储值记录', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'储值管理']
            );

            $content->body(view('member-backend::recharge.log', compact('lists', 'id')));
        });
    }

    private function getListWhere()
    {
        $time = [];
        $where = [];

        if ($id = request('id')) {
            $where['recharge_rule_id'] = $id;
        }

        if ($order_no = request('order_no')) {
            $where['order_no'] = ['like', '%'.request('order_no').'%'];
        }

        if (!empty(request('mobile'))) {
            $where['mobile'] = ['like', '%'.request('mobile').'%'];
        }

        if (!empty(request('etime')) && !empty(request('stime'))) {
            $where['pay_time'] = ['<=', request('etime')];
            $time['pay_time'] = ['>=', request('stime')];
        }

        if (!empty(request('etime'))) {
            $time['pay_time'] = ['<=', request('etime')];
        }

        if (!empty(request('stime'))) {
            $time['pay_time'] = ['>=', request('stime')];
        }

        return [$time, $where];
    }
}
