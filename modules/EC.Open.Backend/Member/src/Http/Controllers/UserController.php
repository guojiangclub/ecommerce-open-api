<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Backend\Member\Http\Controllers;

use GuoJiangClub\Component\Point\Models\Point;
use GuoJiangClub\Component\Point\Repository\PointRepository;
use GuoJiangClub\EC\Open\Backend\Member\Models\User;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Excel;
use GuoJiangClub\EC\Open\Backend\Member\Repository\UserRepository;
use Illuminate\Http\Request;
use Response;
use Validator;

class UserController extends Controller
{
    protected $userRepository;
    protected $integralRepository;
    protected $couponHistoryRepository;
    protected $orderLogRepository;
    protected $pointRepository;
    protected $cache;

    public function __construct(UserRepository $userRepository,
                                PointRepository $pointRepository

    )
    {
        $this->userRepository = $userRepository;
        $this->pointRepository = $pointRepository;
        $this->cache = cache();
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function index()
    {
        $users = $this->UsersSearch(['status' => 1]);

        return LaravelAdmin::content(function (Content $content) use ($users) {
            $content->header('会员管理');

            $content->breadcrumb(
                ['text' => '会员管理', 'url' => 'member/users', 'no-pjax' => 1, 'left-menu-active' => '会员管理']
            );

            $content->body(view('member-backend::auth.index', compact('users')));
        });
    }

    /**
     * @param CreateUserRequest $request
     *
     * @return mixed
     */
    public function create()
    {
        return LaravelAdmin::content(function (Content $content) {
            $content->header('创建会员');

            $content->breadcrumb(
                ['text' => '会员管理', 'url' => 'member/users', 'no-pjax' => 1],
                ['text' => '创建会员', 'url' => 'member/users/create', 'no-pjax' => 1, 'left-menu-active' => '会员管理']
            );

            $content->body(view('member-backend::auth.create'));
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
            'mobile' => 'unique:' . config('ibrand.app.database.prefix', 'ibrand_') . 'user,mobile',
        ];
        $message = [
            'required' => ':attribute 不能为空',
            'unique' => ':attribute 已经存在',
            'email' => ':attribute 格式不正确'
        ];

        $attributes = [
            'name' => '会员名',
            'email' => 'Email',
            'mobile' => '手机号码'
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

        $input = $request->except('_token');

        if (isset($input['email']) && !empty($input['email'])) {
            $data['email'] = $input['email'];
        }

        if (isset($input['nick_name']) && !empty($input['nick_name'])) {
            $data['nick_name'] = $input['nick_name'];
        }

        if (isset($input['password']) && !empty($input['password'])) {
            $data['password'] = $input['password'];
        }

        $data['mobile'] = $input['mobile'];
        $data['status'] = isset($input['status']) ? 1 : 2;
        User::create($data);

        flash('用户创建成功', 'success');

        return redirect()->route('admin.users.index');
    }

    /**
     * @param $id
     * @param EditUserRequest $request
     *
     * @return mixed
     */
    public function edit($id)
    {
        $user = $this->userRepository->findOrThrowException($id, false);
        if (isset($user->bind)) {
            $user->open_id = $user->bind->open_id;
        }

        $redirect_url = request('redirect_url');

        $point = $this->pointRepository->getSumPointValid($user->id);


        return LaravelAdmin::content(function (Content $content) use ($user, $point, $redirect_url) {
            $content->header('编辑会员');

            $content->breadcrumb(
                ['text' => '会员管理', 'url' => 'member/users', 'no-pjax' => 1],
                ['text' => '编辑会员', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '会员管理']
            );

            $content->body(view('member-backend::auth.edit', compact('user', 'point', 'redirect_url'))
            );
        });
    }

    public function getUserPointData($uid)
    {
        $where['user_id'] = $uid;

        $pointData = $this->pointRepository->getPointsByConditions($where, 15);

        return $this->ajaxJson(true, $pointData);
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
            'email' => "unique:" . config('ibrand.app.database.prefix', 'ibrand_') . "user,email,$id|email",
            'mobile' => "unique:" . config('ibrand.app.database.prefix', 'ibrand_') . "user,mobile,$id",
        ];
        $message = [
            'required' => ':attribute 不能为空',
            'unique' => ':attribute 已经存在',
            'email' => ':attribute 格式不正确',
        ];

        $attributes = [
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

            return $this->ajaxJson(false, [], 404, $warning);
        }

        $user = User::find($id);
        $input = $request->except('_token');
        $input['email'] = trim($input['email']);
        $input['status'] = empty(request('status')) ? 2 : request('status');

        $input = array_filter($input);

        if (!empty($input['email']) and $user->email !== $input['email']) {
            if (User::where('email', '=', $input['email'])->first()) {
                return $this->ajaxJson(false, [], 404, '系统已经存在此邮箱');
            }
        }

        $input['email'] = empty($input['email']) ? null : $input['email'];

        $this->userRepository->update($input, $id);

        return $this->ajaxJson(true, [], 200, '更新成功');
    }

    /**
     * @param $id
     * @param DeleteUserRequest $request
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $this->userRepository->destroy($id);
        flash('账号已删除', 'success');

        return redirect()->back();
    }

    /**
     * @param $id
     * @param $status
     * @param MarkUserRequest $request
     *
     * @return mixed
     */
    public function mark($id, $status)
    {
        $this->userRepository->mark($id, $status);
        flash('用户更新成功', 'success');

        return redirect()->back();
    }

    /**
     * @return mixed
     */
    public function banned()
    {
        $users = $this->UsersSearch(['status' => 2]);

        return LaravelAdmin::content(function (Content $content) use ($users) {
            $content->header('已禁用会员');

            $content->breadcrumb(
                ['text' => '会员管理', 'url' => 'member/users', 'no-pjax' => 1],
                ['text' => '已禁用会员', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '会员管理']
            );

            $content->body(view('member-backend::auth.banned', compact('users')));
        });
    }

    /**
     * @param $id
     * @param ChangeUserPasswordRequest $request
     *
     * @return mixed
     */
    public function changePassword($id)
    {
        $user = $this->userRepository->findOrThrowException($id);

        return LaravelAdmin::content(function (Content $content) use ($user) {
            $content->header('更改密码');

            $content->breadcrumb(
                ['text' => '会员管理', 'url' => 'member/users', 'no-pjax' => 1],
                ['text' => '更改密码', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '会员管理']
            );

            $content->body(view('member-backend::auth.change-password', compact('user')));
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
        $this->userRepository->updatePassword($id, $request->all());
        flash('密码修改成功!', 'success');

        return redirect()->route('admin.users.index');
    }


  
    /**用户搜索
     * @param array $where
     * @param bool $delete
     * @return mixed
     */

    public function UsersSearch($where = [], $delete = false)
    {
        if (!empty(request('name'))) {
            $where['name'] = ['like', '%' . request('name') . '%'];
        }

        if (!empty(request('email'))) {
            $where['email'] = ['like', '%' . request('email') . '%'];
        }

        if (!empty(request('mobile'))) {
            $where['mobile'] = ['like', '%' . request('mobile') . '%'];
        }

        if (true == $delete) {
            return $this->userRepository->getDeletedUsersPaginated($where);
        }

        return $this->userRepository->searchUserPaginated($where);
    }

    public function addPoint()
    {
        $id = request('user_id');
        $data = [
            'user_id' => $id,
            'action' => 'admin_action',
            'note' => request('note'),
            'value' => request('value'),
            'status' => 1];
        if (request('value') < 0) {
            $data['valid_time'] = 0;
        }
        $point = Point::create($data);
        return $this->ajaxJson();
    }

    public function userexport()
    {
        $type = request('type');
        return view('member-backend::auth.userexport', compact('type'));
    }

    /**
     * 获取导出数据.
     *
     * @return mixed
     */
    public function getExportData()
    {
        $page = request('page') ? request('page') : 1;
        $limit = request('limit') ? request('limit') : 50;

        $where = [];
        $time = [];

        if (!empty(request('etime')) && !empty(request('stime'))) {
            $where['created_at'] = ['<=', request('etime')];
            $time['created_at'] = ['>=', request('stime')];
        }

        if (!empty(request('etime'))) {
            $time['created_at'] = ['<=', request('etime')];
        }

        if (!empty(request('stime'))) {
            $time['created_at'] = ['>=', request('stime')];
        }

        $users = $this->userRepository->getExportUserData($where, $time, $limit);

        $lastPage = $users['lastPage'];
        $users = $users['users'];
        $type = request('type');

        $adminID = auth('admin')->user()->id;
        $cacheName = request('cache') ? request('cache') : generate_export_cache_name('export_users_cache' . $adminID . '_');

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $users), 300);
        } else {
            $this->cache->put($cacheName, $users, 300);
        }

        if ($page == $lastPage) {
            $title = ['昵称', '邮箱', '电话', '积分',  '注册时间'];

            return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => $type, 'title' => $title, 'cache' => $cacheName, 'prefix' => 'users_data_']);
        }
        $url_bit = route('admin.users.getExportData', array_merge(['page' => $page + 1, 'limit' => $limit, 'cache' => $cacheName], request()->except('page', 'limit', 'cache')));

        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
    }
}
