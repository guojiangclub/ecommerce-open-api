<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Providers;

use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('amountValidate', function ($attribute, $value, $parameters, $validator) {
            $grade = $parameters[0][0];
            if (1 == $grade) {
                return true;
            }

            if ($grade > 1) {
                $memberCard = DB::table('el_member_card')->where('grade', '<', $grade)->orderBy('upgrade_amount', 'DESC')->first();
                if ($memberCard) {
                    $amount = $memberCard->upgrade_amount;

                    return $amount < $value;
                }

                return true;
            }
        }, '升级累计消费金额必须大于上一等级累计消费金额');

        return false;
    }

    public function register()
    {
    }
}
