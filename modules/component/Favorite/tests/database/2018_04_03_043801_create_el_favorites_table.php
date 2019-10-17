<?php

/*
 * This file is part of ibrand/favorite.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateELFavoritesTable extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		$prefix = config('ibrand.app.database.prefix', 'ibrand_');

		Schema::create($prefix . 'favorite', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->comment('用户id');
			$table->unsignedInteger('favoriteable_id')->comment('收藏的id');
			$table->string('favoriteable_type')->comment('收藏的类型(如：商品 ，故事)');
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create($prefix . 'goods', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('name');
			$table->integer('sell_price')->nullable();
			$table->string('image')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create($prefix . 'activitys', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('title');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down()
	{
		$prefix = config('ibrand.app.database.prefix', 'ibrand_');
		Schema::dropIfExists($prefix . 'favorite');
		Schema::dropIfExists($prefix . 'goods');
		Schema::dropIfExists($prefix . 'activitys');
	}
}
