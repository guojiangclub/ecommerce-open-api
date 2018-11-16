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

use iBrand\EC\Open\Backend\Member\Models\Balance;
use iBrand\EC\Open\Backend\Member\Models\User;
use ElementVip\Store\Backend\Repositories\UserRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Excel;

class BalanceController extends Controller
{
    protected $userRepository;

    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $balances = $this->getBalanceData();

        //return view('member-backend::balance.index', compact('balances'));

        return LaravelAdmin::content(function (Content $content) use ($balances) {
            $content->header('会员余额管理');

            $content->breadcrumb(
                ['text' => '会员余额管理', 'url' => 'member/balances', 'no-pjax' => 1],
                ['text' => '会员余额记录', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '会员余额记录']
            );

            $content->body(view('member-backend::balance.index', compact('balances')));
        });
    }

    public function getBalancePaginate($user_id)
    {
        $list = Balance::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(10);

        return $this->ajaxJson(true, $list);
    }

    public function operateBalance(Request $request)
    {
        $data = $request->except('_token');
        if (!is_numeric($data['value'])) {
            return $this->ajaxJson(false, [], 500, '余额值必须是数字');
        }

        $sum = Balance::sumByUser($data['user_id']);
        if (!is_numeric($sum)) {
            $sum = 0;
        } else {
            $sum = (int)$sum;
        }

        $data['current_balance'] = $sum + ($data['value'] * 100);
        Balance::create($data);

        event('member.balance.changed', [$data]);

        return $this->ajaxJson();
    }

    protected function getBalanceData($type = '', $where = [])
    {
        $time = [];
        $userWhere = [];
        if (!empty(request('name'))) {
            $userWhere['name'] = request('name');
        }
        if (!empty(request('mobile'))) {
            $userWhere['mobile'] = request('mobile');
        }
        if (count($userWhere) > 0) {
            $user = $this->userRepository->searchUserPaginated($userWhere)->toArray();
            $where['user_id'] = count($user['data']) > 0 ? $user['data'][0]['id'] : '';
        }

        if (!empty(request('etime'))) {
            $time['created_at'] = ['<=', request('etime')];
        }

        if (!empty(request('stime'))) {
            $time['created_at'] = ['>=', request('stime')];
        }

        if (!empty(request('etime')) && !empty(request('stime'))) {
            $where['created_at'] = ['<=', request('etime')];
            $time['created_at'] = ['>=', request('stime')];
        }

        return $this->getBalancesByConditions($where, 15, $time);
    }

    protected function getBalancesByConditions($where, $limit = 15, $time = [])
    {
        $data = Balance::where(function ($query) use ($where, $time) {
            if (is_array($where) && count($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            if (is_array($time)) {
                foreach ($time as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }
        })->with('user')->orderBy('created_at', 'desc')->paginate($limit);

        return $data;
    }

    /**
     * 导入余额.
     *
     * @return mixed
     */
    public function importBalance()
    {
        return view('member-backend::balance.includes.import-balance');
    }

    public function saveBalanceImport()
    {
        $data = [];
        $filename = 'public' . request('upload_excel');
        Excel::load($filename, function ($reader) {
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $results = $reader->toArray();

            foreach ($results as $key => $value) {
                if (0 != $key) {
                    $account = trim($value[0]);
                    $amount = trim($value[1]);
                    $note = trim($value[2]);

                    if (!$account OR !$amount) continue;

                    if (!$account AND !$amount AND !$note) break;

                    if (isMobile($account)) {
                        $user = User::where('mobile', $account)->first();
                    } else {
                        $user = User::where('name', $account)->first();
                    }

                    if (!$user) continue;

                    $sum = Balance::sumByUser($user->id);
                    if (!is_numeric($sum)) {
                        $sum = 0;
                    } else {
                        $sum = (int)$sum;
                    }

                    $current_balance = $sum + ($amount * 100);

                    Balance::create([
                        'user_id' => $user->id,
                        'type' => 'import',
                        'note' => $note,
                        'value' => $amount,
                        'current_balance' => $current_balance
                    ]);
                }
            }
        });

        return $this->ajaxJson(true, $data, 200, '');
    }
}
