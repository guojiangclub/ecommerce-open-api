<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class UserGroup extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'el_user_group';
    protected $guarded =['id'];

}
