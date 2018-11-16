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

use App\Http\Requests\Backend\Auth\User\ChangeUserPasswordRequest;
use App\Http\Requests\Backend\Auth\User\CreateUserRequest;
use App\Http\Requests\Backend\Auth\User\DeleteUserRequest;
use App\Http\Requests\Backend\Auth\User\EditUserRequest;
use App\Http\Requests\Backend\Auth\User\MarkUserRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserPasswordRequest;
use App\Repositories\AddressRepository;
use App\Repositories\CouponHistoryRepository;
use App\Repositories\IntegralLogRepository;
use App\Repositories\OrderLogRepository;
use Auth;
use Carbon\Carbon;
use ElementVip\Component\Point\Model\Point;
use ElementVip\Component\Point\Repository\PointRepository;
use ElementVip\Component\User\Models\ElGroup;
use ElementVip\Component\User\Models\Permission;
use ElementVip\Component\User\Models\Role;
use iBrand\EC\Open\Backend\Member\Models\Balance;
use iBrand\EC\Open\Backend\Member\Models\User;
use iBrand\EC\Open\Backend\Member\Models\UserGroup;
use ElementVip\Notifications\PointChanged;
//use App\Entities\UserGroup;
use ElementVip\Notifications\PointRecord;
use ElementVip\Store\Backend\Facades\ExcelExportsService;
use ElementVip\Store\Backend\Repositories\UserRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Response;
use Validator;

class UserController extends Controller
{
    protected $userRepository;

    protected $roleRepository;

    protected $permissions;

    protected $addressRepository;

    protected $integralRepository;

    protected $couponHistoryRepository;

    protected $userGroupRepository;

    protected $orderLogRepository;

    protected $pointRepository;

    protected $cache;

