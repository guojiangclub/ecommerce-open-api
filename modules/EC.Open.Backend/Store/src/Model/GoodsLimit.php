<?php

namespace iBrand\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use iBrand\Component\Product\Models\Goods;
use iBrand\Component\Product\Models\Product as GoodsProduct;

class GoodsLimit extends Model
{
	protected $table = 'el_goods_limit';

	protected $guarded = ['id'];

	public function goods()
	{
		return $this->belongsTo(Goods::class);
	}

	public function product()
	{
		return $this->hasMany(GoodsProduct::class, 'goods_id', 'goods_id');
	}
}