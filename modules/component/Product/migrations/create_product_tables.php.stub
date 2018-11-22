<?php

/*
 * This file is part of ibrand/product.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        //品牌表
        if (!Schema::hasTable($prefix . 'goods_brand')) {
            Schema::create($prefix . 'goods_brand', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');                             //品牌名称
                $table->string('description')->nullable();         //描述
                $table->integer('is_show')->default(1);             //是否可见，1可见，0不可见
                $table->integer('sort')->default(99);               //排序(越小越靠前)
                $table->string('url')->nullable();                  //品牌网址
                $table->string('logo')->nullable();                 //品牌logo
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'goods_spec')) {
            Schema::create($prefix . 'goods_spec', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('display_name');
                $table->tinyInteger('type')->default(1);
                $table->timestamps();
                $table->softDeletes();
            });
        }
        if (!Schema::hasTable($prefix . 'goods_spec_value')) {
            Schema::create($prefix . 'goods_spec_value', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('spec_id');
                $table->string('name');
                $table->string('rgb')->nullable();
                /*$table->string('color')->nullable();
                $table->tinyInteger('status')->default(1);*/
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'goods_attribute')) {
            Schema::create($prefix . 'goods_attribute', function (Blueprint $table) {
                $table->increments('id');
                $table->tinyInteger('type')->default(1); //1 下拉列表 2 输入框
                $table->string('name')->nullable();
                $table->tinyInteger('is_search')->default(0);
                $table->tinyInteger('is_chart')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
        if (!Schema::hasTable($prefix . 'goods_attribute_value')) {
            Schema::create($prefix . 'goods_attribute_value', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('attribute_id');
                $table->string('name');
                $table->timestamps();
                $table->softDeletes();
            });
        }
        if (!Schema::hasTable($prefix . 'goods_model')) {
            Schema::create($prefix . 'goods_model', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('spec_ids');
                $table->timestamps();
                $table->softDeletes();
            });
        }
        if (!Schema::hasTable($prefix . 'goods_model_attribute_relation')) {
            Schema::create($prefix . 'goods_model_attribute_relation', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('model_id');
                $table->unsignedInteger('attribute_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'goods')) {
            Schema::create($prefix . 'goods', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');     //商品名称
                $table->string('goods_no');     //商品货号
                $table->unsignedInteger('brand_id');    //品牌ID
                $table->unsignedInteger('model_id');    //模型ID

                //价格类数据
                $table->decimal('min_price', 15, 2); //销售价格
                $table->decimal('max_price', 15, 2); //销售价格
                $table->decimal('sell_price', 15, 2); //销售价格
                $table->decimal('market_price', 15, 2)->nullable();   // 市场价
                $table->decimal('min_market_price', 15, 2)->nullable();   // 市场价
                $table->decimal('cost_price', 15, 2)->nullable(); //成本价

                $table->decimal('weight', 15, 2)->nullable(); //重量
                $table->integer('store_nums')->default(0);  //库存
                $table->string('img')->nullable();  //封面图


                $table->mediumText('content')->nullable();    //商品描述(mobile)
                $table->mediumText('contentpc')->nullable();    //商品描述(pc)
                $table->tinyInteger('sync')->default(0); //内容是否同步 0：不同步 1：同步至PC端  2：PC同步到移动端

                //统计类数据
                $table->integer('comment_count')->default(0);    //评论次数
                $table->integer('visit_count')->default(0);   //浏览次数
                $table->integer('favorite_count')->default(0);    //收藏次数
                $table->integer('sale_count')->default(0);    //销量
                $table->integer('grade')->default(0);   //评分总数

                $table->string('tags')->nullable(); //标签

                $table->string('keywords')->nullable(); //SEO关键词
                $table->string('description')->nullable(); //SEO描述

                $table->tinyInteger('is_del')->default(0); //上架或者下架
                $table->tinyInteger('is_largess')->default(0);  //是否赠品：0否 1是
                $table->tinyInteger('is_commend')->default(0);  //是否推荐
                $table->tinyInteger('is_new')->default(0);  // 是否新品

                $table->text('extra')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'goods_product')) {
            Schema::create($prefix . 'goods_product', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('goods_id');    //商品ID
                $table->integer('store_nums')->default(0);  //库存
                $table->string('sku')->nullable();    //sku
                $table->decimal('sell_price', 15, 2); //销售价格
                $table->decimal('market_price', 15, 2)->nullable();   // 市场价
                $table->decimal('cost_price', 15, 2)->nullable(); //成本价
                $table->decimal('weight', 15, 2)->nullable(); //重量
                $table->tinyInteger('is_show')->default(1);  // 是否上架销售
                $table->text('spec_ids')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'goods_category')) {
            Schema::create($prefix . 'goods_category', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('goods_id');
                $table->unsignedInteger('category_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable($prefix . 'goods_attribute_relation')) {
            Schema::create($prefix . 'goods_attribute_relation', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('goods_id');
                $table->unsignedInteger('model_id');
                $table->unsignedInteger('attribute_id');
                $table->unsignedInteger('attribute_value_id');
                $table->string('attribute_value')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
        if (!Schema::hasTable($prefix . 'goods_spec_relation')) {
            Schema::create($prefix . 'goods_spec_relation', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('goods_id');
                $table->unsignedInteger('spec_id');
                $table->unsignedInteger('spec_value_id');
                $table->integer('sort')->default(99);
                $table->string('alias')->nullable();
                $table->string('img')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'goods_photo')) {
            Schema::create($prefix . 'goods_photo', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('goods_id');
                $table->string('url');
                $table->integer('sort')->default(0);
                $table->string('code')->nullable();
                $table->tinyInteger('is_default')->default(1);  // 是否主图
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists($prefix . 'goods_photo');
        Schema::dropIfExists($prefix . 'goods_spec_relation');
        Schema::dropIfExists($prefix . 'goods_attribute_relation');
        Schema::dropIfExists($prefix . 'goods_category');
        Schema::dropIfExists($prefix . 'goods_product');
        Schema::dropIfExists($prefix . 'goods');
        Schema::dropIfExists($prefix . 'goods_model_attribute_relation');
        Schema::dropIfExists($prefix . 'goods_model');
        Schema::dropIfExists($prefix . 'goods_attribute_value');
        Schema::dropIfExists($prefix . 'goods_attribute');
        Schema::dropIfExists($prefix . 'goods_specs_value');
        Schema::dropIfExists($prefix . 'goods_spec');
        Schema::dropIfExists($prefix . 'goods_brand');
    }
}
