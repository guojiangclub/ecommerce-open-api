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

use Carbon\Carbon;

use GuoJiangClub\Component\Point\Models\Point;
use GuoJiangClub\Component\Point\Repository\PointRepository;
use GuoJiangClub\EC\Open\Backend\Member\Models\User;

use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Excel;
use GuoJiangClub\EC\Open\Backend\Member\Repository\UserRepository;
use Illuminate\Http\Request;

class PointController extends Controller
{
    protected $userRepository;
    protected $pointRepository;
    protected $cache;

    public function __construct(
        UserRepository $userRepository,
        PointRepository $pointRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->pointRepository = $pointRepository;
        $this->cache = cache();
    }

    /**会员积分管理
     * @return mixed
     */
    public function index()
    {
        $points = $this->getPointData();

        return LaravelAdmin::content(function (Content $content) use ($points) {
            $content->header('会员积分管理');

            $content->breadcrumb(
                ['text' => '会员积分管理', 'url' => 'member/points', 'no-pjax' => 1],
                ['text' => '积分记录', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '会员积分记录']
            );

            $content->body(view('member-backend::point.index', compact('points')));
        });
    }


    protected function getPointData($where = [])
    {
        $time = [];
        $userWhere = [];

        if (!empty(request('mobile'))) {
            $userWhere['mobile'] = request('mobile');
        }

        if (count($userWhere) > 0) {

            $user = $this->userRepository->searchUserPaginated($userWhere, 0)->pluck('id')->toArray();

            $where['user_id'] = $user;
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


        return $this->getPointsByConditions($where, 15, $time);
    }

    protected function getPointsByConditions($where, $limit = 15, $time = [])
    {
        $data = Point::where(function ($query) use ($where, $time) {
            if (is_array($where) && count($where)) {
                foreach ($where as $key => $value) {
                    if ('user_id' == $key) {
                        $query = $query->whereIn('user_id', $value);
                    } elseif (is_array($value)) {
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
        })->with(['user', 'point_order', 'point_order_item'])->orderBy('created_at', 'desc')->paginate($limit);

        return $data;
    }

    public function importPointModal()
    {
        return view('member-backend::point.includes.import');
    }

    /**
     * 计算导入的数据数量.
     *
     * @param $path
     */
    public function getImportDataCount()
    {
        $filename = 'public' . request('path');

        Excel::load($filename, function ($reader) {
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $count = count($reader->toArray()) - 1;
            $expiresAt = Carbon::now()->addMinutes(30);
            $this->cache->forget('userPointImportCount');
            $this->cache->put('userPointImportCount', $count, $expiresAt);
        });

        $limit = 100;
        $total = ceil($this->cache->get('userPointImportCount') / $limit);
        $url = route('admin.member.points.saveImportData', ['total' => $total, 'limit' => $limit, 'path' => request('path')]);

        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url]);
    }

    /**
     * 执行导入操作.
     *
     * @return mixed
     */
    public function saveImportData()
    {
        $filename = 'public' . request('path');
        $conditions = [];
        $page = request('page') ? request('page') : 1;
        $total = request('total');
        $limit = request('limit');

        if ($page > $total) {
            return $this->ajaxJson(true, ['status' => 'complete']);
        }

        Excel::load($filename, function ($reader) use ($conditions, $page, $limit) {
            $data = $reader->get()->first()->forPage($page, $limit)->toArray();
            if (count($data) > 0) {
                foreach ($data as $key => $value) {
                    if (!empty($value['mobile']) and $user = User::where('mobile', $value['mobile'])->first()) {
                        Point::create([
                            'user_id' => $user->id,
                            'action' => 'admin_import_action',
                            'note' => $value['note'],
                            'value' => $value['value'],
                            'status' => 1]);
                    }
                }
            }
        });

        $url = route('admin.member.points.saveImportData', ['page' => $page + 1, 'total' => $total, 'limit' => $limit, 'path' => request('path')]);

        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url, 'current_page' => $page, 'total' => $total]);
    }
}
