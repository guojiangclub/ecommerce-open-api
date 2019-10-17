<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Core\Console;

use DB;
use Faker\Factory;
use GuoJiangClub\Component\Address\Address;
use Illuminate\Console\Command;

class BuildAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibrand:build-address {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'build an address to user.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('user');

        $faker = Factory::create('zh_CN');

        $area = DB::table('area')->where('id', rand(1, 3144))->first();
        $city = DB::table('city')->where('cityID', $area->fatherID)->first();
        $province = DB::table('province')->where('provinceID', $city->fatherID)->first();

        Address::create(['user_id' => $userId, 'accept_name' => $faker->name, 'mobile' => $faker->phoneNumber, 'province' => $province->provinceID, 'city' => $city->cityID, 'area' => $area->areaID, 'address_name' => $province->province.' '.$city->city.' '.$area->area, 'address' => $faker->address]);

        $this->info('User\'s address created successfully.');
    }
}
