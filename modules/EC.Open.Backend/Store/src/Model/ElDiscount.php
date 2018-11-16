<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ElDiscount extends Model
{
    protected $table = 'el_discount';
    protected $guarded = ['id'];
    protected $appends=['status_text'];

    public function discountRules()
    {
        return $this->hasMany('iBrand\EC\Open\Backend\Store\Model\ElDiscountRule', 'discount_id', 'id');
    }

    public function discountActions()
    {
        return $this->hasMany('iBrand\EC\Open\Backend\Store\Model\ElDiscountAction', 'discount_id', 'id');
    }

    public function getDiscountActionAttribute()
    {
        return $this->discountActions()->where('type', '<>', 'goods_times_point')->first();
        /* return $this->hasOne('iBrand\EC\Open\Backend\Store\Model\ElDiscountAction','discount_id','id');*/
    }

    public function getDiscountPointActionAttribute()
    {
        return $this->discountActions()->where('type', 'goods_times_point')->first();
        /* return $this->hasOne('iBrand\EC\Open\Backend\Store\Model\ElDiscountAction','discount_id','id');*/
    }

    public function getDiscountItemTotalAttribute()
    {
        return $this->discountRules()->where('type', 'item_total')->first();
    }

    public function getDiscountCartQuantityAttribute()
    {
        return $this->discountRules()->where('type', 'cart_quantity')->first();
    }

    public function getDiscountContainsProductAttribute()
    {
        return $this->discountRules()->where('type', 'contains_product')->first();
    }

    public function getDiscountContainsCategoryAttribute()
    {
        return $this->discountRules()->where('type', 'contains_category')->first();
    }

    public function getDiscountContainsRoleAttribute()
    {
        return $this->discountRules()->where('type', 'contains_role')->first();
    }

	public function getDiscountContainsShopsAttribute()
	{
		return $this->discountRules()->where('type', 'contains_shops')->first();
	}

	public function getDiscountContainsWechatGroupAttribute()
	{
		return $this->discountRules()->where('type', 'contains_wechat_group')->first();
	}

    public function getStatusTextAttribute()
    {
        $start = $this->starts_at;
        $end = $this->ends_at;
        $status = $this->status;

        if ($start > Carbon::now() AND $status == 1) {
            return '活动未开始';
        }

        if ($start <= Carbon::now() AND $end > Carbon::now() AND $status == 1) {
            return '活动进行中';
        }

        if ($status == 0 OR $end < Carbon::now()) {
            return '活动已结束';
        }

        return '';
    }

    /**
     * 获取优惠券总发放数
     * @return mixed
     */
    public function getCountNumAttribute()
    {
        return $this->usage_limit + $this->used;
    }

    public function coupons()
    {
        return $this->hasMany(ElDiscountCoupon::class, 'discount_id');
    }

    public function getUsedCouponCountAttribute()
    {
        return $this->coupons()->whereNotNull('used_at')->count();
    }
}
