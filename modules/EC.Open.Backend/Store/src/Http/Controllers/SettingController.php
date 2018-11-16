<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-11-03
 * Time: 21:42
 */

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Storage;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class SettingController extends Controller
{
    public function index()
    {
        return view('backend::settings.layout');
    }

    public function pay()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('支付设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '支付设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '支付设置']

            );

            $content->body(view('store-backend::sys_setting.ibrand-pay'));
        });

    }

    public function savePay()
    {
        //1. 保存配置进入到数据库
        $data = request()->except('_token', 'file', 'ibrand_alipay_private_key', 'ibrand_wechat_pay_apiclient_cert', 'ibrand_wechat_pay_apiclient_key');

        settings()->setSetting($data);

        if (!empty(request('ibrand_alipay_private_key'))) {
            Storage::disk('share')->put('ibrand_alipay_private_key.pem', request('ibrand_alipay_private_key'));
        }


        if (!empty(request('ibrand_wechat_pay_apiclient_key'))) {
            Storage::disk('share')->put('ibrand_wechat_pay_apiclient_key.pem', request('ibrand_wechat_pay_apiclient_key'));
        }

        if (!empty(request('ibrand_wechat_pay_apiclient_cert'))) {
            Storage::disk('share')->put('ibrand_wechat_pay_apiclient_cert.pem', request('ibrand_wechat_pay_apiclient_cert'));
        }

        $this->ajaxJson();
    }

    public function pingxxPay()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('Pingxx支付设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => 'Pingxx支付设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '支付设置']

            );

            $content->body(view('store-backend::sys_setting.pay'));
        });
    }

    public function savePingxxPay()
    {
        //1. 保存配置进入到数据库
        $data = request()->except('_token', 'file', 'pingxx_rsa_private_key');

        settings()->setSetting($data);

        if (!empty(request('pingxx_rsa_private_key'))) {
            Storage::disk('share')->put('rsa_private_key.pem', request('pingxx_rsa_private_key'));
        }

        $this->ajaxJson();
    }

    public function sms()
    {
        $smsSetting = settings('laravel-sms');

        $phpSms = settings('phpsms');
        return LaravelAdmin::content(function (Content $content) use ($smsSetting, $phpSms) {

            $content->header('系统设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '后台设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '后台设置']

            );

            $content->body(view('store-backend::sys_setting.sms', compact('smsSetting', 'phpSms')));
        });
    }

    public function backend()
    {
        $dmpConfig = settings('dmp_config');

        if (!is_array($dmpConfig)) {
            $dmpConfig = [];
        }

        if (!isset($dmpConfig['setting-cache'])) {
            $dmpConfig['setting-cache'] = false;
        }

        return LaravelAdmin::content(function (Content $content) use ($dmpConfig) {

            $content->header('短信设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '短信设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '短信设置']

            );

            $content->body(view('store-backend::sys_setting.backend', compact('dmpConfig')));
        });

//        return view('backend::settings.backend', compact('dmpConfig'));
    }

    public function sentry()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('系统设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '网站统计设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '网站统计设置']

            );

            $content->body(view('store-backend::sys_setting.sentry'));
        });

//        return view('backend::settings.sentry');
    }

    public function setCustomPackage()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('系统设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '客户私有包', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '客户私有包']

            );

            $content->body(view('store-backend::sys_setting.custom-package'));
        });

//        return view('backend::settings.custom-package');
    }

    public function saveSettings(Request $request)
    {
        $data = $request->except('_token', 'file');

        settings()->setSetting($data);

        $this->ajaxJson();
    }

    public function analytics()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('网站统计设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '网站统计设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '网站统计设置']

            );

            $content->body(view('store-backend::sys_setting.analytics'));
        });

//        return view('backend::settings.analytics');
    }

    public function encryption()
    {
        return view('backend::settings.encryption');
    }

    public function theme()
    {
        $themes = settings('themes');

        return LaravelAdmin::content(function (Content $content) use ($themes) {

            $content->header('主题设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '主题设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '主题设置']

            );

            $content->body(view('store-backend::sys_setting.theme', compact('themes')));
        });

//		return view('backend::settings.theme', compact('themes'));
    }

    public function themeAdd(Request $request)
    {
        $data = $request->except('_token', 'file');
        $themeName = $data['name'];
        $settings = $data['themes']['tmp_name'];
        $themes = settings('themes');
        if (!$themes) {
            $themes = ['themes' => [$themeName => $settings]];
        } else {
            $themes = array_merge($themes, [$themeName => $settings]);
            $themes = ['themes' => $themes];
        }

        settings()->setSetting($themes);

        $this->ajaxJson();
    }

    public function themeSave(Request $request)
    {
        $data = $request->except('_token', 'file');
        $themeSettings = ['themeSettings' => $data['themes']];
        settings()->setSetting($themeSettings);

        $this->ajaxJson();
    }

    /**
     * 支付渠道列表
     * @return Content
     */
    public function payChannels()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('支付设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '支付设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '支付设置']

            );

            $content->body(view('store-backend::sys_setting.pay-channels'));
        });
    }

    public function editPayChannels()
    {
        $type = request('type');
        switch ($type) {
            case 'alipay_wap':
                $title = '支付宝手机网站支付';
                break;
            case 'alipay_web':
                $title = '支付宝电脑网站支付';
                break;
            case 'wechat':
                $title = '微信公众号支付';
                break;
            default:
                $title = '微信小程序支付';
        }

        return LaravelAdmin::content(function (Content $content) use ($title, $type) {

            $content->header('支付设置');

            $content->breadcrumb(
                ['text' => '支付设置', 'url' => 'setting/payChannels', 'no-pjax' => 1],
                ['text' => '支付设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '支付设置']

            );

            $content->body(view('store-backend::sys_setting.includes.pay-edit', compact('type', 'title')));
        });
    }

    /**
     * 微信设置
     * @return Content
     */
    public function wechat()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('微信设置');

            $content->breadcrumb(
                ['text' => '系统设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '微信设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '微信设置']

            );

            $content->body(view('store-backend::sys_setting.wechat'));
        });
    }
}