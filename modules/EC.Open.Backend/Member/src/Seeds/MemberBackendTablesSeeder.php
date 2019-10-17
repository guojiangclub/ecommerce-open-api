<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Backend\Member\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberBackendTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $lastOrder = DB::table(config('admin.database.menu_table'))->max('order');

        $parent = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => 0,
            'order' => $lastOrder++,
            'title' => '会员管理',
            'icon' => 'iconfont icon-huiyuanguanli-',
            'blank' => 1,
            'uri' => 'member/users',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '会员管理',
            'icon' => 'iconfont icon-huiyuanguanli--',
            'blank' => 1,
            'uri' => 'member/users',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);



        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '会员积分记录',
            'icon' => 'iconfont icon-huiyuanjifenjilu',
            'blank' => 1,
            'uri' => 'member/points',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        
    }
}
