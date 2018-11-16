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

use Carbon\Carbon;
use DB;
use ElementVip\Component\User\Models\ElGroup;
use ElementVip\Component\User\Models\Role;
use ElementVip\Component\User\Models\User;
use iBrand\EC\Open\Backend\Member\Models\Message;
use iBrand\EC\Open\Backend\Member\Models\MessageUser;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Validator;

class MessageController extends Controller
{
    public function index()
    {
        //return view('member-backend::message.index');

        return LaravelAdmin::content(function (Content $content) {
            $content->header('系统消息管理');

            $content->breadcrumb(
                ['text' => '系统消息管理', 'url' => 'member/message', 'no-pjax' => 1,'left-menu-active'=>'消息管理']
            );

            $content->body(view('member-backend::message.index'));
        });
    }

    public function create()
    {
        $roles = Role::all();
        $groups = ElGroup::all();

        //eturn view('member-backend::message.create', compact('roles', 'groups'));

        return LaravelAdmin::content(function (Content $content) use ($roles,$groups) {
            $content->header('发送消息');

            $content->breadcrumb(
                ['text' => '系统消息管理', 'url' => 'member/message', 'no-pjax' => 1],
                ['text' => '发送消息', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'消息管理']
            );

            $content->body(view('member-backend::message.create', compact('roles', 'groups')));
        });
    }

    public function store()
    {
        $validator = $this->validateForm();
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $show_warning = $warnings->first();

            return response()->json(['status' => false, 'error_code' => 0, 'error' => $show_warning,
            ]);
        }

        $group_type = request('group_type');

        $data = [
            'send_id' => 1,
            'send_model' => 'ElementVip\Backend\Models\Admin',
            'type' => 'global',
            'group_type' => request('group_type'),
            'content' => request('content'),
            'post_time' => Carbon::now(),
        ];

        $extra = [];
        if ('users' == $group_type) {
            $extra = explode("\n", request('users'));
            if (count($extra) > 50) {
                return $this->ajaxJson(false, [], 404, '指定用户数量不能超过50个');
            }
            $data['extra'] = $extra;
        }

        if ('roles' == $group_type) {
            $data['group_id'] = request('roles');
        } elseif ('groups' == $group_type) {
            $data['group_id'] = request('groups');
        }

        try {
            DB::beginTransaction();
            $message = Message::create($data);

            if ('users' == $group_type) {
                foreach ($extra as $item) {
                    if ($user = User::where('mobile', $item)->orWhere('email', $item)->first()) {
                        MessageUser::create([
                            'user_id' => $user->id,
                            'message_id' => $message->id,
                            'user_type' => 'ElementVip\Component\User\Models\User',
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception);

            return $this->ajaxJson(false, [], 404, '保存失败');
        }
    }

    protected function validateForm()
    {
        $rules = [
            'content' => 'required',
        ];
        $message = [
            'required' => ':attribute 不能为空',
            'integer' => ':attribute 必须是整数',
        ];

        $attributes = [
            'content' => '消息内容',
            'users' => '指定用户',
            'roles' => '指定角色',
            'groups' => '指定分组',
        ];

        $validator = Validator::make(request()->all(), $rules, $message, $attributes);

        $validator->sometimes('users', 'required', function ($input) {
            return 'users' == $input->group_type;
        });

        $validator->sometimes('roles', 'required', function ($input) {
            return 'roles' == $input->group_type;
        });

        $validator->sometimes('groups', 'required', function ($input) {
            return 'groups' == $input->group_type;
        });

        return $validator;
    }
}
