<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/7
 * Time: 22:57
 */

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\User\MarkUserRequest;
use App\Http\Requests\Backend\Auth\User\PermanentlyDeleteUserRequest;
use App\Http\Requests\Backend\Auth\User\ResendConfirmationEmailRequest;
use App\Http\Requests\Backend\Auth\User\RestoreUserRequest;
use App\Http\Requests\Backend\Auth\User\ChangeUserPasswordRequest;
use App\Http\Requests\Backend\Auth\User\CreateUserRequest;
use App\Http\Requests\Backend\Auth\User\DeleteUserRequest;
use App\Http\Requests\Backend\Auth\User\EditUserRequest;
use App\Http\Requests\Backend\Auth\User\StoreUserRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserPasswordRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserRequest;
use App\Repositories\Backend\RoleRepository;
use iBrand\EC\Open\Backend\Store\Repositories\UserRepository;
use App\Repositories\AddressRepository;
use App\Repositories\IntegralLogRepository;
use App\Repositories\CouponHistoryRepository;
use App\Repositories\OrderLogRepository;
use App\Repositories\UserGroupRepository;
use Illuminate\Http\Request;
use App\Entities\UserGroup;
use DB;
use App\User;
use Auth;
use Validator;

use App\Facades\IntegralService;


use Illuminate\Support\Facades\Event;
use App\Events\IntegralEvent;



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

    public function __construct(UserRepository $userRepository
//        , RoleRepository $roleRepository ,AddressRepository $addressRepository
//        ,IntegralLogRepository $integralLogRepository,UserGroupRepository $userGroupRepository
//        ,CouponHistoryRepository $couponHistoryRepository
//        ,OrderLogRepository $orderLogRepository
    )
    {
        $this->userRepository = $userRepository;
//        $this->roleRepository = $roleRepository;
//        $this->addressRepository = $addressRepository;
//        $this->integralRepository=$integralLogRepository;
//        $this->userGroupRepository=$userGroupRepository;
//        $this->couponHistoryRepository=$couponHistoryRepository;
//        $this->orderLogRepository=$orderLogRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index()
    {

        $users=$this->UsersSearch();
        return view('store-backend::auth.index',compact('users'));

    }

    /**
     * @param CreateUserRequest $request
     * @return mixed
     *
     */
    public function create()
    {
        return view('store-backend::auth.create');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        //验证
        $rules = array(
            'name' => "required|unique:el_user,name",
            'email' => "unique:el_user,email|email",
            'mobile' => "unique:el_user,mobile",

        );
        $message = array(
            "required" => ":attribute 不能为空",
            "unique"=>":attribute 已经存在",
            "email"=>":attribute 格式不正确"
        );

        $attributes = array(
            "name" => "会员名",
            "email"=>"Email",
            "mobile"=>"手机号码",
        );

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



        $this->userRepository->createUser(
            $request->except('assignees_roles', 'permission_user'),
            $request->only('assignees_roles')
        );
        flash('用户创建成功', 'success');
        return redirect()->route('admin.users.index');
    }

    /**
     * @param $id
     * @param EditUserRequest $request
     * @return mixed
     */
    public function edit($id)
    {
        $user = $this->userRepository->findOrThrowException($id, false);
//        $address=$this->addressRepository->findByField('user_id',$user->id);
//        $integral=$this->integralRepository->getIntegralLogsPaginated(['user_id'=>$user->id],50);
//        $coupons=$this->couponHistoryRepository->getCouponsHistoryPaginated(['user_id'=>$user->id],50);
//        $orders=$this->orderLogRepository->getUserOrderLogPaginated(['user_id'=>$user->id],50);
//
//        foreach ($address as $key=>$item){
//            $item->add_list=$item->address_name.$item->address;
//        }

        return view('store-backend::auth.edit')
            ->withUser($user);
//            ->withUserAddress($address)
//            ->withIntegral($integral)
//            ->withCoupons($coupons)
//            ->withOrders($orders)
//            ->withUserRoles($user->roles->lists('id')->all())
//            ->withRoles($this->roleRepository->getAllRoles());
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id,Request $request)
    {

       //验证
        $rules = array(
            'name' => "required|unique:el_user,name,$id",
            'email' => "unique:el_user,email,$id|email",
            'mobile' => "unique:el_user,mobile,$id",

        );
        $message = array(
            "required" => ":attribute 不能为空",
            "unique"=>":attribute 已经存在",
            "email"=>":attribute 格式不正确"
        );

        $attributes = array(
            "name" => "会员名",
            "email"=>"Email",
            "mobile"=>"手机号码",
        );

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


       $this->userRepository->updateUser($id,
            $request->except('assignees_roles'),
            $request->only('assignees_roles')
        );
        flash('更新成功', 'success');
         return redirect()->route('admin.users.index');


    }

    /**
     * @param $id
     * @param DeleteUserRequest $request
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

          $users=$this->UsersSearch([],true);
        return view('store-backend::auth.banned',compact('users'));
    }

    /**
     * @return mixed
     */
    public function banned()
    {

        $users=$this->UsersSearch(['status'=>2]);
        return view('store-backend::auth.banned',compact('users'));
    }

    /**
     * @param $id
     * @param ChangeUserPasswordRequest $request
     * @return mixed
     */
    public function changePassword($id)
    {
        return view('store-backend::auth.change-password')
            ->withUser($this->userRepository->findOrThrowException($id));
    }

    /**
     * @param $id
     * @param UpdateUserPasswordRequest $request
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
     * @return mixed
     */
    public function resendConfirmationEmail($user_id)
    {
        $this->userRepository->resendConfirmationEmail($user_id);
        return redirect()->back()->withFlashSuccess(trans("激活邮件发送成功"));
    }

    /**
     * 用户列表
     */
    public function userlist(){

        $users=$this->userRepository->searchUserPaginated([]);

        return view('backend.auth.userlist',compact('user'));

    }

    /**
     * 用户积分记录列表
     * @param $id
     * @return mixed
     */
    public function integrallist($id){

        if($user = $this->userRepository->findOrThrowException($id, true)){
             return view('backend.auth.includes.user-integral-list')
                 ->withIntegral($this->integralRepository->getIntegralLogsPaginated(['user_id'=>$user->id],50));
         };

    }


    /**
     * 用户优惠券记录列表
     * @param $id
     * @return mixed
     */
    public function couponslist($id){

        if($user = $this->userRepository->findOrThrowException($id, true)){
            return view('backend.auth.includes.user-coupons-list')
                ->withCoupons($this->couponHistoryRepository->getCouponsHistoryPaginated(['user_id'=>$user->id],50));
        };

    }


    /**
     * 用户订单记录列表
     * @param $id
     * @return mixed
     */
    public function orderslist($id){

        if($user = $this->userRepository->findOrThrowException($id, true)){
            return view('backend.auth.includes.user-orders-list')
                ->withOrders($this->orderLogRepository->getUserOrderLogPaginated(['user_id'=>$user->id],50));
        };

    }



        /**用户搜索
         * @param array $where
         * @param bool $delete
         * @return mixed
         */

        public  function UsersSearch($where=[],$delete=false){

            if (!empty(request('name'))) {
                $where['name'] = ['like', '%' . request('name') . '%'];
            }

            if (!empty(request('email'))) {
                $where['email'] = ['like', '%' . request('email') . '%'];
            }

            if (!empty(request('integral'))) {
                $where['integral'] = request('integral');
            }

            if (!empty(request('mobile'))) {
                $where['mobile'] = ['like', '%' . request('mobile') . '%'];
            }

            if($delete==true){
                return $this->userRepository->getDeletedUsersPaginated($where);
            }

            return $this->userRepository->searchUserPaginated($where);



        }


// 永久删除用户
//        public  function everDelete(Request $request,$id){
//                   User::withTrashed()->find($id)->forceDelete();
//            return redirect()->back()->withFlashSuccess('用户已删除');
//
//        }




}

