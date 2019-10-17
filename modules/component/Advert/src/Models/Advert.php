<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Advert\Models;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{

    const STATUS_OPEN = 1; // 可用状态

    const STATUS_CLOSE = 0;  // 关闭状态

    protected $guarded = ['id'];

    /**
     * Address constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {

        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'advert');

        parent::__construct($attributes);
    }

    public function addAdvertItem(array $attributes = []){

        $attributes['advert_id'] = $this->id;

        return AdvertItem::create($attributes);

    }

    public function item(){

        return $this->hasMany(AdvertItem::class,'advert_id');
    }


}
