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
use ElementVip\Component\User\Models\Permission;
use ElementVip\Component\User\Models\Role;
use ElementVip\Component\User\Models\User;
use ElementVip\Store\Backend\Repositories\UserRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Excel;
use Illuminate\Http\Request;
use Validator;

class RoleController extends Controller
{
    protected $role;
    protected $permission;
    protected $userRepository;

    /**
     * RoleController constructor.
     *
     * @param $role
     */
    public function __construct(Role $role, Permission $permission, UserRepository $userRepository)
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $models = $this->role->paginate(15);
        $users = $this->UsersSearch();

//        $users = $users->map(function ($item, $key) {
//            $item->roleIndex = User::findOrFail($item->id)->roles()->get();
//
//            return $item;
//        });


        //return view('member-backend::role.roleIndex', compact('models', 'users'));

        return LaravelAdmin::content(function (Content $content) use ($models,$users) {
            $content->header('角色管理');

            $content->breadcrumb(
                ['text' => '角色管理', 'url' => 'member/RoleManagement/role/index','no-pjax' => 1,'left-menu-active'=>'角色管理']
            );

            $content->body(view('member-backend::role.roleIndex', compact('models', 'users')));
        });
    }

    public function userList($role_id)
    {
        $where = [];
        $ids = [];
        $role = Role::find($role_id);
        $users = $role->users()->paginate(15);
        $value = request('value');
        if ('' != request('value') && count($users) > 0) {
            $where['mobile'] = ['like', '%'.request('value').'%'];
            $data = $this->userRepository->scopeQuery(function ($query) use ($where) {
                if (is_array($where)) {
                    foreach ($where as $key => $value) {
                        if (is_array($value)) {
                            list($operate, $va) = $value;
                            $query = $query->where($key, $operate, $va);
                        } else {
                            $query = $query->where($key, $value);
                        }
                    }
                }

                return $query->orderBy('created_at', 'desc');
            })->all();
            $ids = $data->pluck('id')->toArray();

            if (count($ids) > 0) {
                $ids = array_values($ids);
                $user = $role->users()->get()->pluck('id')->toArray();
                $user_id = array_values($user);
                $id = array_intersect($ids, $user_id);
                $users = $role->users()->whereIn('user_id', $id)->paginate(15);
            } else {
                $ids = 'no';
            }
        }

        //return view('member-backend::role.role_user_list', compact('users', 'role', 'value', 'ids'));

        return LaravelAdmin::content(function (Content $content) use ($users,$role,$value,$ids) {
            $content->header('角色用户管理');

            $content->breadcrumb(
                ['text' => '角色管理', 'url' => 'member/RoleManagement/role/index', 'no-pjax' => 1],
                ['text' => '角色用户管理', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'角色管理']
            );

            $content->body(view('member-backend::role.role_user_list', compact('users', 'role', 'value', 'ids')));
        });
    }

    public function create()
    {
        //return view('member-backend::role.roleCreate');

        return LaravelAdmin::content(function (Content $content) {
            $content->header('创建角色');

            $content->breadcrumb(
                ['text' => '角色管理', 'url' => 'member/RoleManagement/role/index', 'no-pjax' => 1],
                ['text' => '创建角色', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'角色管理']
            );

            $content->body(view('member-backend::role.roleCreate'));
        });
    }

    public function store()
    {
        $status = $this->validatePrivate();
        if (is_array($status)) {
            return response()->json($status);
        }

        $this->role->create(request()->only(['name', 'display_name', 'description']));

        return response()->json([
            'status' => true,
        ]);
    }

    public function edit($id)
    {
        $model = $this->role->find($id);
        $permissionModels = $this->permission->get();

        //return view('member-backend::role.roleEdit', compact('model', 'permissionModels'));

        return LaravelAdmin::content(function (Content $content) use ($model,$permissionModels) {
            $content->header('编辑角色');

            $content->breadcrumb(
                ['text' => '角色管理', 'url' => 'member/RoleManagement/role/index', 'no-pjax' => 1],
                ['text' => '编辑角色', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'角色管理']
            );

            $content->body(view('member-backend::role.roleEdit', compact('model', 'permissionModels')));
        });
    }

    public function update($id)
    {
        $status = $this->validatePrivate('update');
        if (is_array($status)) {
            return response()->json($status);
        }
        if (request()->has('permissions')) {
            $selectPermissions = request()->permissions;
        } else {
            $selectPermissions = [];
        }

        $allPermissions = $this->permission->pluck('id')->toArray();

        $role = $this->role->findOrFail($id);

        if (!empty(request('name')) and $role->name !== request('name')) {
            if ($this->role->where('name', '=', request('name'))->first()) {
                return response()->json([
                    'status' => false,
                    'error' => '角色已经存在',
                ]);
            }
        }

        if (!empty($allPermissions)) {
            $role->detachPermissions($allPermissions);
        }

        if (!empty($selectPermissions)) {
            $role->attachPermissions($selectPermissions);
        }

        $role->update(request()->only(['name', 'display_name', 'description']));

        return response()->json([
            'status' => true,
        ]);
    }

    public function roleUserUpdate($user_id)
    {
        $user = User::find($user_id);

        if (request()->has('permissions')) {
            $selectRoles = request()->permissions;
        } else {
            $selectRoles = [];
        }

        $roles = $this->role->pluck('id')->toArray();

        if (!empty($roles)) {
            $user->detachRoles($roles);
        }
        if (!empty($selectRoles)) {
            $user->attachRoles($selectRoles);
        }

        return redirect(route('admin.RoleManagement.role.index'));
    }

    public function delete($id)
    {
        $model = $this->role->findOrFail($id);
        if ($model->users()->count() > 0) {
            return $this->ajaxJson(false, [], 405, '该角色下存在会员，不可删除');
        }

        $model->delete();

        return $this->ajaxJson();
    }

    public function roleUserEdit($user_id)
    {
        $user = User::find($user_id);

        $roles = $this->role->get();

        //return view('member-backend::role.roleUserEdit', compact('user', 'roles'));

        return LaravelAdmin::content(function (Content $content) use ($user,$roles) {
            $content->header('角色用户管理');

            $content->breadcrumb(
                ['text' => '角色管理', 'url' => 'member/RoleManagement/role/index', 'no-pjax' => 1,'left-menu-active'=>'角色管理']
            );

            $content->body(view('member-backend::role.roleUserEdit', compact('user', 'roles')));
        });
    }

    /**用户搜索
     * @param array $where
     * @param bool $delete
     * @return mixed
     */

    public function UsersSearch($where = [], $delete = false)
    {
        if (!empty(request('name'))) {
            $where['name'] = ['like', '%'.request('name').'%'];
        }

        if (!empty(request('email'))) {
            $where['email'] = ['like', '%'.request('email').'%'];
        }

        if (!empty(request('integral'))) {
            $where['integral'] = request('integral');
        }

        if (!empty(request('mobile'))) {
            $where['mobile'] = ['like', '%'.request('mobile').'%'];
        }

        if (true == $delete) {
            return $this->userRepository->getDeletedUsersPaginated($where);
        }

        return $this->userRepository->searchUserPaginated($where, 20);
    }

    private function validatePrivate($operation = 'create')
    {
        if ('update' == $operation) {
            $rules = [
                'name' => 'required',
                'display_name' => 'required',
            ];
        } else {
            $rules = [
                'name' => 'required |unique:el_roles',
                'display_name' => 'required',
            ];
        }

        $message = [
            'required' => ':attribute 不能为空',
            'unique' => ':attribute 已存在',
            'alpha' => '由字母构成',
        ];

        $attributes = [
            'name' => '角色名称',
            'display_name' => '显示名称',
        ];

        $validator = Validator::make(
            request()->all(),
            $rules,
            $message,
            $attributes
        );

        $name = request('name');

        if (is_string($name) && !preg_match('/^[a-zA-Z]+$/u', $name)) {
            return ['status' => false, 'error_code' => 0, 'error' => '角色名称必须为纯字母',
            ];
        }

        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();

            return ['status' => false, 'error_code' => 0, 'error' => $show_warning,
            ];
        }

        return null;
    }

    public function userModal($id)
    {
        return view('member-backend::role.includes.user_modal', compact('id'));

        return LaravelAdmin::content(function (Content $content) use ($id) {
//            $content->header('角色用户管理');

            $content->breadcrumb(
                ['text' => '角色管理', 'url' => 'member/RoleManagement/role/index', 'no-pjax' => 1,'left-menu-active'=>'角色管理']
            );

            $content->body(view('member-backend::role.includes.user_modal', compact('id')));
        });
    }

    public function UsersSearchList()
    {
        $users = $this->UsersSearch();
        $role_id = request('role_id');
        $roles = DB::table('el_role_user')->where('role_id', $role_id)->get();
        $checked = collect($roles)->pluck('user_id')->all();

        return ['status' => true, 'code' => 200, 'data' => $users, 'checked' => $checked,
        ];
    }

    // 批量删除角色会员
    public function allotDelRole($rid)
    {
        $role = Role::find($rid);
        $uid = request('uid');
        if (count($uid) > 0) {
            foreach ($uid as $item) {
                try {
                    $user = User::find($item);
                    $user->detachRole($rid);
                } catch (\Exception $e) {
                }
            }
        }

        return $this->ajaxJson(true, [], 200, '');
    }

    // 批量创建角色会员
    public function allotAddRole()
    {
        $uid = request('uid');
        $rid = request('rid');
        if (count($uid) > 0) {
            foreach ($uid as $item) {
                try {
                    $user = User::find($item);
                    $user->attachRole($rid);
                } catch (\Exception $e) {
                }
            }
        }

        return $this->ajaxJson(true, [], 200, '');
    }

