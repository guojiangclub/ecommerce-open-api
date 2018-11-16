<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/6/21
 * Time: 18:51
 */

namespace iBrand\EC\Open\Backend\Store\Model;


use ElementVip\Component\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class BalanceCash extends Model
{

    protected $table = 'el_balance_cash';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAmountAttribute()
    {
        return $this->attributes['amount'] / 100;
    }

    public function setCertAttribute($value)
    {
        $this->attributes['cert'] = json_encode(explode(';', $value));
    }

    public function getCertAttribute()
    {
        return json_decode($this->attributes['cert']);
    }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 0:
                return '待审核';
                break;
            case 1:
                return '待打款';
                break;
            case 2:
                return '已打款';
                break;
            default:
                return '审核未通过';
        }
    }
}