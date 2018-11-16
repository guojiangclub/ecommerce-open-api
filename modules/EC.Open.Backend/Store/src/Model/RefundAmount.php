<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/11/15
 * Time: 16:29
 */

namespace iBrand\EC\Open\Backend\Store\Model;


use Illuminate\Database\Eloquent\Model;

class RefundAmount extends Model
{
    protected $table = 'el_refund_amount';
    protected $guarded = ['id'];

    /*public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 100;
    }*/

    public function getTypeTextAttribute()
    {
        $text = '';
        switch ($this->attributes['type']) {
            case 'balance':
                return '系统自动退款到用户余额账户';
                break;
            case 'cash':
                return '现金';
                break;
        }
        return $text;
    }
}