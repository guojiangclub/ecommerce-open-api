<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Http\Controllers;

use DB;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class DataController extends Controller
{
    public function index()
    {
        //用户属性
        $user_all_count = DB::table('el_user')->where('status', 1)->count();

        $user_man_count = DB::table('el_user')->where('status', 1)->where('sex', '男')->count();

        $user_woman_count = DB::table('el_user')->where('status', 1)->where('sex', '女')->count();

        $user_un_known_count = $user_all_count - $user_man_count - $user_woman_count;

        $user_buy_count = DB::table('el_order')->whereIn('el_order.status', [4, 5])
            ->leftJoin('el_user', 'el_user.id', '=', 'el_order.user_id')
            ->where('el_user.status', 1)
            ->groupBy('user_id')->get(['user_id'])->count();
        $user_no_buy_count = $user_all_count - $user_buy_count;

        //用户会员等级
        $group = DB::table('el_user_group')->pluck('name');
        $user_group = [];
        if ($group and count($group)) {
            $user_group_data = DB::table('el_user_group')
                ->leftJoin('el_user', 'el_user.group_id', '=', 'el_user_group.id')
                ->where('el_user.status', 1)
                ->get(['el_user.id', 'el_user.group_id'])
                ->groupBy('group_id');
            if (count($user_group_data)) {
                $i = 0;
                foreach ($user_group_data as $item) {
                    $user_group[$group[$i]] = $item->count();
                    ++$i;
                }
            }
        }

        $wechat_app_id = settings('wechat_app_id');
        $user_bind = [];
        $user_bind_array = [];
        $user_bind_value = [];
        $user_bind_data = DB::table('el_user_bind')
            ->leftJoin('el_user', 'el_user.id', '=', 'el_user_bind.user_id')
            ->where('el_user.status', 1)
            ->where('el_user_bind.type', 'wx')
            ->where('el_user_bind.app_id', $wechat_app_id)
            ->get(['el_user.id', 'el_user_bind.province'])
            ->groupBy('province');

        if (count($user_bind_data)) {
            $j = 0;
            foreach ($user_bind_data as $key => $item) {
                if ($key) {
                    $user_bind[$key] = $item->count();
                    $user_bind_array[$j]['name'] = $key;
                    $user_bind_array[$j]['value'] = $item->count();
                    ++$j;
                }
            }
//            $user_bind_value= collect($user_bind_array)->sortByDesc('value');
//            $user_bind_value->values()->all();
        }

        return LaravelAdmin::content(function (Content $content) use ($user_all_count,$user_man_count,
            $user_woman_count,$user_un_known_count
            ,$user_buy_count,$user_no_buy_count
            ,$group,$user_group,$user_bind
            ,$user_bind_value
        ) {
            $content->header('会员概览');

            $content->breadcrumb(
                ['text' => '会员概览', 'url' => 'member/data', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::data.index', compact(
                'user_all_count', 'user_man_count', 'user_woman_count', 'user_un_known_count', 'user_buy_count', 'user_no_buy_count', 'group', 'user_group', 'user_bind', 'user_bind_value'
            )));
        });
    }
}
