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

use ElementVip\Component\User\Models\Permission;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Validator;

class PermissionController extends Controller
{
    protected $permission;

    /**
     * RoleController constructor.
     *
     * @param $Permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function index()
    {
        $models = $this->permission->paginate(15);

        //return view('member-backend::role.permissionIndex', compact('models'));

        return LaravelAdmin::content(function (Content $content) use ($models) {
            $content->header('权限管理');

            $content->breadcrumb(
                ['text' => '权限管理', 'url' => 'member/RoleManagement/permissions/index', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::role.permissionIndex', compact('models')));
        });
    }

    public function create()
    {
        //return view('member-backend::role.permissionCreate');

        return LaravelAdmin::content(function (Content $content) {
            $content->header('权限管理');

            $content->breadcrumb(
                ['text' => '权限管理', 'url' => 'member/RoleManagement/permissions/index', 'no-pjax' => 1],
                ['text' => '创建权限', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::role.permissionCreate'));
        });
    }

    public function store()
    {
        $status = $this->validatePrivate();
        if (is_array($status)) {
            return response()->json($status);
        }

        $this->permission->create(request()->only(['name', 'display_name', 'description']));

        return response()->json([
            'status' => true,
        ]);
    }

    public function edit($id)
    {
        $model = $this->permission->find($id);

        //return view('member-backend::role.permissionEdit', compact('model'));

        return LaravelAdmin::content(function (Content $content) use ($model) {
            $content->header('权限管理');

            $content->breadcrumb(
                ['text' => '权限管理', 'url' => 'member/RoleManagement/permissions/index', 'no-pjax' => 1],
                ['text' => '编辑权限', 'url' => '', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::role.permissionEdit', compact('model')));
        });
    }

    public function update($id)
    {
        $status = $this->validatePrivate('update');
        if (is_array($status)) {
            return response()->json($status);
        }
        $this->permission->findOrFail($id)->update(request()->only(['name', 'display_name', 'description']));

        return response()->json([
            'status' => true,
        ]);
    }

    public function delete($id)
    {
        $model = $this->permission->findOrFail($id);
        $model->delete();

        return  $this->ajaxJson();
    }

    private function validatePrivate($operation = 'create')
    {
        if ('update' == $operation) {
            $rules = [
                'name' => 'required',
                'display_name' => 'required',
                'description' => 'required',
            ];
        } else {
            $rules = [
                'name' => 'required | unique:el_roles',
                'display_name' => 'required',
                'description' => 'required',
            ];
        }

        $message = [
            'required' => ':attribute 不能为空',
            'unique' => ':attribute 唯一',
        ];

        $attributes = [
            'name' => '名称',
            'display_name' => '显示名称',
            'description' => '描述',
        ];

        $validator = Validator::make(
            request()->all(),
            $rules,
            $message,
            $attributes
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();

            return ['status' => false, 'error_code' => 0, 'error' => $show_warning,
            ];
        }

        return null;
    }
}
