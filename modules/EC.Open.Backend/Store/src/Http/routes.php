<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-10-12
 * Time: 13:33
 */

$router->group(['prefix' => 'admin/store'], function () use ($router) {
    $router->post('upload/image', 'ImageController@postUpload')->name('upload.image');
    $router->post('upload/excel', 'ImageController@ExcelUpload')->name('upload.excel');
    $router->post('upload/uploadExcelFile', 'ImageController@uploadExcelFile')->name('upload.uploadExcelFile');

 
    $router->post('ExcelUpload', 'RegistrationsController@ExcelUpload')->name('registrations.ExcelUpload');
   


    //新的规格管理
    $router->group(['prefix' => 'specs'], function () use ($router) {

        $router->get('/', 'GoodsSpecController@index')->name('admin.goods.spec.index');
        $router->get('create', 'GoodsSpecController@create')->name('admin.goods.spec.create');
        $router->post('store', 'GoodsSpecController@store')->name('admin.goods.spec.store');
        $router->get('edit/{id}', 'GoodsSpecController@edit')->name('admin.goods.spec.edit');

        $router->get('specValue/{id}', 'GoodsSpecController@specValue')->name('admin.goods.spec.value.index');
        $router->post('getSpeValueData', 'GoodsSpecController@getSpeValueData')->name('admin.goods.spec.getSpeValueData');
        $router->post('specValue/store', 'GoodsSpecController@specValueStore')->name('admin.goods.spec.value.store');

        $router->get('editSpecValue', 'GoodsSpecController@editSpecValue')->name('admin.goods.spec.value.editSpecValue');
        $router->post('storeSpecValue', 'GoodsSpecController@storeSpecValue')->name('admin.goods.spec.value.storeSpecValue');
        $router->get('addSpecValue/{spec_id}', 'GoodsSpecController@addSpecValue')->name('admin.goods.spec.value.addSpecValue');

        $router->post('delSpecValue', 'GoodsSpecController@delSpecValue')->name('admin.goods.spec.value.delete');

        $router->post('delete/{id}', 'GoodsSpecController@destroy')->name('admin.goods.spec.delete');
    });

    //新模型管理
    $router->group(['prefix' => 'models'], function () use ($router) {

        $router->get('/', 'GoodsModelsController@index')->name('admin.goods.model.index');
        $router->get('create', 'GoodsModelsController@create')->name('admin.goods.model.create');
        $router->post('store', 'GoodsModelsController@store')->name('admin.goods.model.store');
        $router->get('edit/{id}', 'GoodsModelsController@edit')->name('admin.goods.model.edit');

        $router->post('delete/{id}', 'GoodsModelsController@delete')->name('admin.goods.model.delete');
        $router->post('deleteAttrValue/{id}', 'GoodsModelsController@deleteAttrValue')->name('admin.goods.model.deleteAttrValue');
        $router->post('deleteAttr/{id}', 'GoodsModelsController@deleteAttr')->name('admin.goods.model.deleteAttr');
        $router->post('checkSpec/{id}/{model_id}', 'GoodsModelsController@checkSpec')->name('admin.goods.model.checkSpec');
    });

    //公用属性管理
    $router->group(['prefix' => 'attribute'], function () use ($router) {

        $router->get('/', 'GoodsAttributeController@index')->name('admin.goods.attribute.index');
        $router->get('create', 'GoodsAttributeController@create')->name('admin.goods.attribute.create');
        $router->post('store', 'GoodsAttributeController@store')->name('admin.goods.attribute.store');
        $router->get('edit/{id}', 'GoodsAttributeController@edit')->name('admin.goods.attribute.edit');

        $router->post('delete/{id}', 'GoodsAttributeController@delete')->name('admin.goods.attribute.delete');

    });

    //新产品
    $router->group(['prefix' => 'goods'], function () use ($router) {
        $router->get('/', 'CommodityController@index')->name('admin.goods.index');
        $router->get('createBefore', 'CommodityController@createBefore')->name('admin.goods.createBefore');
        $router->get('create', 'CommodityController@create')->name('admin.goods.create');
        $router->get('edit/{id}', 'CommodityController@edit')->name('admin.goods.edit');
        $router->get('sort/update', 'CommodityController@updateSort')->name('admin.goods.sort.update');

        $router->get('excel', 'CommodityController@excel')->name('admin.goods.excel');

        $router->post('destroy/{id}', 'CommodityController@destroy')->name('admin.goods.destroy');
        $router->post('delete/{id}', 'CommodityController@delete')->name('admin.goods.delete');
        $router->post('restore/{id}', 'CommodityController@restore')->name('admin.goods.restore');

        $router->get('get_category', 'CommodityController@getCategoryByGroupID')->name('admin.goods.get_category');
        $router->get('uploadStock', 'CommodityController@uploadStock')->name('admin.goods.uplode_inventorys');

        $router->post('doUploadStock', 'CommodityController@doUploadStock')->name('admin.goods.inventorys_insert');

        $router->post('store', 'CommodityController@store')->name('admin.goods.store');
        $router->get('getAttribute', 'CommodityController@getAttribute')->name('admin.goods.getAttribute');
        $router->get('getSpecsData', 'CommodityController@getSpecsData')->name('admin.goods.getSpecsData');

        $router->get('getExportData', 'CommodityController@getExportData')->name('admin.goods.getExportData');

        $router->get('operationTitle', 'CommodityController@operationTitle')->name('admin.goods.operationTitle');
        $router->post('saveTitle', 'CommodityController@saveTitle')->name('admin.goods.saveTitle');

        $router->get('operationTags', 'CommodityController@operationTags')->name('admin.goods.operationTags');
        $router->post('saveTags', 'CommodityController@saveTags')->name('admin.goods.saveTags');

        $router->post('checkPromotionStatus', 'CommodityController@checkPromotionStatus')->name('admin.goods.checkPromotionStatus');
        $router->post('saveIsDel', 'CommodityController@saveIsDel')->name('admin.goods.saveIsDel');

    });


    //品牌
    $router->resource('brand', 'BrandController');


    //分类
    $router->get('category', 'CategoryController@index')->name('admin.category.index');
    $router->get('category/create', 'CategoryController@create')->name('admin.category.create');
    $router->post('category/store', 'CategoryController@store')->name('admin.category.store');
    $router->get('category/edit/{id}', 'CategoryController@edit')->name('admin.category.edit');
    $router->post('category/update/{id}', 'CategoryController@update')->name('admin.category.update');
    $router->get('category/check', 'CategoryController@check')->name('admin.category.check');
    $router->post('category/delete', 'CategoryController@destroy')->name('admin.category.delete');

    $router->get('category/category_sort', 'CategoryController@category_sort')->name('admin.category.category_sort');

    $router->get('/limit', 'GoodsPurchaseController@index')->name('admin.store.goods.limit');
    $router->get("/limit/sync", "GoodsPurchaseController@syncGoods")->name('admin.store.goods.limit.syncGoods');
    $router->get("/limit/editGoods", "GoodsPurchaseController@editGoods")->name('admin.store.goods.limit.editGoods');
    $router->post("/limit/saveGoods", "GoodsPurchaseController@saveGoods")->name('admin.store.goods.limit.saveGoods');
    $router->post("/limit/postSyncGoods", "GoodsPurchaseController@postSyncGoods")->name('admin.store.goods.limit.postSyncGoods');
    $router->get("/limit/editBatchGoods", "GoodsPurchaseController@editBatchGoods")->name('admin.store.goods.limit.editBatchGoods');
    $router->post("/limit/saveBatchGoods", "GoodsPurchaseController@saveBatchGoods")->name('admin.store.goods.limit.saveBatchGoods');
});

