<?php

/*
 * This file is part of ibrand/shipping.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix') ?? 'ibrand_';

        if(!Schema::hasTable($prefix . 'shipping_method')) {
            Schema::create($prefix . 'shipping_method', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code');
                $table->string('name');
                $table->string('url')->nullable();
                $table->boolean('is_enabled')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable($prefix . 'shipping')) {
            Schema::create($prefix . 'shipping', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('method_id');
                $table->integer('order_id');
                $table->string('tracking')->nullable(); //快递单号
                $table->timestamp('delivery_time')->nullable();
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
        $prefix = config('ibrand.app.database.prefix') ?? 'ibrand_';

        Schema::dropIfExists($prefix . 'shipping');
        Schema::dropIfExists($prefix . 'shipping_method');
    }
}