//    public function allotRole()
//    {
//        $user_ids = count(request('user_ids')) <= 0 || empty(request('user_ids')) ? [] : request('user_ids');
//        $role_id = request('role_id');
//        $old_user_ids = count(request('old_user_ids')) <= 0 || empty(request('old_user_ids')) ? [] : request('old_user_ids');
//
//
//        if (count($old_user_ids) > 0) {
//            $userIDs = array_intersect($old_user_ids, $user_ids);
//        } else {
//            $userIDs = [];
//        }
//
//        $del_user = array_diff($old_user_ids, $userIDs);
//        $add_user = array_diff($user_ids, $userIDs);
//
//        $role=Role::find($role_id);
//
//        if (count($del_user) > 0) {
//            foreach ($del_user as $item) {
//                $user = User::find($item);
//                $user->detachRole($role_id);
//
//            }
//        }
//
//        if (count($add_user) > 0) {
//            foreach ($add_user as $item) {
//                $user = User::find($item);
//                if(!$user->hasRole($role->name)){
//                    $user->attachRole($role_id);
//                }
//            }
//        }
//
//
//        return ['status' => true
//            , 'code' => 200
//        ];
//    }

    /**
     * 导入会员.
     *
     * @return mixed
     */
    public function importUser()
    {
        $role_id = request('role_id');

       return view('member-backend::role.includes.import-user', compact('role_id'));

    }

    public function saveImport()
    {
        $data = [];
        $filename = 'public'.request('upload_excel');
        $role_id = request('role_id');
        $role = Role::find($role_id);

        Excel::load($filename, function ($reader) use ($role_id,$role) {
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $results = $reader->toArray();

            foreach ($results as $key => $value) {
                if (0 != $key and !empty($value[0]) and $user = User::where(['mobile' => $value[0]])->first() and !$user->hasRole($role->name)) {
                    $user->attachRole($role_id);
                }
            }
        });

        return $this->ajaxJson(true, $data, 200, '');
    }
}
