<?php

/*
 * This file is part of ibrand/payment.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Payment\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/7
 * Time: 16:35.
 */
class Payment extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_VOID = 'void';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_UNKNOWN = 'unknown';

    protected $appends = ['channel_text', 'amount_yuan'];

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        $this->setTable($prefix.'payment');
    }

    public function getChannelTextAttribute()
    {
        $str = '';

        if ('test' == $this->channel_no) {
            $str = '测试';
        }
        switch ($this->channel) {
            case 'test':
                return '测试';
                break;
            case 'alipay_wap':
                return '支付宝'.$str;
                break;
            case 'alipay_pc_direct':
                return '支付宝'.$str;
                break;
            case 'wx_pub':
                return '微信'.$str;
                break;
            case 'wx_pub_qr':
                return '微信'.$str;
                break;

            case 'wx_lite':
                return '微信'.$str;
                break;
            case 'balance':
                return '余额';
                break;
            case 'pop_cash_pay':
                return '刷卡';
                break;
            case  'cash_pay':
                return '现金';
                break;
            case 'ali_scan_pay':
                return '支付宝';
                break;
            default:
                return '';
        }
    }

    public function getAmountYuanAttribute()
    {
        return number_format($this->amount / 100, 2, '.', '');
    }
}
