<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Seeds;

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
            'uri' => 'member/data',
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
            'title' => '会员分组管理',
            'icon' => 'iconfont icon-huiyuandengjiguanli',
            'blank' => 1,
            'uri' => 'member/group',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '会员卡',
            'icon' => 'iconfont icon-dingdanguanli',
            'blank' => 1,
            'uri' => 'member/card',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '会员等级管理',
            'icon' => 'iconfont icon-huiyuandengjiguanli',
            'blank' => 1,
            'uri' => 'member/groups/grouplist',
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

        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '会员余额记录',
            'icon' => 'iconfont icon-huiyuanjifenjilu',
            'blank' => 1,
            'uri' => 'member/balances',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '员工管理',
            'icon' => 'iconfont icon-yuangongguanli',
            'blank' => 1,
            'uri' => 'member/staff',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '角色管理',
            'icon' => 'iconfont icon-jiaoseguanli',
            'blank' => 1,
            'uri' => 'member/RoleManagement/role/index',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
//        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
//            'parent_id' => $parent,
//            'order' => $lastOrder++,
//            'title' => '消息管理',
//            'icon' => 'iconfont icon-shijianyingxiaoguanli',
//            'blank' => 1,
//            'uri' => 'member/message',
//            'created_at' => date('Y-m-d H:i:s', time()),
//            'updated_at' => date('Y-m-d H:i:s', time()),
//        ]);

        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '储值管理',
            'icon' => 'iconfont icon-zhifushezhi',
            'blank' => 1,
            'uri' => 'member/recharge',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);


        $second = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '会员卡管理',
            'icon' => 'iconfont icon-huiyuanqiaguanli',
            'blank' => 1,
            'uri' => 'member/vip/card',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

    }
}
