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

use App\Http\Controllers\Controller;
use iBrand\EC\Open\Backend\Member\Models\Admin;
use iBrand\EC\Open\Backend\Member\Models\UserLoginLog;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Datatables;

class AdminController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function index()
    {
        $users = Admin::all();

        //return view('member-backend::admin.index', compact('users'));

        return LaravelAdmin::content(function (Content $content) use ($users) {
            $content->header('管理员管理');

            $content->breadcrumb(
                ['text' => '管理员管理', 'url' => 'manager', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::admin.index', compact('users')));
        });
    }

    /**
     * 创建管理员.
     *
     * @return mixed
     */
    public function create()
    {
        //return view('member-backend::admin.create');

        return LaravelAdmin::content(function (Content $content) {
            $content->header('管理员管理');

            $content->breadcrumb(
                ['text' => '管理员管理', 'url' => 'manager', 'no-pjax' => 1],
                ['text' => '创建管理员', 'url' => '', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::admin.create'));
        });
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        //验证
        $rules = [
            'name' => 'required|unique:el_admin,name',
            'email' => 'unique:el_admin,email|email',
            'mobile' => 'unique:el_admin,mobile',
        ];
        $message = [
            'required' => ':attribute 不能为空',
            'unique' => ':attribute 已经存在',
            'email' => ':attribute 格式不正确',
            'mobile' => ':attribute 格式不正确',
        ];

        $attributes = [
            'name' => '登录账号',
            'email' => 'Email',
            'mobile' => '手机号码',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            $message,
            $attributes
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $warning = $warnings->first();

            flash($warning, 'danger');

            return redirect()->back()->withInput();
        }

        //验证验证码
        /*$credentials = [
            'mobile' => $request->input('mobile'),
            'verifyCode' => $request->input('code'),
        ];

        $validator = Validator::make($credentials, [
            'mobile' => 'required|confirm_mobile_not_change|confirm_rule:mobile_required',
            'verifyCode' => 'required|verify_code',
        ]);

        if ($validator->fails()) {
            flash('验证码错误', 'danger');
            return redirect()->back()->withInput();
        }*/

        $data = $request->except('password_confirmation', 'code', '_token', 'access_token');

        Admin::create($data);

        flash('管理员创建成功', 'success');

        return redirect()->route('admin.manager.index');
    }

    /**
     * @param $id
     * @param EditUserRequest $request
     *
     * @return mixed
     */
    public function edit($id)
    {
        $user = Admin::find($id);

        //return view('member-backend::admin.edit')->withUser($user);

        return LaravelAdmin::content(function (Content $content) use ($user) {
            $content->header('编辑管理员');

            $content->breadcrumb(
                ['text' => '管理员管理', 'url' => 'manager', 'no-pjax' => 1],
                ['text' => '编辑管理员', 'url' => '', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::admin.edit', compact('user')));
        });
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return mixed
     */
    public function update($id, Request $request)
    {
        //验证
        $rules = [
            'name' => "required|unique:el_admin,name,$id",
            'email' => "unique:el_admin,email,$id|email",
            'mobile' => "unique:el_admin,mobile,$id",
        ];
        $message = [
            'required' => ':attribute 不能为空',
            'unique' => ':attribute 已经存在',
            'email' => ':attribute 格式不正确',
            'mobile' => ':attribute 格式不正确',
        ];

        $attributes = [
            'name' => '登录账号',
            'email' => 'Email',
            'mobile' => '手机号码',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            $message,
            $attributes
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $warning = $warnings->first();

            flash($warning, 'danger');

            return redirect()->back()->withInput();
        }

        $data = $request->except('password_confirmation', '_token', 'code');
        $user = Admin::find($id);
        $user->fill($data);
        $user->save();

        flash('更新成功', 'success');

        return redirect()->route('admin.manager.index');
    }

    /**
     * @param $id
     * @param DeleteUserRequest $request
     *
     * @return mixed
     */
    public function destroy($id)
    {
        Admin::destroy($id);
        flash('管理员账号已删除', 'success');

        return redirect()->back();
    }

    /**
     * @param $id
     * @param ChangeUserPasswordRequest $request
     *
     * @return mixed
     */
    public function changePassword($id)
    {
        //return view('member-backend::admin.change-password')
        //  ->withUser(Admin::find($id));

        $user = Admin::find($id);

        return LaravelAdmin::content(function (Content $content) use ($user) {
            $content->header('修改密码');

            $content->breadcrumb(
                ['text' => '管理员管理', 'url' => 'manager', 'no-pjax' => 1],
                ['text' => '修改密码', 'url' => '', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::admin.change-password', compact('user')));
        });
    }

    /**
     * @param $id
     * @param UpdateUserPasswordRequest $request
     *
     * @return mixed
     */
    public function updatePassword($id, Request $request)
    {
        $user = Admin::find($id);
        $user->fill($request->only('password'));
        $user->save();

        flash('密码修改成功!', 'success');

        return redirect()->route('admin.manager.index');
    }

    public function loginLog()
    {
        //return view('member-backend::admin.login-log');

        return LaravelAdmin::content(function (Content $content) {
            $content->header('登陆日志');

            $content->breadcrumb(
                ['text' => '管理员管理', 'url' => 'manager', 'no-pjax' => 1],
                ['text' => '登陆日志', 'url' => '', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::admin.login-log'));
        });
    }

    public function getLoginLog()
    {
        $logs = UserLoginLog::select()->orderBy('created_at', 'desc');

        return Datatables::of($logs)
            ->editColumn('ip_info', function ($log) {
                return $log->info;
            })
            ->make(true);
    }
}
