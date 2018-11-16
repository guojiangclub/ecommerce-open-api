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

use ElementVip\Component\User\Models\ElGroup;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = ElGroup::paginate(15);

        //return view('member-backend::group.index', compact('groups'));

        return LaravelAdmin::content(function (Content $content) use ($groups) {
            $content->header('会员分组管理');

            $content->breadcrumb(
                ['text' => '会员分组管理', 'url' => 'member/group', 'no-pjax' => 1,'left-menu-active'=>'会员分组管理']
            );

            $content->body(view('member-backend::group.index', compact('groups')));
        });
    }

    public function create()
    {
        //return view('member-backend::group.create');

        return LaravelAdmin::content(function (Content $content) {
            $content->header('新增会员分组');

            $content->breadcrumb(
                ['text' => '会员分组管理', 'url' => 'member/group', 'no-pjax' => 1],
                ['text' => '新增会员分组', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'会员分组管理']
            );

            $content->body(view('member-backend::group.create'));
        });
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $description = $request->input('description');
        if (!$name) {
            return $this->ajaxJson(false, [], 404, '分组名称不能为空');
        }

        if ($id = $request->input('id')) {
            if (ElGroup::where('name', $name)->where('id', '<>', $id)->first()) {
                return $this->ajaxJson(false, [], 404, '该分组名称已存在');
            }

            $group = ElGroup::find($id);
            $group->name = $name;
            $group->description = $description;
            $group->save();
        } else {
            if (ElGroup::where('name', $name)->first()) {
                return $this->ajaxJson(false, [], 404, '该分组名称已存在');
            }

            ElGroup::create(['name' => $name, 'description' => $description]);
        }

        return $this->ajaxJson();
    }

    public function edit($id)
    {
        $group = ElGroup::find($id);

        //return view('member-backend::group.edit', compact('group'));

        return LaravelAdmin::content(function (Content $content) use ($group) {
            $content->header('修改会员分组');

            $content->breadcrumb(
                ['text' => '会员分组管理', 'url' => 'member/group', 'no-pjax' => 1],
                ['text' => '修改会员分组', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'会员分组管理']
            );

            $content->body(view('member-backend::group.edit', compact('group')));
        });
    }

    public function delete($id)
    {
        $group = ElGroup::find($id);
        if ($group->users->count() > 0) {
            return $this->ajaxJson(false, [], 404, '该分组下存在会员，不可删除');
        }
        $group->delete();

        return $this->ajaxJson();
    }
}