//促销
$router->group(['prefix' => 'admin/store/promotion'], function () use ($router) {
    //新促销活动
    $router->group(['prefix' => 'discount', 'namespace' => 'Promotion'], function () use ($router) {
        $router->get('/', 'DiscountController@index')->name('admin.promotion.discount.index');
        $router->get('create', 'DiscountController@create')->name('admin.promotion.discount.create');
        $router->get('edit/{id}', 'DiscountController@edit')->name('admin.promotion.discount.edit');
        $router->post('store', 'DiscountController@store')->name('admin.promotion.discount.store');
        $router->get('useRecord', 'DiscountController@useRecord')->name('admin.promotion.discount.useRecord');

        $router->get('getUsedExportData', 'DiscountController@getUsedExportData')->name('admin.promotion.discount.getUsedExportData');
    });

    //新促销优惠券
    $router->group(['prefix' => 'coupon', 'namespace' => 'Promotion'], function () use ($router) {
        $router->get('/', 'CouponController@index')->name('admin.promotion.coupon.index');
        $router->get('create', 'CouponController@create')->name('admin.promotion.coupon.create');
        $router->get('edit/{id}', 'CouponController@edit')->name('admin.promotion.coupon.edit');
        $router->post('store', 'CouponController@store')->name('admin.promotion.coupon.store');
        $router->get('useRecord', 'CouponController@useRecord')->name('admin.promotion.coupon.useRecord');

        $router->get('show', 'CouponController@showCoupons')->name('admin.promotion.coupon.show');

        $router->get('sendCoupon', 'CouponController@sendCoupon')->name('admin.promotion.coupon.sendCoupon');
        $router->get('filterUser', 'CouponController@filterUser')->name('admin.promotion.coupon.sendCoupon.filterUser');
        $router->post('getUsers', 'CouponController@getUsers')->name('admin.promotion.coupon.getUsers');
        $router->post('getSelectedUsersByID', 'CouponController@getSelectedUsersByID')->name('admin.promotion.coupon.getSelectedUsersByID');
        $router->post('sendAction', 'CouponController@sendAction')->name('admin.promotion.coupon.sendAction');

        $router->get('couponCode', 'CouponController@couponCode')->name('admin.promotion.coupon.couponCode');
        $router->post('createCouponCode', 'CouponController@createCouponCode')->name('admin.promotion.coupon.createCouponCode');
        $router->get('getExportData', 'CouponController@getExportData')->name('admin.promotion.coupon.getExportData');

        $router->get('getUsedExportData', 'CouponController@getUsedExportData')->name('admin.promotion.coupon.getUsedExportData');
        $router->get('getCouponsExportData', 'CouponController@getCouponsExportData')->name('admin.promotion.coupon.getCouponsExportData');
    });


});



