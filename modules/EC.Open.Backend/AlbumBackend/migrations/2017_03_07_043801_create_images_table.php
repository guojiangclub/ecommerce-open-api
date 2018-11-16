<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        if (!Schema::hasTable($prefix . 'images')) {

            Schema::create($prefix . 'images', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('url');    //本地url
                $table->string('remote_url')->nullable();    //远程URL
                $table->integer('category_id'); //分类ID
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
        Schema::drop($prefix . 'images');
    }

}
