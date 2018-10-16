<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        if (!Schema::hasTable($prefix . 'favorite')) {
            Schema::create($prefix . 'favorite', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->comment('用户id');
                $table->unsignedInteger('favoriteable_id')->comment('收藏的id');
                $table->string('favoriteable_type')->comment('收藏的类型(如：商品 ，故事)');
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
        Schema::dropIfExists($prefix . 'favorite');

    }
}
