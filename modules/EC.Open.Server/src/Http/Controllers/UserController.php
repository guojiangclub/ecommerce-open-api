<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Http\Controllers;

use GuoJiangClub\Component\Address\Address;
use GuoJiangClub\Component\Discount\Repositories\CouponRepository;
use GuoJiangClub\Component\Favorite\Favorite;
use GuoJiangClub\Component\Order\Models\Order;
use GuoJiangClub\Component\Order\Repositories\OrderRepository;
use GuoJiangClub\Component\User\Models\User;
use GuoJiangClub\Component\User\Repository\UserRepository;
use GuoJiangClub\EC\Open\Server\Transformers\UserTransformer;
use iBrand\Sms\Facade as Sms;
use Illuminate\Http\Request;
use Image;

class UserController extends Controller
{
    private $user;
    private $order;

    public function __construct(UserRepository $userRepository, OrderRepository $orderRepository)
    {
        $this->user = $userRepository;
        $this->order = $orderRepository;
    }

    public function me()
    {
        $user = request()->user();

        return $this->response()->item($user, new UserTransformer());
    }

    public function show($id)
    {
        $user = $this->user->with('group')->with('size')->find($id);

        return $this->response()->item($user, new UserTransformer());
    }

    public function updateInfo()
    {
        $user = request()->user();

        $data = array_filter(request()->only(['name', 'nick_name', 'sex', 'birthday', 'city', 'education', 'qq', 'avatar', 'email', 'password']));

        if (isset($data['name']) and ($data['name']) != $user->name and User::where('name', $data['name'])->first()) {
            return $this->failed('此用户名已存在');
        }

        $user->fill($data);
        $user->save();

        return $this->success();
    }

    public function uploadAvatar(Request $request)
    {
        //TODO: 需要验证是否传入avatar_file 参数
        $file = $request->file('avatar_file');
        $Orientation = $request->input('Orientation');

        $destinationPath = storage_path('app/public/uploads');

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $extension = $file->getClientOriginalExtension();
        $filename = $this->generaterandomstring() . '.' . $extension;

        $image = $file->move($destinationPath, $filename);

        $img = Image::make($image);

        switch ($Orientation) {
            case 6://需要顺时针（向左）90度旋转
                $img->rotate(-90);
                break;
            case 8://需要逆时针（向右）90度旋转
                $img->rotate(90);
                break;
            case 3://需要180度旋转
                $img->rotate(180);
                break;
        }

        $img->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        })->crop(320, 320, 0, 0)->save();

        if ('save' == request('action')) {
            $user = $request->user();
            $user->avatar = '/storage/uploads/' . $filename;
            $user->save();
        }

        return $this->success(['url' => url('/storage/uploads/' . $filename)]);
    }

    public function updateMobile(Request $request)
    {
        if (!Sms::checkCode(\request('mobile'), \request('code'))) {
            return $this->failed('验证码错误');
        }

        $user = $request->user();

        if ($existUser = $this->user->findWhere(['mobile' => request('mobile')])->first()) {
            return $this->failed('此手机号已绑定账号');
        }
        $user = $this->user->update(['mobile' => $request->input('mobile')], $user->id);

        return $this->response()->item($user, new UserTransformer());
    }

    private function generaterandomstring($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function ucenter()
    {
        $user = request()->user();
        $newCount = $this->order->getOrderCountByUserAndStatus(request()->user()->id, Order::STATUS_NEW);
        $paidCount = $this->order->getOrderCountByUserAndStatus(request()->user()->id, Order::STATUS_PAY);
        $deliveredCount = $this->order->getOrderCountByUserAndStatus(request()->user()->id, Order::STATUS_DELIVERED);
        $receiveCount = $this->order->getOrderCountByUserAndStatus(request()->user()->id, Order::STATUS_RECEIVED);

        $favCount = Favorite::where('user_id', $user->id)->count();
        $addressCount = Address::where('user_id', $user->id)->count();
        $couponCount = app(CouponRepository::class)->findActiveByUser($user->id)->count();

        return $this->success(compact('newCount', 'paidCount', 'deliveredCount', 'receiveCount', 'favCount', 'addressCount', 'couponCount'));
    }
}