//物流管理
$router->group(['prefix' => 'admin/store/shippingmethod'], function () use ($router) {

    $router->get('company', 'ShippingMethodController@company')->name('admin.shippingmethod.company');
    $router->get('Create', 'ShippingMethodController@CompanyCreate')->name('admin.shippingmethod.CompanyCreate');
    $router->post('companyStore', 'ShippingMethodController@companyStore')->name('admin.shippingmethod.companyStore');
    $router->post('delcompany/{id}', 'ShippingMethodController@deletedCompany')->name('admin.shippingmethod.deletedCompany');
});

//运费模板
$router->group(['prefix' => 'admin/shipping/template'], function () use ($router) {
    $router->get('/', 'ShippingTemplateController@index')->name('admin.shipping.template.index');
    $router->get('create', 'ShippingTemplateController@create')->name('admin.shipping.template.create');
    $router->get('edit/{id}', 'ShippingTemplateController@edit')->name('admin.shipping.template.edit');
    $router->post('store', 'ShippingTemplateController@store')->name('admin.shipping.template.store');
    $router->post('delete/{id}', 'ShippingTemplateController@delete')->name('admin.shipping.template.delete');
});


//订单
$router->group(['prefix' => 'admin/store/order'], function () use ($router) {
    //订单
    $router->get('/', 'OrdersController@index')->name('admin.orders.index');

    $router->get('detail/{id}', 'OrdersController@show')->name('admin.orders.show');


    $router->get('import/orders', 'OrdersController@ordersImport')->name('admin.orders.import');
    $router->post('import/order_send', 'OrdersController@importOrderSend')->name('admin.orders.saveimport');
    $router->get('deliver/{id}', 'OrdersController@ordersDeliver')->name('admin.orders.deliver');
    $router->get('deliver/{id}/edit', 'OrdersController@ordersDeliverEdit')->name('admin.orders.deliver.edit');
    $router->get('multiple_deliver', 'OrdersController@ordersMultipleDeliver')->name('admin.orders.multiple.deliver');

    $router->post('doDeliver', 'OrdersController@deliver')->name('admin.orders.savedeliver');
    $router->get('invoice/{id}', 'InvoiceController@edit')->name('admin.orders.invoice.edit');
    $router->post('invoice/update', 'InvoiceController@update')->name('admin.orders.invoice.update');


    $router->post('close/{id}', 'OrdersController@close')->name('admin.orders.close');

    $router->get('export/job', 'OrdersController@exportJob')->name('admin.orders.export.job');

    $router->get('export/getExportData', 'OrdersController@getExportData')->name('admin.orders.getExportData');

    $router->get('editAddress/{id}', 'OrdersController@editAddress')->name('admin.orders.editAddress');
    $router->post('postAddress', 'OrdersController@postAddress')->name('admin.orders.postAddress');
});



