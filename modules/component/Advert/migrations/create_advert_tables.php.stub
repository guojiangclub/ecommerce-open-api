<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) 果酱社区 <https://guojiang.club>
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
                $table->string('type')->default('default');//类型
                $table->string('code')->unique();//编码
                $table->string('name');
                $table->tinyInteger('status')->default(1);//状态：1 有效 ，0 失效
                $table->string('title')->nullable();//前端显示标题
                $table->tinyInteger('is_show_title')->default(0);//前端是否显示标题
                $table->timestamps();
                $table->softDeletes();
            });


            Schema::create($prefix . 'micro_page', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('code')->unique();//编码
                $table->integer('page_type')->default(1);//1默认 2首页 3分类页面
                $table->string('link')->nullable();//推广链接
                $table->string('link_image')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

             Schema::create($prefix . 'micro_page_advert', function (Blueprint $table) {
                 $table->increments('id');
                 $table->integer('micro_page_id');
                 $table->integer('advert_id');
                 $table->integer('sort')->default(99);//排序(越小越靠前）
                 $table->timestamps();
                 $table->softDeletes();
              });

            Schema::create($prefix . 'advert_item', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('advert_id');//推广位id
                $table->tinyInteger('status')->default(1);//状态：1 有效 ，0 失效
                $table->string('name')->nullable(); //标题
                $table->string('image')->nullable();//图片url
                $table->string('link')->nullable();//推广位链接
                $table->integer('sort')->default(99);//排序(越小越靠前）
                $table->string('type')->default('default');//类型
                $table->integer('associate_id')->nullable();//关联的类型ID：如商品ID 等
                $table->string('associate_type')->nullable();
                $table->text('meta')->nullable();//存储json数据格式，主要用来存储一些额外的信息，预留字段
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
         Schema::dropIfExists($prefix.'advert_item');
         Schema::dropIfExists($prefix.'advert');
         Schema::dropIfExists($prefix.'micro_page_advert');
         Schema::dropIfExists($prefix.'micro_page');
    }
}
