<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Discount\Models;

use GuoJiangClub\Component\Discount\Contracts\DiscountContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model implements DiscountContract
{
    use SoftDeletes;

    protected $guarded = ['id', 'orderAmountLimit'];

    protected $appends = ['use_start_time', 'use_end_time', 'action_type'];

    /**
     * Address constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'discount');

        parent::__construct($attributes);
    }

    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    /**
     * @return mixed
     */
    public function hasRules()
    {
        return 0 !== $this->rules()->count();
    }

    public function isCouponBased()
    {
        return $this->coupon_based;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setCouponUsed()
    {
    }

    public function getUsestartAtAttribute($value)
    {
        if (empty($value)) {
            return $this->starts_at;
        }

        return $value;
    }

    public function getUseendAtAttribute($value)
    {
        if (empty($value)) {
            return $this->ends_at;
        }

        return $value;
    }

    public function getStartsAt()
    {
        return $this->starts_at;
    }

    public function getEndsAt()
    {
        return $this->ends_at;
    }

    /**
     * @return mixed
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * @return mixed
     */
    public function getUsageLimit()
    {
        return $this->usage_limit;
    }

    public function getUseStartTimeAttribute()
    {
        if (!$this->attributes['usestart_at']) {
            $time = $this->starts_at;
        } else {
            $time = $this->attributes['usestart_at'];
        }

        return date('Y-m-d', strtotime($time));
    }

    public function getUseEndTimeAttribute()
    {
        if (!$this->attributes['useend_at']) {
            $time = $this->ends_at;
        } else {
            $time = $this->attributes['useend_at'];
        }

        return date('Y-m-d', strtotime($time));
    }

    /**
     * 为方便前台展示优惠券信息.
     *
     * @return array
     */
    public function getActionTypeAttribute()
    {
        $action = $this->actions()->first();

        $type = [];

        if(str_contains($action->type,'fixed')){
            $type['type'] = 'cash';
            $type['value'] = json_decode($action->configuration, true)['amount'] / 100;
        }

        if(str_contains($action->type,'percentage')){
            $type['type'] = 'percentage';
            $type['value'] = json_decode($action->configuration, true)['percentage'] / 100;
        }

        return $type;
    }
}