    public function __construct(UserRepository $userRepository, PointRepository $pointRepository
//        , RoleRepository $roleRepository ,AddressRepository $addressRepository
//        ,IntegralLogRepository $integralLogRepository,UserGroupRepository $userGroupRepository
//        ,CouponHistoryRepository $couponHistoryRepository
//        ,OrderLogRepository $orderLogRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->pointRepository = $pointRepository;
        $this->cache = cache();
//        $this->roleRepository = $roleRepository;
//        $this->addressRepository = $addressRepository;
//        $this->integralRepository=$integralLogRepository;
//        $this->userGroupRepository=$userGroupRepository;
//        $this->couponHistoryRepository=$couponHistoryRepository;
//        $this->orderLogRepository=$orderLogRepository;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function index()
    {
        $users = $this->UsersSearch(['status' => 1]);

        //return view('member-backend::auth.index', compact('users'));

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
        $groups = UserGroup::all();

        //return view('member-backend::auth.create', compact('groups'));

        return LaravelAdmin::content(function (Content $content) use ($groups) {
            $content->header('创建会员');

            $content->breadcrumb(
                ['text' => '会员管理', 'url' => 'member/users', 'no-pjax' => 1],
                ['text' => '创建会员', 'url' => 'member/users/create', 'no-pjax' => 1, 'left-menu-active' => '会员管理']
            );

            $content->body(view('member-backend::auth.create', compact('groups')));
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
            //'name' => "required|unique:el_user,name",
            // 'email' => "unique:el_user,email|email",
            'mobile' => 'unique:el_user,mobile',
            'group_id' => 'required',
        ];
        $message = [
            'required' => ':attribute 不能为空',
            'unique' => ':attribute 已经存在',
            'email' => ':attribute 格式不正确',
            'group_id' => ':attribute 不能为空',
        ];

        $attributes = [
            'name' => '会员名',
            'email' => 'Email',
            'mobile' => '手机号码',
            'group_id' => '用户等级分组',
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

        $input = $request->except('assignees_roles', 'permission_user', '_token');

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
        $data['confirmation_code'] = md5(uniqid(mt_rand(), true));
        $data['confirmed'] = isset($input['confirmed']) ? 1 : 0;
        $data['group_id'] = isset($input['group_id']) ? $input['group_id'] : 1;
        User::create($data);

//        $this->userRepository->createUser(
//            $request->except('assignees_roles', 'permission_user'),
//            $request->only('assignees_roles')
//        );
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

        $balance = Balance::where('user_id', $user->id)->sum('value');

        $points = $user->points()->paginate(20);
//        $address=$this->addressRepository->findByField('user_id',$user->id);
//        $integral=$this->integralRepository->getIntegralLogsPaginated(['user_id'=>$user->id],50);
//        $coupons=$this->couponHistoryRepository->getCouponsHistoryPaginated(['user_id'=>$user->id],50);
//        $orders=$this->orderLogRepository->getUserOrderLogPaginated(['user_id'=>$user->id],50);
//
//        foreach ($address as $key=>$item){
//            $item->add_list=$item->address_name.$item->address;
//        }
        $roles = Role::get();
        $redirect_url = request('redirect_url');

        $groups = ElGroup::all();

//        return view('member-backend::auth.edit', compact('roles', 'balance', 'groups'))
//            ->withUser($user)
//            ->withPoints($points)
//            ->withRedirectUrl($redirect_url);

//            ->withUserAddress($address)
//            ->withIntegral($integral)
//            ->withCoupons($coupons)
//            ->withOrders($orders)
//            ->withUserRoles($user->roles->lists('id')->all())
//            ->withRoles($this->roleRepository->getAllRoles());

        return LaravelAdmin::content(function (Content $content) use ($roles, $balance, $groups, $user, $points, $redirect_url) {
            $content->header('编辑会员');

            $content->breadcrumb(
                ['text' => '会员管理', 'url' => 'member/users', 'no-pjax' => 1],
                ['text' => '编辑会员', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '会员管理']
            );

            $content->body(view('member-backend::auth.edit', compact('roles', 'balance', 'groups', 'user', 'points', 'redirect_url'))
            );
        });
    }

    public function getUserPointData($uid, $type = 'offline')
    {
        if ('offline' == $type) {
            $where['type'] = $type;
        } else {
            $where['type'] = ['type', '<>', 'offline'];
        }
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
//            'name' => "required|unique:el_user,name,$id",
            'email' => "unique:el_user,email,$id|email",
            'mobile' => "unique:el_user,mobile,$id",
        ];
        $message = [
            'required' => ':attribute 不能为空',
            'unique' => ':attribute 已经存在',
            'email' => ':attribute 格式不正确',
        ];

        $attributes = [
//            "name" => "会员名",
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
        $input = $request->except('assignees_roles', 'permissions', 'userGroups');
        $input['email'] = trim($input['email']);
        $input['status'] = empty(request('status')) ? 2 : request('status');
        $input['confirmed'] = empty(request('confirmed')) ? 0 : request('confirmed');
        $input = array_filter($input);

//        $this->userRepository->updateUser($id, $input, $request->only('assignees_roles'));

        if (!empty($input['email']) and $user->email !== $input['email']) {
            if (User::where('email', '=', $input['email'])->first()) {
                return $this->ajaxJson(false, [], 404, '系统已经存在此邮箱');
            }
        }

        $input['email'] = empty($input['email']) ? null : $input['email'];

        $this->userRepository->update($input, $id);

        if (request()->has('permissions')) {
            $selectRoles = request()->permissions;
        } else {
            $selectRoles = [];
        }
        $roles = Role::pluck('id')->toArray();
        if (!empty($roles)) {
            $user->detachRoles($roles);
        }
        if (!empty($selectRoles)) {
            $user->attachRoles($selectRoles);
        }

        $selectGroups = request('userGroups') ? request('userGroups') : [];
        $groups = ElGroup::pluck('id')->toArray();
        if (!empty($groups)) {
            $user->detachGroups($groups);
        }
        if (count($selectGroups)) {
            $user->attachGroups($selectGroups);
        }

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
    public function deleted()
    {
        $users = $this->UsersSearch([], true);

        //return view('member-backend::auth.deleted', compact('users'));

        return LaravelAdmin::content(function (Content $content) use ($users) {
            $content->header('已删除会员');

            $content->breadcrumb(
                ['text' => '会员管理', 'url' => 'member/users', 'no-pjax' => 1],
                ['text' => '已删除会员', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '会员管理']
            );

            $content->body(view('member-backend::auth.deleted', compact('users')));
        });
    }

    /**
     * @return mixed
     */
    public function banned()
    {
        $users = $this->UsersSearch(['status' => 2]);

        //return view('member-backend::auth.banned', compact('users'));

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

        //return view('member-backend::auth.change-password')
        //  ->withUser($this->userRepository->findOrThrowException($id));

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

    /**
     * @param $user_id
     *
     * @return mixed
     */
    public function resendConfirmationEmail($user_id)
    {
        $this->userRepository->resendConfirmationEmail($user_id);

        return redirect()->back()->withFlashSuccess(trans('激活邮件发送成功'));
    }

    /**
     * 用户列表.
     */
    public function userlist()
    {
        $users = $this->userRepository->searchUserPaginated([]);

        //return view('member-backend::auth.userlist', compact('users'));

        return LaravelAdmin::content(function (Content $content) use ($users) {
            $content->header('会员管理');

            $content->breadcrumb(
                ['text' => '会员管理', 'url' => 'member/users', 'no-pjax' => 1, 'left-menu-active' => '会员管理']
            );

            $content->body(view('member-backend::auth.userlist', compact('users')));
        });
    }

    /**
     * 用户积分记录列表.
     *
     * @param $id
     *
     * @return mixed
     */
    public function integrallist($id)
    {
        if ($user = $this->userRepository->findOrThrowException($id, true)) {
            return view('member-backend::auth.includes.user-integral-list')
                ->withIntegral($this->integralRepository->getIntegralLogsPaginated(['user_id' => $user->id], 50));
        }
    }

    /**
     * 用户优惠券记录列表.
     *
     * @param $id
     *
     * @return mixed
     */
    public function couponslist($id)
    {
        if ($user = $this->userRepository->findOrThrowException($id, true)) {
            return view('member-backend::auth.includes.user-coupons-list')
                ->withCoupons($this->couponHistoryRepository->getCouponsHistoryPaginated(['user_id' => $user->id], 50));
        }
    }

    /**
     * 用户订单记录列表.
     *
     * @param $id
     *
     * @return mixed
     */
    public function orderslist($id)
    {
        if ($user = $this->userRepository->findOrThrowException($id, true)) {
            return view('member-backend::auth.includes.user-orders-list')
                ->withOrders($this->orderLogRepository->getUserOrderLogPaginated(['user_id' => $user->id], 50));
        }
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

//            if (!empty(request('integral'))) {
//                $where['integral'] = request('integral');
//            }

        if (!empty(request('mobile'))) {
            $where['mobile'] = ['like', '%' . request('mobile') . '%'];
        }

        if (true == $delete) {
            return $this->userRepository->getDeletedUsersPaginated($where);
        }

        return $this->userRepository->searchUserPaginated($where);
    }

    public function userexport()
    {
        $user_group = UserGroup::all();
        $type = request('type');

        return view('member-backend::auth.userexport', compact('user_group', 'type'));


    }

    public function getexport()
    {
        $input = request()->except('_token', 'stime', 'etime');
        $time = [];
        $data = [];

        foreach ($input as $k => $v) {
            if (empty($v)) {
                unset($input[$k]);
            }
        }

        if (!empty(request('etime')) && !empty(request('stime'))) {
            $input['created_at'] = ['<=', request('etime')];
            $time['created_at'] = ['>=', request('stime')];
        }

        if (!empty(request('etime'))) {
            $time['created_at'] = ['<=', request('etime')];
        }

        if (!empty(request('stime'))) {
            $time['created_at'] = ['>=', request('stime')];
        }
//            return $input;
        $data = $this->userRepository->getUserExportList($input, $time);
        //return $data;
        $titles = ['会员名', '邮箱', '电话', '积分', '角色', '注册时间', '会员卡号', '申领日期', '注册姓名', '手机号', '出生年月日', 'open_id'];
//        $titles=['会员名','邮箱','电话','积分','注册时间','会员卡号','申领日期','注册姓名','手机号','出生年月日'];
        return ExcelExportsService::createExcelExport('User_', $data, $titles);
    }

    public function download()
    {
//        return $url;
        $url = request('url');

        return Response::download(storage_path() . "/exports/$url");
    }

    // 永久删除用户
//        public  function everDelete(Request $request,$id){
//                   User::withTrashed()->find($id)->forceDelete();
//            return redirect()->back()->withFlashSuccess('用户已删除');
//
//        }

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


        event('point.change', $id);

        $user = User::find($id);
        $user->notify(new PointRecord(['point' => [
            'user_id' => $id,
            'action' => 'admin_action',
            'note' => request('note'),
            'value' => request('value'),
            'valid_time' => 0,
            'status' => 1,]]));

        $user->notify((new PointChanged(compact('point')))->delay(Carbon::now()->addSecond(30)));

        return $this->ajaxJson();
    }

    /**
     * 导入会员.
     *
     * @return mixed
     */
    public function importUser()
    {
        return view('member-backend::auth.includes.import-user');

//        return LaravelAdmin::content(function (Content $content)  {
//
//            $content->body(view('member-backend::auth.includes.import-user'));
//        });
    }

    public function saveImport()
    {
        $data = [];
        $filename = 'public' . request('upload_excel');
        Excel::load($filename, function ($reader) {
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $results = $reader->toArray();

            foreach ($results as $key => $value) {
                if (0 != $key) {
                    $data['nick_name'] = trim($value[0]);
                    $data['mobile'] = trim($value[1]);
                    $data['email'] = trim($value[2]);
                    $data['name'] = trim($value[3]);
                    $data['password'] = trim($value[4]);
                    $data['status'] = 1;
                    $data['confirmation_code'] = md5(uniqid(mt_rand(), true));
                    $data['group_id'] = 1;

                    if ($data['mobile'] AND User::where('mobile', $data['mobile'])->first()) {
                        continue;
                    }

                    if ($data['email'] AND User::where('email', $data['email'])->first()) {
                        continue;
                    }

                    if ($data['name'] AND User::where('name', $data['name'])->first()) {
                        continue;
                    }

                    if (!$data['name'] AND !$data['mobile'] AND !$data['email']) {
                        break;
                    }

                    $user = User::create($data);

                    if ($user AND $value[5]) {
                        $selectRoles = Role::where('display_name', trim($value[5]))->get()->pluck('id')->toArray();                        
                        $user->attachRoles($selectRoles);
                    }

                }
            }
        });

        return $this->ajaxJson(true, $data, 200, '');
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
        if ($group_id = request('group_id')) {
            $where['group_id'] = $group_id;
        }

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
        //\Log::info('cache:'.$cacheName);

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $users), 300);
        } else {
            $this->cache->put($cacheName, $users, 300);
        }

        if ($page == $lastPage) {
            $title = ['会员名', '邮箱', '电话', '积分', '角色', '注册时间', '会员卡号', '申领日期', '注册姓名', '手机号', '出生年月日', 'open_id'];

            return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => $type, 'title' => $title, 'cache' => $cacheName, 'prefix' => 'users_data_']);
        }
        $url_bit = route('admin.users.getExportData', array_merge(['page' => $page + 1, 'limit' => $limit, 'cache' => $cacheName], request()->except('page', 'limit', 'cache')));

        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
    }
}
