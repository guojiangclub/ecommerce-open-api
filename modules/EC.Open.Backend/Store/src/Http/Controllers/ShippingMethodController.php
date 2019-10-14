<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers;

use GuoJiangClub\EC\Open\Backend\Store\Model\ShippingMethod;
use Illuminate\Http\Request;
use iBrand\Backend\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class ShippingMethodController extends Controller
{


    /**
     * 物流公司列表
     * @return mixed
     */
    public function company()
    {
        $company_list = ShippingMethod::all();

        return LaravelAdmin::content(function (Content $content) use ($company_list) {

            $content->header('物流列表');

            $content->breadcrumb(
                ['text' => '物流列表', 'url' => 'store/shippingmethod/company', 'no-pjax' => 1],
                ['text' => '物流列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '物流列表']

            );

            $content->body(view('store-backend::shippingmethod.company', compact('company_list')));
        });

    }

    /**
     * 物流公司编辑页面
     * @param Request $request
     * @return mixed
     */
    public function CompanyCreate(Request $request)
    {
        if ($request->input('id')) {
            $action = 'edit';
            $company_edit = ShippingMethod::find($request->input('id'));

        } else {
            $action = '';
            $company_edit = new ShippingMethod();
        }

        return LaravelAdmin::content(function (Content $content) use ($action, $company_edit) {

            $content->header('编辑物流信息');

            $content->breadcrumb(
                ['text' => '物流列表', 'url' => 'store/shippingmethod/company', 'no-pjax' => 1],
                ['text' => '编辑物流信息', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '物流列表']

            );

            $content->body(view('store-backend::shippingmethod.companystore', compact('action', 'company_edit')));
        });
    }

    /**
     * 物流公司数据库操作（新增/修改）
     * @param Request $request
     * @return mixed
     */
    public function companyStore(Request $request)
    {
        $input = $request->except('_token', 'id');
        if (!$input['code'] OR !$input['name']) {
            return $this->ajaxJson(false, [], 404, '请完善物流信息');
        }

        $id = $request->input('id') ? $request->input('id') : 0;
        $result = ShippingMethod::CheckShipping($input['code'], $input['name'], $id)->first();
        if ($result) {
            return $this->ajaxJson(false, [], 404, '该物流信息已存在');
        }

        if ($id) {
            $company = ShippingMethod::find($id);
            $company->fill($input);
            $company->save();

        } else {
            if (ShippingMethod::where('code', $input['code'])->orWhere('name', $input['name'])->first()) {
                return $this->ajaxJson(false, [], 404, '该物流信息已存在');
            }
            ShippingMethod::create($input);
        }
        return $this->ajaxJson();

    }

    /**
     * 删除物流公司
     * @param $id
     * @return mixed
     */
    public function deletedCompany($id)
    {
        if (ShippingMethod::destroy($id)) {
            return $this->ajaxJson(true);
        }
        return $this->ajaxJson(false);
    }


}
