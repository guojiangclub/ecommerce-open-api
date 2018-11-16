<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesCategoryTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');
        if (!Schema::hasTable($prefix . 'images_category')) {
            Schema::create($prefix . 'images_category', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('parent_id');
                $table->string('name'); //分类的名字
                $table->text('description')->nullable(); //分类描述
                $table->integer('sort')->default(99); //分类排序
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
        Schema::drop($prefix . 'images_category');
    }

}
