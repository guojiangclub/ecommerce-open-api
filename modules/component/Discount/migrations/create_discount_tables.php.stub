<?php

/*
 * This file is part of ibrand/discount.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('ibrand.app.database.prefix', 'ibrand_');

        if (!Schema::hasTable($prefix . 'discount')) {
            Schema::create($prefix . 'discount', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('code')->nullable();
                $table->string('label');
                $table->string('intro')->nullable();
                $table->tinyInteger('exclusive')->default(0);
                $table->integer('usage_limit')->nullable();
                $table->integer('per_usage_limit')->default(0)->nullable();
                $table->integer('used')->default(0);
                $table->tinyInteger('coupon_based')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamp('usestart_at')->nullable();
                $table->timestamp('useend_at')->nullable();
                $table->string('tags')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'discount_coupon')) {
            Schema::create($prefix . 'discount_coupon', function (Blueprint $table) use ($prefix) {
                $table->increments('id');
                $table->unsignedInteger('discount_id');
                $table->unsignedInteger('user_id');
                $table->string('code')->nullable();
                $table->timestamp('used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->foreign('discount_id')
                    ->references('id')
                    ->on($prefix . 'discount')
                    ->onDelete('cascade');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable($prefix . 'discount_action')) {
            Schema::create($prefix . 'discount_action', function (Blueprint $table) use ($prefix) {
                $table->increments('id');
                $table->unsignedInteger('discount_id');
                $table->string('type');
                $table->text('configuration')->nullable();
                $table->foreign('discount_id')
                    ->references('id')
                    ->on($prefix . 'discount')
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable($prefix . 'discount_rule')) {
            Schema::create($prefix . 'discount_rule', function (Blueprint $table) use ($prefix) {
                $table->increments('id');
                $table->unsignedInteger('discount_id');
                $table->string('type');
                $table->mediumText('configuration')->nullable();
                $table->foreign('discount_id')
                    ->references('id')
                    ->on($prefix . 'discount')
                    ->onDelete('cascade');
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

        Schema::dropIfExists($prefix . 'discount_rule');
        Schema::dropIfExists($prefix . 'discount_action');
        Schema::dropIfExists($prefix . 'discount_coupon');
        Schema::dropIfExists($prefix . 'discount');
    }
}
