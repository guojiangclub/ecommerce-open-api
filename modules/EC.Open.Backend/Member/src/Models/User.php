<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Models;

use ElementVip\Component\Order\Models\Order;
use ElementVip\Component\User\Models\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends \ElementVip\Component\User\Models\User
{
    use SoftDeletes;
    // use EntrustUserTrait;

    protected $table = 'el_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'name', 'email', 'password',
    ];*/

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }

    public function getActionButtonsAttribute()
    {
        return $this->getEditButtonAttribute().
        $this->getChangePasswordButtonAttribute().' '.
        $this->getStatusButtonAttribute().
//        $this->getConfirmedButtonAttribute() .
        $this->getDeleteButtonAttribute();
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
//        if (Entrust::hasRole(['super_admin','admin']))
        return '<a href="'.route('admin.users.edit', ['id' => $this->id, 'redirect_url' => urlencode(\Request::getRequestUri())]).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="'.'编辑'.'"></i></a> ';
//        return '';
    }

    /**
     * @return string
     */
    public function getChangePasswordButtonAttribute()
    {
//        if (Entrust::hasRole(['super_admin', 'admin']))
        return '<a href="'.route('admin.user.change-password', $this->id).'" class="btn btn-xs btn-info"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="'.'设置密码'.'"></i></a>';
//        return '';
    }

    /**
     * @return string
     */
    public function getStatusButtonAttribute()
    {
//        if (!Entrust::hasRole(['super_admin', 'admin']))
//            return '';

        switch ($this->status) {
            case 0:
                return '<a href="'.route('admin.user.mark', [$this->id, 1]).'" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="'.'启用'.'"></i></a> ';
                break;

            case 1:
                $buttons = '';
                $buttons .= '<a href="'.route('admin.user.mark', [$this->id, 2]).'" class="btn btn-xs btn-danger"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="'.'禁用'.'"></i></a> ';

                return $buttons;
                break;
            case 2:
                return '<a href="'.route('admin.user.mark', [$this->id, 1]).'" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="'.'激活'.'"></i></a> ';
                break;
            default:
                return '';
                break;
        }
    }

    public function getConfirmedButtonAttribute()
    {
        if (!$this->confirmed) {
//            if (Entrust::hasRole(['super_admin', 'admin']))
            return '<a href="'.route('admin.account.confirm.resend', $this->id).'" class="btn btn-xs btn-success"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="重新发送激活邮件"></i></a> ';
        }
//        return '';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
//        if (!$this->hasRole(['super_admin', 'admin'])&&empty($this->deleted_at))
//        if (empty($this->deleted_at))
//            return '<a href="' . route('admin.users.destroy', $this->id) . '" data-method="delete" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="删除用户"></i></a>';
        return '';
    }

    public function group()
    {
        return $this->belongsTo('iBrand\EC\Open\Backend\Member\Models\UserGroup', 'group_id');
    }

    public function bind()
    {
        return $this->hasOne('iBrand\EC\Open\Backend\Member\Models\UserBind', 'user_id', 'id');
    }

    public function size()
    {
        return $this->hasOne('iBrand\EC\Open\Backend\Member\Models\UserSize', 'user_id', 'id');
    }

    public function card()
    {
        return $this->hasOne('ElementVip\Component\Card\Models\Card', 'user_id', 'id');
    }

    public function hasManyOrders()
    {
        return $this->hasMany(Order::class);
    }
}
