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

use Carbon\Carbon;
use GuoJiangClub\Component\Discount\Contracts\DiscountContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model implements DiscountContract
{
    use SoftDeletes;

    protected $guarded = ['id', 'orderAmountLimit'];

    /**
     * Address constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'discount_coupon');

        parent::__construct($attributes);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * @return mixed
     */
    public function hasRules()
    {
        return 0 !== $this->discount->rules()->count();
    }

    public function isCouponBased()
    {
        return $this->discount->coupon_based;
    }

    public function getLabelAttribute()
    {
        return $this->discount->label;
    }

    public function getStartsAtAttribute()
    {
        return $this->getStartsAt();
    }

    public function getEndsAtAttribute()
    {
        return $this->getEndsAt();
    }

    public function getActions()
    {
        return $this->discount->getActions();
    }

    public function getRules()
    {
        return $this->discount->rules;
    }

    public function setCouponUsed()
    {
        $this->used_at = Carbon::now();
        $this->save();
    }

    public function getStartsAt()
    {
        return $this->discount->usestart_at;
    }

    public function getEndsAt()
    {
        if (empty($this->expires_at)) {
            return $this->discount->useend_at;
        }

        return $this->expires_at;
    }

    /**
     * @return mixed
     */
    public function getUsed()
    {
        // TODO: Implement getUsed() method.
    }

    /**
     * @return mixed
     */
    public function getUsageLimit()
    {
        // TODO: Implement getUsageLimit() method.
    }
}