//produce设置
$router->group(['prefix' => 'admin/store/setting'], function () use ($router) {
    $router->get('produce', 'SystemSettingController@index')->name('admin.setting.produce');
    $router->post('saveProduce', 'SystemSettingController@saveProduce')->name('admin.setting.saveProduce');

    //商城设置
    $router->get('shopSetting', 'SystemSettingController@shopSetting')->name('admin.setting.shopSetting');
    $router->post('saveShopSetting', 'SystemSettingController@saveSettings')->name('admin.setting.saveShopSetting');

    //站点配置
    $router->get('siteSettings', 'SystemSettingController@siteSettings')->name('admin.setting.siteSettings');
    $router->post('saveSiteSettings', 'SystemSettingController@saveSiteSettings')->name('admin.setting.saveSiteSettings');

    $router->get('point', 'SystemSettingController@point')->name('admin.setting.point');
    $router->get('employee', 'SystemSettingController@employee')->name('admin.setting.employee');
    $router->get('tool', 'SystemSettingController@toolList')->name('admin.setting.tool');

    $router->get('refund-reason', 'SystemSettingController@refundReason')->name('admin.setting.refund.reason');
    $router->post('saveRefundSettings', 'SystemSettingController@saveRefundSettings')->name('admin.setting.saveRefundSettings');

    $router->get('clearCache', 'SystemSettingController@clearCache')->name('admin.setting.clearCache');

    $router->get('price/protection', 'SystemSettingController@priceProtection')->name('admin.setting.price.protection');

    $router->get('invoice', 'SystemSettingController@invoice')->name('admin.setting.invoice');
    $router->post('saveInvoiceSettings', 'SystemSettingController@saveInvoiceSettings')->name('admin.setting.saveInvoiceSettings');

    $router->get('onlineService', 'SystemSettingController@onlineService')->name('admin.setting.onlineService');
    $router->post('saveOnlineService', 'SystemSettingController@saveOnlineService')->name('admin.setting.saveOnlineService');
});



//评论
$router->group(['prefix' => 'admin/store/comments'], function () use ($router) {
    $router->get('/', 'CommentsController@index')->name('admin.comments.index');
    $router->get('edit/{id}', 'CommentsController@edit')->name('admin.comments.edit');
    $router->post('update/{id}', 'CommentsController@update')->name('admin.comments.update');

    $router->get('create', 'CommentsController@create')->name('admin.comments.create');
    $router->post('searchGoods', 'CommentsController@searchGoods')->name('admin.comments.searchGoods');
    $router->post('searchUsers', 'CommentsController@searchUsers')->name('admin.comments.searchUsers');
    $router->post('store', 'CommentsController@store')->name('admin.comments.store');
});

