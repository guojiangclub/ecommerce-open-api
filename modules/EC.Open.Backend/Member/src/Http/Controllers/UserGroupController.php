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

use ElementVip\Store\Backend\Model\UserGroup;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    /**会员组列表
     * @return mixed
     */
    public function grouplist()
    {
        $group = UserGroup::all();

        //return view('member-backend::user_group.grouplist', compact('group'));

        return LaravelAdmin::content(function (Content $content) use ($group) {
            $content->header('会员等级管理');

            $content->breadcrumb(
                ['text' => '会员等级管理', 'url' => 'member/groups/grouplist', 'no-pjax' => 1,'left-menu-active'=>'会员等级管理']
            );

            $content->body(view('member-backend::user_group.grouplist', compact('group')));
        });
    }

    /**会员组新增/修改
     * @param Request $request
     * @return mixed
     */
    public function groupcreate(Request $request)
    {
        if ($request->input('id')) {
            $id = $request->input('id');
            $group_edit = UserGroup::find($id);
            $action = 'edit';
        } else {
            $group_edit = new UserGroup();
            $action = '';
        }

        //$flag=settings('is_group');
        $flag = 0;
        if (1 == $flag) {
            $title = '会员积分范围';
        } elseif (0 == $flag) {
            $title = '会员消费范围';
        }

        //return view('member-backend::user_group.groupstore', compact('group_edit', 'action', 'flag', 'title'));

        return LaravelAdmin::content(function (Content $content) use ($group_edit,$action,$flag,$title) {
            $header='新增会员等级';
            if (request('id')) {
                $header='编辑会员等级';
            }

            $content->header($header);

            $content->breadcrumb(
                ['text' => '会员等级管理', 'url' => 'member/groups/grouplist', 'no-pjax' => 1],
                ['text' => $header, 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'会员等级管理']
            );


            $content->body(view('member-backend::user_group.groupstore', compact('group_edit', 'action', 'flag', 'title')));
        });
    }

    /**
     * 会员组数据库新增.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function groupstore(Request $request)
    {
        if (request('grade') or '0' == request('grade')) {
            $group_list = UserGroup::where('grade', request('grade'))->first();
            if (count($group_list) && $group_list->id != request('id')) {
                return response()->json([
                    'error' => '该等级值已存在,请修改!',
                    'status' => true,
                    'data' => [
                    ],
                    'error_code' => 1,
                ]);
            }
            $input = $request->except('id', '_token');
            if ($request->input('id')) {
                $group = UserGroup::find($request->input('id'));
                $group->fill($input);
                $group->save();
            } else {
                $group = UserGroup::create($input);
            }

            return response()->json([
                    'error' => '',
                    'status' => true,
                    'data' => [
                        'group_id' => $group->id,
                    ],
                    'error_code' => 0,
                ]);
        }

        return response()->json([
                'error' => '等级值不能为空!',
                'status' => true,
                'data' => [
                ],
                'error_code' => 1,
            ]);
    }

    /**
     * 会员组删除.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function deletedGroup($id)
    {
        $group = UserGroup::find($id);

        $group->delete();

        return $this->ajaxJson();
    }
}
