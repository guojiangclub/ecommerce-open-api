<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Advert\Models;

use Illuminate\Database\Eloquent\Model;

class MicroPage extends Model
{
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
