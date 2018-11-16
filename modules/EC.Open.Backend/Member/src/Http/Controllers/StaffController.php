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

use iBrand\EC\Open\Backend\Member\Models\Staff;
use iBrand\EC\Open\Backend\Member\Repository\StaffRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Excel;
use Illuminate\Http\Request;
use Validator;

class StaffController extends Controller
{
    protected $staffRepository;

    protected static $num;

    protected static $abnormal;

    public function __construct(StaffRepository $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function index()
    {
        $where = [];
        if ('' != request('active_status')) {
            $where['active_status'] = request('active_status');
        }
        if ('' != request('locationType')) {
            $where['locationType'] = request('locationType');
        }

        $time = [];
        if ('' != request('activate_at')) {
            if ('activated' == request('activate_at')) {
                $where['activate_at'] = ['!=', null];
            } elseif ('unactivated' == request('activate_at')) {
                $where['activate_at'] = null;
            }
        }

        if (!empty(request('etime')) && !empty(request('stime'))) {
            $where['activate_at'] = ['<=', request('etime')];
            $time['activate_at'] = ['>=', request('stime')];
        }

        if (!empty(request('etime'))) {
            $time['activate_at'] = ['<=', request('etime')];
        }

        if (!empty(request('stime'))) {
            $time['activate_at'] = ['>=', request('stime')];
        }

        if ('' != request('value')) {
            $where['staff_id'] = ['like', '%'.request('value').'%'];
            $where['name'] = ['like', '%'.request('value').'%'];
            $where['email'] = ['like', '%'.request('value').'%'];
            $where['mobile'] = ['like', '%'.request('value').'%'];
        }
        $staff = $this->staffRepository->getRegistrationPaginated(15, $where, $time);

        //return view('member-backend::staff.index', compact('staff'));

        return LaravelAdmin::content(function (Content $content) use ($staff) {
            $content->header('员工管理');

            $content->breadcrumb(
                ['text' => '员工管理', 'url' => 'member/staff', 'no-pjax' => 1,'left-menu-active'=>'员工管理']
            );

            $content->body(view('member-backend::staff.index', compact('staff')));
        });
    }

    public function createStaff()
    {

        return LaravelAdmin::content(function (Content $content) {
            $content->header('员工管理');

            $content->breadcrumb(
                ['text' => '员工管理', 'url' => 'member/staff', 'no-pjax' => 1,'left-menu-active'=>'员工管理']
            );

            $content->body(view('member-backend::staff.create'));
        });

    }

    public function edit($id)
    {
        $staff = Staff::find($id);
        if (!$staff) {
            return redirect()->route('admin.staff.index')->withFlashSuccess('该员工已删除');
        }

        //return view('member-backend::staff.edit', compact('staff'));

        return LaravelAdmin::content(function (Content $content) use ($staff) {
            $content->header('员工管理');

            $content->breadcrumb(
                ['text' => '员工管理', 'url' => 'staff', 'no-pjax' => 1],
                ['text' => '修改员工信息', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'员工管理']
            );

            $content->body(view('member-backend::staff.edit', compact('staff')));
        });

    }

    public function store(Request $request)
    {
        $data = request()->except('type', '_token');
        $type = request('type');
        if (isset($type) && 1 == $type) {
            $validator = Validator::make($data, [
                'staff_id' => 'required|unique:el_staff,staff_id',
                'name' => 'required|unique:el_staff,name',
//                'email' => 'required|unique:el_staff,email',
                'mobile' => 'required|unique:el_staff,mobile',
                'hiredate_at' => 'required|date',
                'locationType' => 'required',
                'active_status' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->ajaxJson(false, [], 400, '信息不完整或存在重复的会员信息');
            }
            $this->staffRepository->create($data);
        }
    }

    public function update($id)
    {
        $input = request()->except('_token');
        $staff = Staff::find($id);
        if (!$staff) {
            return redirect()->route('admin.staff.index')->withFlashSuccess('该员工已删除');
        }

        $validator = Validator::make($input, [
            // 'email' => 'required',
            'mobile' => 'required',
            'hiredate_at' => 'required|date',
            'locationType' => 'required',
            'active_status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->ajaxJson(false, [], 400, '信息不完整');
        }

        $check = Staff::where(['mobile' => $input['mobile']])->first();

        if ($check) {
            if ($check->id !== intval($id)) {
                return $this->ajaxJson(false, [], 400, '该手机信息已经存在');
            }
        }

        $up_staff = $this->staffRepository->update($input, $id);

        return $this->ajaxJson();
    }

    public function staffImport()
    {
        return view('member-backend::staff.includes.import');
    }

    public function saveImport()
    {
        $data = [];
        $filename = 'public'.request('upload_excel');
        $error_list = [];
        Excel::load($filename, function ($reader) {
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $results = $reader->toArray();
            $mobile = [];
            $staff_id = [];
            $abnormal = [];
            $i = 0;
            foreach ($results as $key => $value) {
                if (0 != $key) {
                    $status = 2;
                    if ('在职' == $value[6] || 1 == $value[6]) {
                        $status = 1;
                    } elseif ('离职' == $value[6] || 0 == $value[6]) {
                        $status = 2;
                    }
                    $email = empty($value[2]) ? '' : $value[2];
                    $name = empty($value[1]) ? '' : $value[1];

                    if (!empty($value[0]) || !empty($value[1]) || !empty($value[2]) || !empty($value[3]) || !empty($value[4]) || !empty($value[5])) {
                        $input = [
                            'staff_id' => $value[0],
                            'name' => $name,
                            'email' => $email,
                            'hiredate_at' => $value[4],
                            'locationType' => $value[5],
                            'active_status' => $status,
                            'mobile' => $value[3],
                        ];

                        $saff = null;
                        if (!empty($value[3]) || !empty($value[0])) {
                            $saff = Staff::where('mobile', $value[3])->orWhere('staff_id', $value[0])->first();
                        }

                        if ($saff) {
                            if (intval($value[0]) === intval($saff->staff_id)) {
                                $abnormal[] = $key + 1;
                            }
                            if (intval($value[3]) === intval($saff->mobile)) {
                                $abnormal[] = $key + 1;
                            }

//                        $this->staffRepository->update($input,$saff->id);
                        } else {
                            try {
                                $this->staffRepository->create($input);
                                ++$i;
                            } catch (\Exception $e) {
                                $abnormal[] = $key + 1;
                            }
                        }
                    }
                }
            }

            self::$num = $i;
            self::$abnormal = $abnormal;
        });

        if (count(self::$abnormal)) {
            $abnormal = array_unique(self::$abnormal);
            $data['abnormal'] = '以下列号数据未导入:'.implode(' ', $abnormal);
        } else {
            $data['abnormal'] = '';
        }

        $data['num'] = empty(self::$num) ? '0' : self::$num;

        return $this->ajaxJson(true, $data, 200, '');
    }
}
