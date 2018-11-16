<?php
namespace iBrand\EC\Open\Backend\Store\Service;

use iBrand\EC\Open\Backend\Store\Model\PromotionGoodsRelation;
use iBrand\EC\Open\Backend\Store\Repositories\GrouponRepository;
use iBrand\EC\Open\Backend\Store\Repositories\SeckillRepository;


class SpecialGoodsService
{
    protected $grouponRepository;
    protected $seckillRepository;


    public function __construct(
        GrouponRepository $grouponRepository
        , SeckillRepository $seckillRepository
    )
    {
        $this->grouponRepository = $grouponRepository;
        $this->seckillRepository = $seckillRepository;
    }


    /**
     * 判断所有商品状态是否已经参与有效活动
     * @param $goods
     * @param $type : 活动类型
     * @param $id : 活动ID
     * @return mixed
     */
    public function filterGoodsStatus($goods, $type = null, $id = 0)
    {
        $goodsData = $goods['data'];
        foreach ($goodsData as $key => $value) {
            $goodsData[$key]['promotion_status'] = 0;
        }

        if ($id) {
            $relation = PromotionGoodsRelation::where('origin_type', '<>', $type)->where('origin_id', '<>', $id)->get();
        } else {
            $relation = PromotionGoodsRelation::all();
        }

        if (count($relation) > 0) {
            $goodsIds = $relation->pluck('goods_id')->toArray();
            foreach ($goodsData as $key => $value) {
                if (in_array($value['id'], $goodsIds)) {
                    $goodsData[$key]['promotion_status'] = 1;
                }
            }
        }

        $goods['data'] = $goodsData;

        return $goods;
    }


    /**
     * by eddy /20180724
     * 检测商品是否在参与有效的促销活动
     * @param $goods_id
     * @return bool
     */
    public function checkGoodsStatus($goods_id, $promotionType = null, $promotionID = 0)
    {
        if ($promotionID AND $promotionType) {
            $check = PromotionGoodsRelation::where('goods_id', $goods_id)
                ->where('origin_id', '<>', $promotionID)
                ->where('origin_type', $promotionType)
                ->first();
        } else {
            $check = PromotionGoodsRelation::where('goods_id', $goods_id)->first();
        }
        if ($check) {
            return false;
        }
        return true;
    }

}