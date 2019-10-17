<?php

/*
 * This file is part of ibrand/category.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElCategoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        Schema::create($prefix . 'category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');                                              //分类的名字
            $table->tinyInteger('status')->default(1);                          //状态：1 有效 ，0 失效
            $table->unsignedInteger('sort')->default(0);                       //排序
            $table->text('description')->nullable();                          //分类描述
            $table->timestamps();
            $table->softDeletes();
            $table->nestedSet();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');
        Schema::dropIfExists($prefix . 'category');
    }
}
