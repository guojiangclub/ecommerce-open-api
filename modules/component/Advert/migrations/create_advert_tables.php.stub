<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        if (!Schema::hasTable($prefix . 'advert')) {

            Schema::create($prefix . 'advert', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code')->unique();
                $table->string('name');
                $table->timestamps();
            });

            Schema::create($prefix . 'advert_item', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('advert_id');
                $table->string('name');
                $table->string('image');
                $table->string('link');
                $table->integer('sort')->default(99);//排序
                $table->text('meta')->nullable();//存储json数据格式，主要用来存储一些额外的信息，预留字段
                $table->timestamps();
            });

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');
        Schema::dropIfExists($prefix.'advert_item');
        Schema::dropIfExists($prefix.'advert');
    }
}
