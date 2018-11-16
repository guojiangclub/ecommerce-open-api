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

use DB;
use ElementVip\Component\Balance\Repository\BalanceRepository;
use ElementVip\Component\Point\Repository\PointRepository;
use ElementVip\Component\User\Repository\UserRepository;
use iBrand\EC\Open\Backend\Member\Repository\CardRepository;
use iBrand\EC\Open\Backend\Member\Repository\MemberCardRepository;
use iBrand\EC\Open\Backend\Member\Repository\MemberWxCardRepository;
use iBrand\EC\Open\Backend\Member\Utils\Http;
use ElementVip\Store\Backend\Repositories\CategoryRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class MemberCardController extends Controller
{
    protected $cardRepository;
    protected $categoryRepository;
    protected $memberCardRepository;
    protected $memberWxCardRepository;
    protected $pointRepository;
    protected $userRepository;
    protected $balanceRepository;
    protected $activeUrl = 'http://m.dev.tnf.ibrand.cc/';

    public function __construct(CardRepository $cardRepository,
                                CategoryRepository $categoryRepository,
                                MemberCardRepository $memberCardRepository,
                                MemberWxCardRepository $memberWxCardRepository,
                                PointRepository $pointRepository,
                                UserRepository $userRepository,
                                BalanceRepository $balanceRepository)
    {
        $this->cardRepository = $cardRepository;
        $this->categoryRepository = $categoryRepository;
        $this->memberCardRepository = $memberCardRepository;
        $this->memberWxCardRepository = $memberWxCardRepository;
        $this->pointRepository = $pointRepository;
        $this->userRepository = $userRepository;
        $this->balanceRepository = $balanceRepository;
    }

    /**
     * 会员卡首页.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $where = [];
        if (!empty(request('name'))) {
            $where['name'] = ['like', '%'.request('name').'%'];
        }

        $count = DB::table('el_member_card')->count();
        $cardList = $this->memberCardRepository->getCardsPaginated($where);

        //return view('member-backend::member_card.index', compact('cardList', 'count'));

        return LaravelAdmin::content(function (Content $content) use ($cardList,$count) {
            $content->header('会员卡列表');

            $content->breadcrumb(
                ['text' => '会员卡列表', 'url' => 'member/card', 'no-pjax' => 1,'left-menu-active'=>'会员卡']
            );

            $content->body(view('member-backend::member_card.index', compact('cardList', 'count')));
        });
    }

    /**
     * 添加会员卡
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $colors = config('wx_card.colors');

        //return view('member-backend::member_card.create', compact('colors'));

        return LaravelAdmin::content(function (Content $content) use ($colors) {
            $content->header('新建会员卡');

            $content->breadcrumb(
                ['text' => '会员卡列表', 'url' => 'member/card', 'no-pjax' => 1],
                ['text' => '新建会员卡', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'会员卡']
            );

            $content->body(view('member-backend::member_card.create', compact('colors')));
        });
    }

    /**
     * 修改会员卡信息.
     *
     * @param int $id 会员卡id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id)
    {
        $card = $this->memberCardRepository->find($id);
        $colors = config('wx_card.colors');
        $files_num = '';
        $items = '';

        !$card->custom_field1_name && $files_num .= '1';
        !$card->custom_field2_name && $files_num .= ',2';
        !$card->custom_field3_name && $files_num .= ',3';
        !$card->custom_cell1_name && $items .= "{'name': 'custom_cell1', 'key': 1}";
        !$card->custom_url_name && $items .= ", {'name': 'custom_url', 'key': 2}";
        !$card->promotion_url_name && $items .= ", {'name': 'promotion_url', 'key': 3}";

        $files_num = trim($files_num, ',');
        $items = trim($items, ',');

        //return view('member-backend::member_card.edit', compact('card', 'colors', 'files_num', 'items'));

        return LaravelAdmin::content(function (Content $content) use ($card,$colors,$files_num,$items) {
            $content->header('编辑会员卡');

            $content->breadcrumb(
                ['text' => '会员卡列表', 'url' => 'member/card', 'no-pjax' => 1],
                ['text' => '编辑会员卡', 'url' => '', 'no-pjax' => 1,'left-menu-active'=>'会员卡']
            );

            $content->body(view('member-backend::member_card.edit', compact('card', 'colors', 'files_num', 'items')));
        });
    }

    /**
     * 添加|修改 会员卡信息.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $id = request('id');
        if (!$id) {
            $count = DB::table('el_member_card')->count();
            if ($count >= 1) {
                return $this->ajaxJson(false, [], 500, '只能添加一个卡套');
            }
        }

        $input = $request->except(['card_cover_tmp', 'file', '_token']);
        $validator = Validator::make($input, [
            'store_name' => 'required',
            'store_logo' => 'required',
            'bg_color' => 'required_if:card_cover,1',
            'bg_img' => 'required_if:card_cover,2',
            'name' => $id ? 'required|unique:el_member_card,name,'.$id : 'required|unique:el_member_card,name',
            'privilege_desc' => 'required|max:255',
            //'upgrade_amount' => 'required|numeric|amountValidate:' . $input['grade'],
            //'period'         => 'required|numeric|min:365',
            //'grade'          => request('id') ? 'required|unique:el_member_card,grade,' . request('id') : 'required|unique:el_member_card,grade',
            'center_url' => 'required_with:center_title',
            'custom_cell1' => 'required_with:custom_cell1_name',
            'custom_url' => 'required_with:custom_url_name',
            'promotion_url' => 'required_with:promotion_url_name',
        ], [
            'store_name.required' => '请填写商家名称',
            'store_logo.required' => '请上传商家logo',
            'bg_color.required_if' => '请选择背景颜色',
            'bg_img.required_if' => '请上传背景图片',
            'name.required' => '会员卡名称必须填写',
            'privilege_desc.required' => '特权说明必须填写',
            //'upgrade_amount.required' => '消费升级金额必须填写',
            //'grade.required'          => '会员卡等级必须填写',
            'name.unique' => '会员卡名称已存在',
            'privilege_desc.size' => '特权说明最多填写255个字符',
            //'upgrade_amount.numeric'  => '消费升级金额必须为数字',
            //'period.required'         => '等级计算周期必须填写',
            //'period.numeric'          => '等级计算周期必须为数字',
            //'period.min'              => '等级计算周期必须大于等于365天',
            //'grade.unique'            => '会员卡等级已存在',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $code = 500;
            $message = $errors;

            return $this->ajaxJson(false, $errors, $code, $message);
        }

        if (isset($input['custom_field1']) && !empty($input['custom_field1'])) {
            $input['custom_field1_name'] = $input['custom_field1']['name'];
            $input['custom_field1_url'] = $input['custom_field1']['url'];
            unset($input['custom_field1']);
        } else {
            $input['custom_field1_name'] = '';
            $input['custom_field1_url'] = '';
        }

        if (isset($input['custom_field2']) && !empty($input['custom_field2'])) {
            $input['custom_field2_name'] = $input['custom_field2']['name'];
            $input['custom_field2_url'] = $input['custom_field2']['url'];
            unset($input['custom_field2']);
        } else {
            $input['custom_field2_name'] = '';
            $input['custom_field2_url'] = '';
        }

        if (isset($input['custom_field3']) && !empty($input['custom_field3'])) {
            $input['custom_field3_name'] = $input['custom_field3']['name'];
            $input['custom_field3_url'] = $input['custom_field3']['url'];
            unset($input['custom_field3']);
        } else {
            $input['custom_field3_name'] = '';
            $input['custom_field3_url'] = '';
        }

        if (!isset($input['center_title'])) {
            $input['center_title'] = '';
            $input['center_url'] = '';
        }

        if (!isset($input['custom_cell1_name'])) {
            $input['custom_cell1_name'] = '';
            $input['custom_cell1'] = '';
            $input['custom_cell1_tips'] = '';
        }

        if (!isset($input['custom_url_name'])) {
            $input['custom_url_name'] = '';
            $input['custom_url'] = '';
            $input['custom_url_tips'] = '';
        }

        if (!isset($input['promotion_url_name'])) {
            $input['promotion_url_name'] = '';
            $input['promotion_url'] = '';
            $input['promotion_url_tips'] = '';
        }

        if ($id) {
            $last_update = $this->memberCardRepository->find(request('id'), ['updated_at']);
            Storage::put('we_card_update.txt', strtotime($last_update->updated_at));
            $res = $this->memberCardRepository->update($input, request('id'));
            $code = $res->id ? 200 : 500;
            $message = $res->id ? '修改成功' : '修改失败';
        } else {
            $res = $this->memberCardRepository->create($input);
            Storage::put('we_card_update.txt', 1);
            $code = $res->id ? 200 : 500;
            $message = $res->id ? '添加成功' : '添加失败';
        }

        return $this->ajaxJson(true, [], $code, $message);
    }

    /**
     * 修改会员卡状态
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status()
    {
        $model = $this->memberCardRepository->find(request()->modelId);
        $model->update(['status' => request()->status]);

        return response()->json(['status' => true]);
    }

    public function delMemberCard()
    {
        $id = request('id');
        $memberCard = $this->memberCardRepository->find($id);
        $file_path = base_path('public').str_replace(env('APP_URL'), '', $memberCard->bg_img);
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $memberCard->forceDelete();

        return $this->ajaxJson(true, [], 200, '删除成功');
    }

    /**
     * 创建微信会员卡
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wxCard()
    {
        $apiUrl = env('APP_URL').config('wx_card.create_wx_card_url');
        $apiUpdateUrl = env('APP_URL').config('wx_card.create_wx_card_update_url');
        $card = $this->memberCardRepository->getLowestGrade();
        if (!$card) {
            return $this->ajaxJson(false, '', 500, '没有可同步的微信卡卷');
        }

        $last_update_time = Storage::get('we_card_update.txt');
        if (strtotime($card['updated_at']) <= $last_update_time) {
            return $this->ajaxJson(false, '', 500, '没有修改卡卷内容,无法同步');
        }

        $params = [
            'base_info' => [
                'logo_url' => $card['store_logo_wx'], //卡券的商户logo
                'brand_name' => $card['store_name'], //商户名字
                'code_type' => $card['code_type'],
                'title' => $card['name'], //卡券名
                'color' => $card['bg_color_name'], //卡券的背景颜色
                'notice' => $card['use_notice'], //使用提醒
                'service_phone' => $card['service_phone'],
                'description' => $card['use_description'], //使用说明
                'date_info' => [ //使用日期
                                               'type' => 'DATE_TYPE_PERMANENT',
                ],
                'sku' => [
                    'quantity' => '50000000',
                ],
                'get_limit' => 1,
                'center_title' => $card['center_title'],
                'center_url' => $card['center_url'],
                'custom_url_name' => $card['custom_url_name'],
                'custom_url' => $card['custom_url'],
                'custom_url_sub_title' => $card['custom_url_tips'],
                'promotion_url_name' => $card['promotion_url_name'],
                'promotion_url' => $card['promotion_url'],
                'promotion_url_sub_title' => $card['promotion_url_tips'],
                //'need_push_on_view'       => true,
            ],
            'especial' => [
                'background_pic_url' => 2 == $card['card_cover'] ? $card['bg_img_wx'] : '',
                'custom_field1' => [
                    'name' => $card['custom_field1_name'],
                    'url' => $card['custom_field1_url'],
                ],
                'custom_field2' => [
                    'name' => $card['custom_field2_name'],
                    'url' => $card['custom_field2_url'],
                ],
                'custom_field3' => [
                    'name' => $card['custom_field3_name'],
                    'url' => $card['custom_field3_url'],
                ],
                'custom_cell1' => [
                    'name' => $card['custom_cell1_name'],
                    'tips' => $card['custom_cell1_tips'],
                    'url' => $card['custom_cell1'],
                ],
                'prerogative' => $card['privilege_desc'], //特权说明
                'activate_url' => env('APP_URL').'/api/member/wxcard/redirect?type=wechat',
            ],
        ];

        $status = false;
        $code = 500;
        $wx_card = $this->memberWxCardRepository->searchWxCard($card['id']);
        if (!$wx_card) {
            $res = Http::request($apiUrl, 'POST', $params);
            $message = '创建失败';
            if ($res['status'] && 200 == $res['code']) {
                Storage::put('we_card_update.txt', strtotime($card['updated_at']));
                $status = $res['status'];
                $code = $res['code'];
                $message = '创建成功';
                $qrCode = $this->downLoadWxQrCode($res['data']['card_id']);
                $data = array_merge(['el_member_card_id' => $card['id'], 'card_id' => $res['data']['card_id']], $qrCode);
                $this->memberWxCardRepository->create($data);
            }
        } else {
            unset($params['base_info']['sku']);
            unset($params['base_info']['brand_name']);
            $params['card_id'] = $wx_card->card_id;
            $res = Http::request($apiUpdateUrl, 'POST', $params);
            $message = '修改失败';
            if ($res['status'] && 200 == $res['code']) {
                Storage::put('we_card_update.txt', strtotime($card['updated_at']));
                $status = $res['status'];
                $code = $res['code'];
                $message = '修改成功';
            }
        }

        return $this->ajaxJson($status, '', $code, $message);
    }

    /**
     * 测试.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wxCardActivate()
    {
        $openid = 'ooGxkwg71K_LhVXrYYpmvD2mr23Q';
        $card_id = 'poGxkwhgbi46FjGwPWcHouk_owFw';
        $message = '激活失败';
        $status = false;
        $code = 500;

        $apiCodeUrl = env('APP_URL').config('wx_card.create_wx_card_code_url');
        $apiActivateUrl = env('APP_URL').config('wx_card.create_wx_card_activate_url');

        $codeResponse = Http::request($apiCodeUrl, 'POST', ['card_id' => $card_id, 'open_id' => $openid]);
        if ($codeResponse['status'] && 200 == $codeResponse['code']) {
            $code = $codeResponse['data']['code'];
            $activateResponse = Http::request($apiActivateUrl, 'POST', [
                'card_id' => $card_id,
                'code' => $code,
                'membership_number' => $code,
            ]);

            if ($activateResponse['status'] && 0 == $activateResponse['data']['errcode'] && 'ok' == $activateResponse['data']['errmsg']) {
                $message = '激活成功';
                $status = true;
                $code = 200;
            }
        }

        return $this->ajaxJson($status, $message, $code, $message);
    }

    /**
     * 微信卡卷二维码
     *
     * @param string $card_id 微信卡卷id
     */
    public function downLoadWxQrCode($card_id)
    {
        $url = env('APP_URL').config('wx_card.create_wx_card_qrcode_url');
        $card = ['card_id' => $card_id];
        $data = [];
        $res = Http::request($url, 'POST', $card);
        if ($res['status'] && 200 == $res['code']) {
            $data = [
                'ticket' => $res['data']['ticket'],
                'url' => $res['data']['url'],
                'show_qrcode_url' => $res['data']['show_qrcode_url'],
            ];
        }

        return $data;
    }

    /**
     * 更新用户等级.
     *
     * @param object $user   ORM对象
     * @param int    $amount 消费金额
     * @param bool   $sync
     */
    public function updateUserLevel($user, $amount = 0, $sync = false)
    {
        if (!$sync) {
            $user->current_exp = $amount;
            $user->save();
        }

        $groups = $this->memberCardRepository->getCardsPaginated([], 0);
        $min_group = end($groups);
        $max_group = $groups[0];
        if ($amount <= $min_group['upgrade_amount']) {
            $user->group_id = $min_group['grade'];
        }

        if ($amount >= $max_group['upgrade_amount']) {
            $user->group_id = $max_group['grade'];
        }

        if ($amount > $min_group['upgrade_amount'] && $amount < $max_group['upgrade_amount'] && count($groups) >= 3) {
            $groups_tmp = $groups;
            array_shift($groups_tmp);
            array_pop($groups_tmp);
            foreach ($groups_tmp as $group) {
                if ($amount >= $group['upgrade_amount']) {
                    $user->group_id = $group['grade'];
                    break;
                }
            }
        }

        $user->save();
    }

    /**
     * 获取卡卷用户显示数据.
     *
     * @param      $user_id
     * @param null $type
     *
     * @return array
     */
    public function userData($user_id, $type = null)
    {
        $pointValid = $this->pointRepository->getSumPointValid($user_id, $type);
        $userGroup = $this->userRepository->with('group')->find($user_id);
        $amountCount = $this->balanceRepository->getSum($user_id);

        return [
            'pointValid' => $pointValid,
            'userGroup' => $userGroup->group->grade,
            'amountCount' => $amountCount,
        ];
    }
}
