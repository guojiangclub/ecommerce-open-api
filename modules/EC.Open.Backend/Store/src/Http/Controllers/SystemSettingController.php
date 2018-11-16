<?php
/**
 * Created by PhpStorm.
 * User: eddy
 * Date: 2016/11/5
 * Time: 23:34
 */
namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use Illuminate\Http\Request;
use iBrand\Backend\Http\Controllers\Controller;
use Spatie\ResponseCache\ResponseCacheFacade as ResponseCache;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Storage;


class SystemSettingController extends Controller
{

    //生产状态设置
    public function index()
    {

        $produce = settings()->getSetting('produce_status_setting');

        return view('store-backend::setting.produce', compact('produce'));
    }

    public function saveProduce(Request $request)
    {
        $data = [];
        foreach ($request->input('produce') as $key => $value) {
            $data[$value['key']] = $value['value'];
        }

        settings()->setSetting(['produce_status_setting' => $data]);

        $this->ajaxJson();
    }

    //商城设置
    public function shopSetting()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('商城设置');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '商城设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '全局设置']

            );

            $content->body(view('store-backend::setting.shop'));
        });

//        return view('store-backend::setting.shop');
    }

    public function point()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('积分设置');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '积分设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '积分设置']

            );

            $content->body(view('store-backend::setting.point'));
        });

//        return view('store-backend::setting.point');
    }

    public function employee()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('员工内购设置');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '员工内购设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '员工内购']

            );

            $content->body(view('store-backend::setting.employee'));
        });
//        return view('store-backend::setting.employee');
    }

    public function saveSettings(Request $request)
    {
        $data = ($request->except('_token', 'file'));

        settings()->setSetting($data);

        $this->ajaxJson();
    }

    public function refundReason()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('售后设置');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '售后设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '售后设置']

            );

            $content->body(view('store-backend::setting.refund_reason'));
        });
//        return view('store-backend::setting.refund_reason');
    }

    public function siteSettings()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('站点设置');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '站点设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '商城设置']

            );

            $content->body(view('store-backend::setting.site_settings'));
        });

//        return view('store-backend::setting.site_settings');
    }

    public function saveSiteSettings(Request $request)
    {
        $data = array_filter($request->except('_token', 'file', 'upload_image'));
        settings()->setSetting($data);

        $this->ajaxJson();
    }

    public function clearCache()
    {
        ResponseCache::flush();
        $this->ajaxJson();
    }

    public function toolList()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('工具管理');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '小工具', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '工具管理']

            );

            $content->body(view('store-backend::setting.toolList'));
        });

//        return view('store-backend::setting.toolList');
    }

    public function priceProtection()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('价格保护');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '价格保护设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '价格保护']

            );

            $content->body(view('store-backend::setting.price-protection'));
        });
//        return view('store-backend::setting.price-protection');
    }

    public function invoice()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('发票设置');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/shopSetting', 'no-pjax' => 1],
                ['text' => '发票设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '发票设置']

            );

            $content->body(view('store-backend::setting.invoice'));
        });
//        return view('store-backend::setting.invoice');
    }

    public function saveInvoiceSettings(Request $request)
    {
        $data = $request->except('_token');

        if (!isset($data['invoice_type'])) {
            $data['invoice_type'] = '';
        } else {
            $data['invoice_type'] = array_values(array_filter($data['invoice_type']));
        }

        if (!isset($data['invoice_content'])) {
            $data['invoice_content'] = '';
        } else {
            $data['invoice_content'] = array_values(array_filter($data['invoice_content']));
        }

        settings()->setSetting($data);

        $this->ajaxJson();
    }

    /**
     * 保存退换货理由
     * @param Request $request
     */
    public function saveRefundSettings(Request $request)
    {
        $data = $request->except('_token');

        if (!isset($data['order_refund_reason'])) {
            return $this->ajaxJson(false, [], 400, '退换货理由不能为空');
        }

        $reason = $data['order_refund_reason'];

        foreach ($reason as $item) {
            if (!$item['key'] OR !$item['value']) {
                return $this->ajaxJson(false, [], 400, 'key值或者理由不能为空');
            }
        }

        $array = array_map('array_shift', $reason);
        if (count($array) != count(array_unique($array))) {
            return $this->ajaxJson(false, [], 400, '存在重复的key值');
        }

        settings()->setSetting($data);

        return $this->ajaxJson();
    }

    /**
     * 客服设置
     */
    public function onlineService()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('客服设置');

            $content->breadcrumb(
                ['text' => '商城设置', 'url' => 'store/setting/onlineService', 'no-pjax' => 1],
                ['text' => '客服设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '客服设置']

            );

            $content->body(view('store-backend::setting.online_service'));
        });

        /*return view('store-backend::setting.online_service');*/
    }

    public function saveOnlineService(Request $request)
    {
        $data = $request->except(['_token', 'file']);

        if ($data['online_service_status']) {
            if (!isset($data['online_service_type'])) {
                return $this->ajaxJson(false, [], 400, '请选择客服设置类型');
            }
            if ($data['online_service_type'] == 'self') {
                foreach ($data['online_service_self'] as $item) {
                    if (!$item) {
                        return $this->ajaxJson(false, [], 400, '请完善自有客服信息');
                    }
                }
            }

            if ($data['online_service_type'] == 'platform' AND !$data['online_service_url']) {
                return $this->ajaxJson(false, [], 400, '请填写云客服URL');
            }
        }


        settings()->setSetting($data);
        return $this->ajaxJson();
    }
}