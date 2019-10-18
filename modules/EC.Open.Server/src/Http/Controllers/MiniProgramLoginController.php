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

use GuoJiangClub\Component\User\Repository\UserBindRepository;
use GuoJiangClub\Component\User\Repository\UserRepository;
use GuoJiangClub\Component\User\UserService;
use iBrand\Common\Wechat\Factory;

class MiniProgramLoginController extends Controller
{
    protected $userRepository;
    protected $userBindRepository;
    protected $userService;

    public function __construct(UserRepository $userRepository, UserBindRepository $userBindRepository
        , UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userBindRepository = $userBindRepository;
        $this->userService = $userService;
    }

    public function login()
    {
        $code = request('code');
        if (empty($code)) {
            return $this->failed('缺失code');
        }

        $miniProgram = Factory::miniProgram();

        $result = $miniProgram->auth->session($code);

        if (!isset($result['openid'])) {
            return $this->failed('获取openid失败.');
        }

        $openid = $result['openid'];

        //1. openid 不存在相关用户和记录，直接返回 openid
        if (!$userBind = $this->userBindRepository->getByOpenId($openid)) {
            $userBind = $this->userBindRepository->create(['open_id' => $openid, 'type' => 'miniprogram',
                'app_id' => config('ibrand.wechat.mini_program.default.app_id'),]);

            return $this->success(['open_id' => $openid]);
        }

        //2. openid 不存在相关用户，直接返回 openid
        if (!$userBind->user_id) {
            return $this->success(['open_id' => $openid]);
        }

        //3. 绑定了用户,直接返回 token
        $user = $this->userRepository->find($userBind->user_id);

        $token = $user->createToken($user->id)->accessToken;

        return $this->success(['token_type' => 'Bearer', 'access_token' => $token]);
    }

    public function mobileLogin()
    {
        $miniProgram = Factory::miniProgram();

        //1. get session key.
        $code = request('code');
        $result = $miniProgram->auth->session($code);

        if (!isset($result['session_key'])) {
            return $this->failed('获取 session_key 失败.');
        }

        $sessionKey = $result['session_key'];

        //2. get phone number.
        $encryptedData = request('encryptedData');
        $iv = request('iv');

        $decryptedData = $miniProgram->encryptor->decryptData($sessionKey, $iv, $encryptedData);

        if (!isset($decryptedData['purePhoneNumber'])) {
            return $this->failed('获取手机号失败.');
        }

        $mobile = $decryptedData['purePhoneNumber'];

        //3. get or create user.
        if (!$user = $this->userRepository->getUserByCredentials(['mobile' => $mobile])) {
            $user = $this->userRepository->create(['mobile' => $mobile]);
        }

        $token = $user->createToken($user->id)->accessToken;

        $this->userService->bindPlatform($user->id, request('open_id'), config('ibrand.wechat.mini_program.default.app_id'), 'miniprogram');

        return $this->success(['token_type' => 'Bearer', 'access_token' => $token]);
    }

    public function getOpenIdByCode()
    {
        $code = request('code');
        if (empty($code)) {
            return $this->failed('缺失code');
        }

        $miniProgram = Factory::miniProgram();

        $result = $miniProgram->auth->session($code);

        if (!isset($result['openid'])) {
            return $this->failed('获取openid失败.');
        }

        $openid = $result['openid'];

        return $this->success(compact('openid'));
    }
}
