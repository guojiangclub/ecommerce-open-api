<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        Schema::create($prefix . 'user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->nullable();
            $table->string('nick_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->string('password')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->string('sex')->nullable();
            $table->string('avatar')->nullable();
            $table->string('city')->nullable();
            $table->string('birthday')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create($prefix . 'user_bind', function (Blueprint $table) use ($prefix) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('type')->default('wechat');   //名称: qq, wechat, weibo,douban
            $table->string('app_id');
            $table->string('open_id')->unique();
            $table->string('nick_name')->nullable();
            $table->string('sex')->nullable();
            $table->string('email')->nullable();
            $table->string('avatar')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('language')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on($prefix . 'user')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        Schema::dropIfExists($prefix . 'user_bind');
        Schema::dropIfExists($prefix . 'user');
    }
}
