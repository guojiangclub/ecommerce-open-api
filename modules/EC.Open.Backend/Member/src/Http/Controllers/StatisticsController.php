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

use iBrand\EC\Open\Backend\Member\Repository\CardRepository;
use iBrand\EC\Open\Backend\Member\Repository\CouponRepository;
use iBrand\EC\Open\Backend\Member\Repository\DiscountRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected $cardRepository;
    protected $couponCodeRepository;
    protected $discountRepository;
    const ONLine = 0;  //线上优惠券类型
    const OFFLine = 1;  //线下线优惠券类型

    public function __construct(CardRepository $cardRepository,
                                CouponRepository $couponCodeRepository,
                                DiscountRepository $discountRepository
    ) {
        $this->cardRepository = $cardRepository;
        $this->couponCodeRepository = $couponCodeRepository;
        $this->discountRepository = $discountRepository;
    }

    public function index()
    {
        $time = [];
        $where = [];
        $OnCouponCount = 0;
        $OffCouponCount = 0;
        $TOffCouponCount = 0;
        $TOnCouponCount = 0;

        if (!empty(request('etime')) && !empty(request('stime'))) {
            $where['created_at'] = ['<=', request('etime')];
            $time['created_at'] = ['>=', request('stime')];
        }

        if (!empty(request('etime'))) {
            $time['created_at'] = ['<=', request('etime')];
        }

        if (!empty(request('stime'))) {
            $time['created_at'] = ['>=', request('stime')];
        }

        $discountONID = $this->discountRepository->getDiscountIDByType(self::ONLine);
        if (count($discountONID) > 0) {
            foreach ($discountONID as $item) {
                $OnCouponCount += $this->couponCodeRepository->getCouponCodeCount($item, $where, $time);
            }
        }

        $discountOffID = $this->discountRepository->getDiscountIDByType(self::OFFLine);
        if (count($discountOffID) > 0) {
            foreach ($discountOffID as $item) {
                $OffCouponCount += $this->couponCodeRepository->getCouponCodeCount($item, $where, $time);
            }
        }

        $cards = $this->cardRepository->getCardsPaginated($where, 0, $time);
        $CardCount = $cards->count();

        /*截止到昨天 total*/
        if (empty(request('etime'))) {
            $Ttime['created_at'] = ['<=', date('Y-m-d', strtotime('-1 day')).' 23:59:59'];
        } else {
            $Ttime['created_at'] = ['<=', request('etime')];
        }

        $Tcards = $this->cardRepository->getCardsPaginated([], 0, $Ttime);
        $TCardCount = $Tcards->count();

        if (count($discountONID) > 0) {
            foreach ($discountONID as $item) {
                $TOnCouponCount += $this->couponCodeRepository->getCouponCodeCount($item, [], $Ttime);
            }
        }

        if (count($discountOffID) > 0) {
            foreach ($discountOffID as $item) {
                $TOffCouponCount += $this->couponCodeRepository->getCouponCodeCount($item, [], $Ttime);
            }
        }

        //return view('member-backend::statistics.index', compact('CardCount', 'OffCouponCount', 'OnCouponCount', 'TCardCount', 'TOffCouponCount', 'TOnCouponCount'));

        return LaravelAdmin::content(function (Content $content) use ($CardCount,$OffCouponCount,$OnCouponCount,$TCardCount,$TOffCouponCount,$TOnCouponCount) {
            $content->header('数据统计');

            $content->breadcrumb(
                ['text' => '数据统计', 'url' => 'statistics/index', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::statistics.index', compact('CardCount', 'OffCouponCount', 'OnCouponCount', 'TCardCount', 'TOffCouponCount', 'TOnCouponCount')));
        });
    }
}
