<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 14:37
 */

namespace GuoJiangClub\Component\Discount\Test\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'id', 'name', 'email', 'password',
    ];
}
