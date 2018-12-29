<?php

namespace iBrand\Component\User\Test;

use iBrand\Component\User\Models\User;
use iBrand\Component\User\Models\UserBind;

class UserTest extends BaseTest
{
	public function testGetUserByCredentials()
	{
		$where['mobile'] = '18684738715';

		$user = $this->user->getUserByCredentials($where);

		$this->assertEquals($user->count(), 1);
		$this->assertSame($user->mobile, $where['mobile']);

		$appId    = 'wx49f648b10ef7f110b';
		$userBind = $this->userBind->getByUserIdAndAppId($user->id, $appId);

		$this->assertSame($userBind->count(), 1);
	}

	public function testGetByOpenId()
	{
		$openid = 'ovNizjmZxfWoVZ28vLdElWi8YsAw';

		$userBind = $this->userBind->getByOpenId($openid);

		$this->assertSame($userBind->count(), 1);
	}

	public function testByOpenIdAndType()
	{
		$openid = 'ovNizjmZxfWoVZ28vLdElWi8YsAw';

		$userBind = UserBind::ByOpenIdAndType($openid, UserBind::TYPE_WECHAT)->first();
		$this->assertSame($userBind->count(), 1);
	}

	public function testByUserIdAndType()
	{
		$user = User::find(1);

		$userBind = UserBind::ByUserIdAndType($user->id, UserBind::TYPE_WECHAT)->first();
		$this->assertSame($userBind->count(), 1);
	}

	public function testByAppID()
	{
		$user  = User::find(1);
		$appId = 'wx49f648b10ef7f110b';

		$userBind = UserBind::ByAppID($user->id, UserBind::TYPE_WECHAT, $appId)->first();
		$this->assertSame($userBind->count(), 1);
	}

	public function testBindUser()
	{
		$user = User::find(1);
		$user->delete();
		$user = User::create([
			'name'      => 'tangqi',
			'nick_name' => '承诺',
			'email'     => '923332947@qq.com',
			'mobile'    => '18684738715',
			'password'  => '123456',
			'status'    => '1',
			'sex'       => '男',
			'avatar'    => 'https://avatars1.githubusercontent.com/u/11305589?s=400&u=e05d3607f4347e3b739a995ea3b9d2374476627d&v=4',
			'city'      => '长沙市',
		]);

		$openid = 'ovNizjmZxfWoVZ28vLdElWi8YsAw';
		$result = UserBind::bindUser($openid, UserBind::TYPE_WECHAT, $user->id);
		$this->assertEquals($result, 1);

		$result = $this->userBind->bindToUser($openid, $user->id);
		$this->assertEquals($result->toArray()['user_id'], $user->id);
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage This user bind model does not exist.
	 */
	public function testException()
	{
		$user = User::find(1);
		$user->delete();
		$user = User::create([
			'name'      => 'tangqi',
			'nick_name' => '承诺',
			'email'     => '923332947@qq.com',
			'mobile'    => '18684738715',
			'password'  => '123456',
			'status'    => '1',
			'sex'       => '男',
			'avatar'    => 'https://avatars1.githubusercontent.com/u/11305589?s=400&u=e05d3607f4347e3b739a995ea3b9d2374476627d&v=4',
			'city'      => '长沙市',
		]);

		$openid = 'ovNizjmZxfWoVZ28vLdElWi8aaaa';
		$this->userBind->bindToUser($openid, $user->id);
	}
}