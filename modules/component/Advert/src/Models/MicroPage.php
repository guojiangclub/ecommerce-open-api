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

class MicroPage extends Model
{
    const PAGE_TYPE_DEFAULT= 1;        //默认
    const PAGE_TYPE_HOME = 2;         //首页
    const PAGE_TYPE_Category = 3;    //分类页

    protected $guarded = ['id'];

    /**
     * Address constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {

        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'micro_page');

        parent::__construct($attributes);
    }

    public function micro_page_advert(){

        return $this->hasMany(MicroPageAdvert::class,'micro_page_id');
    }

}