$router->group(['prefix' => 'admin'], function () use ($router) {

    $router->get('setting/payChannels', 'SettingController@payChannels')->name('admin.setting.payChannels');

    $router->get('setting/editPayChannels', 'SettingController@editPayChannels')->name('admin.setting.editPayChannels');

    $router->get('setting/pay', 'SettingController@pay')->name('admin.setting.pay');

    $router->post('setting/pay', 'SettingController@savePay')->name('admin.setting.pay');

    $router->get('setting/pingxxPay', 'SettingController@pingxxPay')->name('admin.setting.pingxx.pay');

    $router->post('setting/pingxxPay', 'SettingController@savePingxxPay')->name('admin.setting.pingxx.pay');

    $router->post('setting/save', 'SettingController@saveSettings')->name('admin.setting.save');

    $router->get('setting/sms', 'SettingController@sms')->name('admin.setting.sms');

    $router->get('setting/theme', 'SettingController@theme')->name('admin.setting.theme');
    $router->post('setting/theme/add', 'SettingController@themeAdd')->name('admin.setting.theme.add');
    $router->post('setting/theme/save', 'SettingController@themeSave')->name('admin.setting.theme.save');

    $router->get('setting/backend', 'SettingController@backend')->name('admin.setting.backend');

    $router->get('setting/sentry', 'SettingController@sentry')->name('admin.setting.sentry');

    $router->get('setting/analytics', 'SettingController@analytics')->name('admin.setting.analytics');

    $router->get('setting/encryption', 'SettingController@encryption')->name('admin.setting.encryption');

    $router->get('setting/set-custom-package', 'SettingController@setCustomPackage')->name('admin.setting.setCustomPackage');

    $router->get('setting/wechat', 'SettingController@wechat')->name('admin.setting.wechat');

    $router->get('setting/wechat/message', 'WechatMessageSettingController@index')->name('admin.setting.wechat');
    $router->get('setting/wechat/order/remind', 'WechatMessageSettingController@orderRemind')->name('admin.setting.wechat.order.remind');
    $router->get('setting/wechat/goods/deliver', 'WechatMessageSettingController@goodsDeliver')->name('admin.setting.wechat.goods.deliver');
    $router->get('setting/wechat/arrival/goods', 'WechatMessageSettingController@goodsArrival')->name('admin.setting.wechat.goods.arrival');
    $router->get('setting/wechat/sales/service', 'WechatMessageSettingController@salesService')->name('admin.setting.wechat.sales.service');
    $router->get('setting/wechat/goods/refund', 'WechatMessageSettingController@goodsRefund')->name('admin.setting.wechat.goods.refund');
    $router->get('setting/wechat/customer/paid', 'WechatMessageSettingController@customerPaid')->name('admin.setting.wechat.customer.paid');
    $router->get('setting/wechat/money/changed', 'WechatMessageSettingController@moneyChanged')->name('admin.setting.wechat.money.changed');
    $router->get('setting/wechat/point/changed', 'WechatMessageSettingController@pointChanged')->name('admin.setting.wechat.point.changed');
    $router->get('setting/wechat/charge/success', 'WechatMessageSettingController@chargeSuccess')->name('admin.setting.wechat.charge.success');
    $router->get('setting/wechat/member/grade', 'WechatMessageSettingController@memberGrade')->name('admin.setting.wechat.member.grade');
    $router->get('setting/wechat/sales/notice', 'WechatMessageSettingController@salesNotice')->name('admin.setting.wechat.sales.notice');
    $router->get('setting/wechat/refund/result', 'WechatMessageSettingController@refundResult')->name('admin.setting.wechat.refund.result');
    $router->get('setting/wechat/groupon/grouponSuccess', 'WechatMessageSettingController@grouponSuccess')->name('admin.setting.wechat.groupon.success');
    $router->get('setting/wechat/groupon/grouponFailed', 'WechatMessageSettingController@grouponFailed')->name('admin.setting.wechat.groupon.failed');
    $router->get('setting/wechat/activity/notice', 'WechatMessageSettingController@activityNotice')->name('admin.setting.wechat.activity.notice');
    $router->get('setting/wechat/activity/notice/gift', 'WechatMessageSettingController@activityNoticeGift')->name('admin.setting.wechat.activity.notice.gift');
    $router->post('setting/wechat/save', 'WechatMessageSettingController@save')->name('admin.setting.wechat.save');

    $router->group(['prefix' => 'setting/uploads'], function () use ($router) {
        $router->get('/', 'UploadVerifyFileController@index')->name('admin.uploads.index');
        $router->post('/up', 'UploadVerifyFileController@upload')->name('admin.uploads.up');
    });
});
