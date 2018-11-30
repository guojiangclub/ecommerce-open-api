<?php

/*
 * This file is part of ibrand/category.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        if (!Schema::hasTable($prefix . 'category')) {
            Schema::create($prefix . 'category', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');                                              //分类的名字
                $table->tinyInteger('status')->default(1);                          //状态：1 有效 ，0 失效
                $table->unsignedInteger('sort')->default(0);                       //排序
                $table->text('description')->nullable();                          //分类描述
                $table->string('path')->nullable()->default('/');
                $table->integer('level')->default(1);
                $table->timestamps();
                $table->softDeletes();
                $table->nestedSet();
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

        Schema::dropIfExists($prefix . 'category');

    }
}
