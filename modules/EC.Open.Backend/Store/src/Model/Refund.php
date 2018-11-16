<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use iBrand\EC\Open\Backend\Store\Model\Relations\BelongToUserTrait;

class  Refund extends Model implements Transformable
{
    use TransformableTrait, BelongToUserTrait;

    protected $table = 'el_refund';
    protected $guarded = ['id'];
    protected $appends = ['status_text'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function refundLog()
    {
        return $this->hasMany(RefundLog::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTypeTextAttribute()
    {
        if ($this->type == 4 OR
            ($this->order->distribution_status == 1 AND $this->type == 1 AND $this->orderItem->is_send == 0 AND $this->orderItem->status == 1)
        ) {
            return '退货退款';
        } elseif ($this->type == 1) {
            return '仅退款';
        } elseif ($this->type == 2) {
            return '换货';
        } else {
            return '其他类型';
        }

        /*switch ($this->type) {
            case 1:
                return '退货申请';
                break;

            case 2:
                return '换货申请';
                break;

            case 3:
                return '返修';
                break;
        }*/

    }

    public function getStatusTextAttribute()
    {
        switch ($this->attributes['status']) {
            case 0:
                return '待审核';
                break;

            case 1:
                return '审核通过';
                break;

            case 2:
                return '拒绝申请';
                break;

            case 3:
                return '已完成';
                break;

            case 4:
                return '已关闭';
                break;

            case 5:
                return '等待用户退货';
                break;

            case 6:
                return '用户已退货';
                break;

            case 7:
                return '等待商城发货';
                break;

            case 8:
                return '等待商家退款';
                break;

            default:
                return '待审核';
        }
    }

    public function getPicListAttribute()
    {
        if ($this->images) {
            return json_decode($this->images);
        }

        return [];
    }

    public function getReasonAttribute($value)
    {
        $reasons = settings('order_refund_reason');

        foreach ($reasons as $item) {
            if (isset($item['key']) AND isset($item['value']) AND $item['key'] == $value) {
                return $item['value'];
            }
        }

        return '';
    }

    /**
     * 后台退换货申请详情页按钮
     * @return string
     */
    public function getActionBtnTextAttribute()
    {
        $status = $this->attributes['status'];
        if ($status == 6 AND $this->logs->last()->action == 'reject') {
            return '<button type="submit" class="btn btn-primary">拒绝退款</button>';
        }

        switch ($status) {
            case 0:
                return '<button type="submit" class="btn btn-primary">提交审核</button>';
                break;

            case 6:
                return '<button type="submit" class="btn btn-primary">确认收货</button>
                <button type="button" class="btn btn-primary" id="reject">拒绝退款</button> ';
                break;

            case 7:
                return '<button type="submit" class="btn btn-primary">确认发货</button>';
                break;

            /*case 8:
                return '<button type="submit" class="btn btn-primary">确认退款</button>';
                break;*/

            case 5:
                return '<button type="submit" class="btn btn-primary">提交退货物流信息</button>';
                break;

            default:
                return '';
        }
    }

    public function getAmountAttribute($value)
    {
        return $value / 100;
    }


    public function shipping()
    {
        return $this->hasOne(RefundShipping::class);
    }

    public function refundAmount()
    {
        return $this->hasMany(RefundAmount::class);

    }

    public function logs()
    {
        return $this->hasMany(RefundLog::class);
    }

}


