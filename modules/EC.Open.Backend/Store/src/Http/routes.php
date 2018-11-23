<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-10-12
 * Time: 13:33
 */

$router->group(['prefix' => 'admin/store'], function () use ($router) {

    $router->get('/', 'DashboardController@index')->name('admin.store.index');
    $router->get('getExportData', 'DashboardController@getExportData')->name('admin.store.getExportData');

    $router->get('dashboard', 'DashboardController@dashboard')->name('admin.store.dashboard.index');
    $router->get('getMonthData', 'DashboardController@getMonthData')->name('admin.store.dashboard.getMonthData');

    $router->post('upload/image', 'ImageController@postUpload')->name('upload.image');
    $router->post('upload/excel', 'ImageController@ExcelUpload')->name('upload.excel');
    $router->post('upload/uploadExcelFile', 'ImageController@uploadExcelFile')->name('upload.uploadExcelFile');

    $router->get('registrations/export', 'RegistrationsController@reg_export')->name('registrations.export');
    $router->post('registrations/get_regexport', 'RegistrationsController@getregexport')->name('registrations.get_regexport');
    $router->resource('registrations', 'RegistrationsController');

    $router->post('ExcelUpload', 'RegistrationsController@ExcelUpload')->name('registrations.ExcelUpload');
    $router->get('registration/getExportData', 'RegistrationsController@getExportData')->name('registrations.export.getExportData');

    $router->group(['prefix' => 'registration'], function () use ($router) {
        $router->get('importRegistrationModal', 'RegistrationsController@importRegistrationModal')->name('admin.registrations.importRegistrationModal');
        $router->get('getImportDataCount', 'RegistrationsController@getImportDataCount')->name('admin.registrations.getImportDataCount');
        $router->get('saveImportData', 'RegistrationsController@saveImportData')->name('admin.registrations.saveImportData');
    });

    //规格
//    $router->get('pic/{specIndex}', 'SpecController@pic')->name('admin.pic.upload');
//    $router->resource('specs', 'SpecController');

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
//
        $router->post('delete/{id}', 'GoodsAttributeController@delete')->name('admin.goods.attribute.delete');
//        $router->post('deleteAttrValue/{id}', 'GoodsModelsController@deleteAttrValue')->name('admin.goods.model.deleteAttrValue');
//        $router->post('deleteAttr/{id}', 'GoodsModelsController@deleteAttr')->name('admin.goods.model.deleteAttr');
//        $router->post('checkSpec/{id}','GoodsModelsController@checkSpec')->name('admin.goods.model.checkSpec');

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

    //微信群
    $router->resource('wechat/group', 'WechatGroupController');

    //单品折扣
    $router->group(['prefix' => 'single-discount'], function () use ($router) {

        $router->get('/', 'SingleDiscountController@index')->name('admin.promotion.singleDiscount.index');
        $router->get('create', 'SingleDiscountController@create')->name('admin.promotion.singleDiscount.create');
        $router->get('{id}/edit', 'SingleDiscountController@edit')->name('admin.promotion.singleDiscount.edit');

        $router->get('create/importModal', 'SingleDiscountController@importModal')->name('admin.promotion.singleDiscount.importModal');
        $router->get('create/getImportDataCount', 'SingleDiscountController@getImportDataCount')->name('admin.promotion.singleDiscount.getImportDataCount');
        $router->get('saveImportData', 'SingleDiscountController@saveImportData')->name('admin.promotion.singleDiscount.saveImportData');

        $router->post('store', 'SingleDiscountController@store')->name('admin.promotion.singleDiscount.store');
        $router->post('storeBatch', 'SingleDiscountController@storeBatch')->name('admin.promotion.singleDiscount.batch');
        $router->post('cacheDiscount', 'SingleDiscountController@cacheDiscount')->name('admin.promotion.singleDiscount.cacheDiscount');

        $router->get('singleDataList/{id}', 'SingleDiscountController@getDataList')->name('admin.promotion.singleDiscount.getDataList');
        $router->post('switchStatus', 'SingleDiscountController@switchStatus')->name('admin.promotion.singleDiscount.switchStatus');

        $router->get('syncGoodsPriceModal', 'SingleDiscountController@syncGoodsPriceModal')->name('admin.promotion.singleDiscount.syncGoodsPriceModal');
        $router->post('syncGoodsPrice', 'SingleDiscountController@syncGoodsPrice')->name('admin.promotion.singleDiscount.syncGoodsPrice');

        $router->get('getDiscountInfo', 'SingleDiscountController@getDiscountInfo')->name('admin.promotion.singleDiscount.getDiscountInfo');
    });

    //套餐
    $router->group(['prefix' => 'bundle'], function () use ($router) {

        $router->get('/', 'BundleController@index')->name('admin.promotion.bundle.index');
        $router->get('create', 'BundleController@create')->name('admin.promotion.bundle.create');
        $router->post('store', 'BundleController@store')->name('admin.promotion.bundle.store');
        $router->get('/{id}/edit', 'BundleController@edit')->name('admin.promotion.bundle.edit');
        $router->post('/{id}/edit', 'BundleController@update')->name('admin.promotion.bundle.update');
        $router->delete('/{id}/delete', 'BundleController@delete')->name('admin.promotion.bundle.delete');

        $router->get('getSpu', 'BundleController@getSpu')->name('admin.promotion.bundle.getSpu');
        $router->post('getSpuData', 'BundleController@getSpuData')->name('admin.promotion.bundle.getSpuData');
    });

    //  2017.8月套餐
    $router->group(['prefix' => 'suit'], function () use ($router) {
        $router->get('/', 'SuitController@index')->name('admin.promotion.suit.index');
        $router->get('create', 'SuitController@create')->name('admin.promotion.suit.create');
        $router->post('toggleStatus', 'SuitController@toggleSuitStatus')->name('admin.suit.toggle.suit.status');
        $router->post('store', 'SuitController@store')->name('admin.promotion.suit.store');

        $router->get('edit/{id}', 'SuitController@edit')->name('admin.promotion.suit.edit');
        $router->post('delete', 'SuitController@destroy')->name('admin.promotion.suit.delete');
        //套餐item
        $router->get('create/{id}/add', 'SuitController@createItem')->name('admin.promotion.suit.create.item');
        $router->get('item/{id}/edit', 'SuitController@editItem')->name('admin.promotion.suit.create.item.edit');
        $router->get('show/{id}/item', 'SuitController@ShowItem')->name('admin.promotion.suit.ShowItem');
        $router->post('store/item/add', 'SuitController@storeItem')->name('admin.promotion.suit.store.item');
        $router->post('getGoodsInfo', 'SuitController@getGoodsInfo')->name('admin.promotion.suit.getGoodsInfo');
        $router->post('item/toggleStatus', 'SuitController@toggleSuitItemStatus')->name('admin.suit.toggle.suit.item.status');
        $router->post('delete/item', 'SuitController@destroyItem')->name('admin.promotion.suit.delete.item');
        $router->post('item/{id}/update', 'SuitController@updateItem')->name('admin.promotion.suit.store.item.update');
    });

    // 新人进店礼
    $router->group(['prefix' => 'gift/new_user'], function () use ($router) {
        $router->get('/', 'GiftNewUserController@index')->name('admin.promotion.gift.new.user.index');

        $router->get('/create', 'GiftNewUserController@create')->name('admin.promotion.gift.new.user.create');

        $router->post('/store', 'GiftNewUserController@store')->name('admin.promotion.gift.new.user.store');

        $router->get('/api/coupon', 'GiftNewUserController@coupon_api')->name('admin.promotion.gift.new.user.api.coupon');

        $router->get('/{id}/edit', 'GiftNewUserController@edit')->name('admin.promotion.gift.new.user.api.edit');

        $router->post('/{id}/update', 'GiftNewUserController@update')->name('admin.promotion.gift.new.user.api.update');

        $router->post('/toggleStatus', 'GiftNewUserController@toggleStatus')->name('admin.promotion.gift.new.user.toggleStatus');

        $router->post('/delete', 'GiftNewUserController@destroy')->name('admin.promotion.gift.new.user.delete');

        $router->get('/{id}/edit', 'GiftNewUserController@edit')->name('admin.promotion.gift.new.user.edit');
    });

    // 生日礼
    $router->group(['prefix' => 'gift/birthday'], function () use ($router) {

        $router->get('/', 'GiftBirthdayController@index')->name('admin.promotion.gift.birthday.index');

        $router->get('/user', 'GiftBirthdayController@user')->name('admin.promotion.gift.birthday.user');

        $router->get('/create', 'GiftBirthdayController@create')->name('admin.promotion.gift.birthday.create');

        $router->post('/store', 'GiftBirthdayController@store')->name('admin.promotion.gift.birthday.store');

        $router->get('/api/coupon', 'GiftBirthdayController@coupon_api')->name('admin.promotion.gift.birthday.api.coupon');

        $router->get('/{id}/edit', 'GiftBirthdayController@edit')->name('admin.promotion.gift.birthday.api.edit');

        $router->post('/{id}/update', 'GiftBirthdayController@update')->name('admin.promotion.gift.birthday.api.update');

        $router->post('/toggleStatus', 'GiftBirthdayController@toggleStatus')->name('admin.promotion.gift.birthday.toggleStatus');

        $router->post('/delete', 'GiftBirthdayController@destroy')->name('admin.promotion.gift.birthday.delete');

        $router->get('/{id}/edit', 'GiftBirthdayController@edit')->name('admin.promotion.gift.birthday.edit');
    });

    // 定向发券
    $router->group(['prefix' => 'directional/coupon'], function () use ($router) {

        $router->get('/', 'DirectionalCouponController@index')->name('admin.promotion.directional.coupon.index');

        $router->get('/create', 'DirectionalCouponController@create')->name('admin.promotion.directional.coupon.create');

        $router->post('/store', 'DirectionalCouponController@store')->name('admin.promotion.directional.coupon.store');

        $router->get('/api/coupon', 'DirectionalCouponController@coupon_api')->name('admin.promotion.directional.coupon.api.coupon');

        $router->post('/searchUser', 'DirectionalCouponController@searchUser')->name('admin.promotion.directional.searchUser');

        $router->post('/delete', 'DirectionalCouponController@destroy')->name('admin.promotion.directional.delete');

        $router->get('/{id}/edit', 'DirectionalCouponController@edit')->name('admin.promotion.directional.edit');

        $router->get('/{id}/log', 'DirectionalCouponController@log')->name('admin.promotion.directional.log');
    });

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

    $router->group(['prefix' => 'pubic', 'namespace' => 'Promotion'], function () use ($router) {
        $router->get('getSpu', 'PublicController@getSpu')->name('admin.promotion.getSpu');
        $router->get('getWechatGroup', 'PublicController@getWechatGroup')->name('admin.promotion.getWechatGroup');
        $router->post('getSpuData', 'PublicController@getSpuData')->name('admin.promotion.getSpuData');
        $router->post('getWechatGroupData', 'PublicController@getWechatGroupData')->name('admin.promotion.getWechatGroupData');
        $router->post('export/excelExport', 'PublicController@excelExport')->name('admin.promotion.excelExport');
        $router->get('export/download/{url}', 'PublicController@download')->name('admin.promotion.download');
    });

    //秒杀
    $router->group(['prefix' => 'seckill'], function () use ($router) {
        $router->get('/', 'SeckillController@index')->name('admin.promotion.seckill.index');
        $router->get('create', 'SeckillController@create')->name('admin.promotion.seckill.create');
        $router->get('edit/{id}', 'SeckillController@edit')->name('admin.promotion.seckill.edit');

        $router->get('getSpu', 'SeckillController@getSpu')->name('admin.promotion.seckill.getSpu');
        $router->post('getSpuData', 'SeckillController@getSpuData')->name('admin.promotion.seckill.getSpuData');
        $router->get('getSelectGoods', 'SeckillController@getSelectGoods')->name('admin.promotion.seckill.getSelectGoods');

        $router->post('store', 'SeckillController@store')->name('admin.promotion.seckill.store');
        $router->post('updateDisable', 'SeckillController@updateDisable')->name('admin.promotion.seckill.updateDisable');
        $router->post('update', 'SeckillController@update')->name('admin.promotion.seckill.update');
        $router->post('delete/{id}', 'SeckillController@delete')->name('admin.promotion.seckill.delete');
    });

    //团购
    $router->group(['prefix' => 'groupon'], function () use ($router) {
        $router->get('/', 'GrouponController@index')->name('admin.promotion.groupon.index');
        $router->get('create', 'GrouponController@create')->name('admin.promotion.groupon.create');
        $router->get('edit/{id}', 'GrouponController@edit')->name('admin.promotion.groupon.edit');

        $router->get('getSpu', 'GrouponController@getSpu')->name('admin.promotion.groupon.getSpu');
        $router->post('getSpuData', 'GrouponController@getSpuData')->name('admin.promotion.groupon.getSpuData');
        $router->get('getSelectGoods', 'GrouponController@getSelectGoods')->name('admin.promotion.groupon.getSelectGoods');

        $router->post('store', 'GrouponController@store')->name('admin.promotion.groupon.store');
        $router->post('updateDisable', 'GrouponController@updateDisable')->name('admin.promotion.groupon.updateDisable');
        $router->post('update', 'GrouponController@update')->name('admin.promotion.groupon.update');
        $router->post('delete/{id}', 'GrouponController@delete')->name('admin.promotion.groupon.delete');
    });

    //多人拼团
    $router->group(['prefix' => 'multiGroupon'], function () use ($router) {
        $router->get('/', 'MultiGrouponController@index')->name('admin.promotion.multiGroupon.index');
        $router->get('create', 'MultiGrouponController@create')->name('admin.promotion.multiGroupon.create');
        $router->get('edit/{id}', 'MultiGrouponController@edit')->name('admin.promotion.multiGroupon.edit');
        $router->post('store', 'MultiGrouponController@store')->name('admin.promotion.multiGroupon.store');

        $router->get('getSpuModal', 'MultiGrouponController@getSpuModal')->name('admin.promotion.multiGroupon.getSpuModal');
        $router->post('getSpuData', 'MultiGrouponController@getSpuData')->name('admin.promotion.multiGroupon.getSpuData');
        $router->post('update', 'MultiGrouponController@update')->name('admin.promotion.multiGroupon.update');
        $router->post('delete/{id}', 'MultiGrouponController@delete')->name('admin.promotion.multiGroupon.delete');

        $router->get('grouponItemList/{id}', 'MultiGrouponController@grouponItemList')->name('admin.promotion.multiGroupon.grouponItemList');

        $router->get('getRefundModal', 'MultiGrouponController@getRefundModal')->name('admin.promotion.multiGroupon.getRefundModal');
        $router->get('getRefundItemsPaginate', 'MultiGrouponController@getRefundItemsPaginate')->name('admin.promotion.multiGroupon.getRefundItemsPaginate');
        $router->get('getRefundList', 'MultiGrouponController@getRefundList')->name('admin.promotion.multiGroupon.getRefundList');
    });
});

//事件营销
$router->group(['prefix' => 'admin/marketing', 'namespace' => 'Marketing'], function () use ($router) {
    $router->get('{type}/list', 'MarketingController@index')->name('admin.marketing.index');
    $router->get('create', 'MarketingController@create')->name('admin.marketing.create');
    $router->get('edit/{id}', 'MarketingController@edit')->name('admin.marketing.edit');
    $router->post('store', 'MarketingController@store')->name('admin.marketing.store');
    $router->post('delete/{id}', 'MarketingController@delete')->name('admin.marketing.delete');

    $router->get('getCoupon', 'MarketingController@getCoupon')->name('admin.marketing.getCoupon');
    $router->post('getCouponData', 'MarketingController@getCouponData')->name('admin.marketing.getCouponData');
    $router->post('getSelectCouponData', 'MarketingController@getSelectCouponData')->name('admin.marketing.getSelectCouponData');

    $router->get('member', 'MemberMarketingController@index')->name('admin.marketing.member.index');

    $router->group(['prefix' => 'sign'], function () use ($router) {
        $router->get('create', 'SignController@create')->name('admin.marketing.sign.create');
        $router->get('edit/{id}', 'SignController@edit')->name('admin.marketing.sign.edit');
        $router->post('store', 'SignController@store')->name('admin.marketing.sign.store');
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

$router->group(['prefix' => 'admin/store/cart'], function () use ($router) {
    $router->get('/', 'ShoppingCartController@index')->name('admin.cart.index');
    $router->get('getExportData', 'ShoppingCartController@getExportData')->name('admin.cart.getExportData');
});

$router->get('admin/erp/log', 'ErpController@log_list')->name('admin.erp.log');
$router->get('admin/erp/order', 'ErpController@processOrders')->name('admin.erp.order');
$router->post('admin/erp/order', 'ErpController@orderToErp')->name('admin.erp.order');

$router->get('admin/erp/all-orders', 'ErpController@allOrders')->name('admin.erp.order.all');

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

/*线下订单*/
$router->group(['prefix' => 'admin/offOrders'], function () use ($router) {
    $router->get('/', 'OffOrdersController@offOrder')->name('admin.orders.offOrders');
    $router->get('exportExcel', 'OffOrdersController@exportOffExcel')->name('admin.offOrders.exportExcel');
    $router->get('download/{url}', 'OffOrdersController@downloadOff')->name('admin.offOrders.download');
});

//推广管理
$router->group(['prefix' => 'admin/cms'], function () use ($router) {
    $router->resource('ad', 'AdvertisementController');
    $router->resource('aditem', 'AdvertisementItemController');

    $router->get('aditem/promote/goods', 'AdvertisementItemController@promoteGoods')->name('admin.ad.promote.goods');
    $router->post('aditem/promote/goods/data', 'AdvertisementItemController@getPromoteGoodsData')->name('admin.ad.promote.goods.data');
    $router->post('aditem/promote/goods/add', 'AdvertisementItemController@AddGoodsPromote')->name('admin.ad.promote.goods.add');
    //子推广
    $router->get('aditem/child/create', 'AdvertisementItemController@createChild')->name('admin.ad.child.create');
    $router->post('aditem/child/store', 'AdvertisementItemController@storeChild')->name('admin.ad.child.store');

    $router->post('aditem/settings', 'AdvertisementItemController@SetAdStoreDetailUrl')->name('admin.ad.settings');

    //隐藏或开启
    $router->post('aditem/toggleStatus', 'AdvertisementItemController@toggleStatus')->name('admin.aditem.toggleStatus');

    $router->post('ad/toggleStatus', 'AdvertisementController@toggleStatus')->name('admin.ad.toggleStatus');
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

//退换货
$router->group(['prefix' => 'admin/store/refund'], function () use ($router) {
    $router->get('/', 'RefundController@index')->name('admin.refund.index');
    $router->get('show/{id}', 'RefundController@show')->name('admin.refund.show');
    $router->post('store', 'RefundController@store')->name('admin.refund.store');
    $router->post('paid', 'RefundController@paid')->name('admin.refund.paid');

    $router->get('getStatus/{id}', 'RefundController@getStatus')->name('admin.refund.getStatus');
    $router->post('changeStatus', 'RefundController@changeStatus')->name('admin.refund.changeStatus');

    $router->get('getExportData', 'RefundController@getExportData')->name('admin.refund.getExportData');
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

//数据库处理
$router->group(['prefix' => 'admin/data'], function () use ($router) {

    $router->get('install', 'DataController@install')->name('admin.data.install');
    $router->get('migrate', 'DataController@migrate')->name('admin.data.migrate');

    $router->get('orderItem', 'DataProcessController@orderItem')->name('admin.data.orderItem');
    $router->get('note', 'DataProcessController@note')->name('admin.data.note');
    $router->get('registration', 'DataProcessController@registration_list')->name('admin.data.registration');

//数据处理
    $router->get('handleAttributeValue', 'DataController@handleAttributeValue')->name('admin.data.handleAttributeValue');
    $router->get('handleSpec', 'DataController@handleSpec')->name('admin.data.handleSpec');
    $router->get('handleGoodsAttribute', 'DataController@handleGoodsAttribute')->name('admin.data.handleGoodsAttribute');
    $router->get('handleGoodsSpecRelation', 'DataController@handleGoodsSpecRelation')->name('admin.data.handleGoodsSpecRelation');
    $router->get('handleProduct', 'DataController@handleProduct')->name('admin.data.handleProduct');

    $router->get('handleSpecImg', 'DataController@handleSpecImg')->name('admin.data.handleSpecImg');

    $router->get('update/user/grade', 'DataController@updateUserGrade')->name('admin.data.updateUserGrade');

    $router->get('auto', 'DataController@auto')->name('admin.data.auto');

    $router->get('fixfreeorder', 'DataController@fixFreeOrder')->name('admin.data.fixfreeOrder');

    $router->get('fixgoodsstore', 'DataController@fixGoodsStore')->name('admin.data.fixGoodsStore');

    $router->get('fixgoodsinsale', 'DataController@fixGoodsInsale')->name('admin.data.fixGoodsInsale');

    $router->get('fixCommunityPointBug', 'DataController@fixCommunityPointBug')->name('admin.data.fixCommunityPointBug');

    $router->get('fixgoodsprice', 'DataController@fixGoodsPrice')->name('admin.data.fixGoodsPrice');

    $router->get('handleNewAttribute', 'DataController@handleNewAttribute')->name('admin.data.handleNewAttribute');

    $router->get('moveOrderToOfflineOrder', 'DataController@moveOrderToOfflineOrder')->name('admin.data.moveOrderToOfflineOrder');

    $router->get('handleNumberForUser', 'DataController@handleNumberForUser')->name('admin.data.handleNumberForUser');

    $router->get('logGradeUser', 'DataController@logGradeUser')->name('admin.data.logGradeUser');

    $router->get('AddVipCodeInTtpos', 'DataController@AddVipCodeInTtpos')->name('admin.data.AddVipCodeInTtpos');

    $router->get('handleOfflineOrder', 'DataController@handleOfflineOrder')->name('admin.data.handleOfflineOrder');

    $router->get('handleUserGrade', 'DataController@handleUserGrade')->name('admin.data.handleUserGrade');
    $router->get('handleUserGrade2', 'DataController@handleUserGrade2')->name('admin.data.handleUserGrade2');

    $router->get('handleInputNewAttribute', 'DataController@handleInputNewAttribute')->name('admin.data.handleInputNewAttribute');

    $router->get('deleteOldAttribute', 'DataController@deleteOldAttribute')->name('admin.data.deleteOldAttribute');

    $router->get('processSiteSetting', 'DataController@processSiteSetting')->name('admin.data.processSiteSetting');

    $router->get('ddapp', 'DataController@ddApp')->name('admin.data.ddapp');
    $router->get('ddSchedule', 'DataController@ddSchedule')->name('admin.data.ddSchedule');

    $router->group(['middleware' => 'admin_role:administrator'], function () use ($router) {
        $router->get('run-sql', 'DataController@runSql')->name('admin.data.runSql');
        $router->get('run-payment', 'DataController@runPayment');
        $router->post('run-payment', 'DataController@runPayment')->name('admin.data.runPayment');
    });

    $router->get('handleFuntasyRegistrationData', 'DataController@handleFuntasyRegistrationData')->name('admin.data.handleFuntasyRegistrationData');

    $router->get('handleFuntasyRegistrationChannelData', 'DataController@handleFuntasyRegistrationChannelData')->name('admin.data.handleFuntasyRegistrationChannelData');

    $router->get('handleReissueMobilePoint', 'DataController@handleReissueMobilePoint')->name('admin.data.handleReissueMobilePoint');

    $router->get('handleReceivePoint', 'DataController@handleReceivePoint')->name('admin.data.handleReceivePoint');

    $router->get('handleHomeCache', 'DataController@handleHomeCache')->name('admin.data.handleHomeCache');

    $router->get('handlePCHeadMenuCache', 'DataController@handlePCHeadMenuCache')->name('admin.data.handlePCHeadMenuCache');

    $router->get('handleCategory', 'DataController@handleCategory')->name('admin.data.handleCategory');

    $router->get('clearGoodsCache', 'DataController@clearGoodsCache')->name('admin.data.clearGoodsCache');

    $router->get('getWxUnionID', 'DataController@getWxUnionID')->name('admin.data.getWxUnionID');

    $router->get('handleGoodsComment', 'DataController@handleGoodsComment')->name('admin.data.handleGoodsComment');

    $router->get('createCustomerMiniCode', 'DataController@createCustomerMiniCode')->name('admin.data.createCustomerMiniCode');
});

//线下数据导入
$router->group(['prefix' => 'admin/dataimport', 'namespace' => 'DataImport'], function () use ($router) {

    $router->get('createOrder', 'DataImportController@createOrder')->name('admin.dataimport.createOrder');
    $router->get('createVipeak', 'DataImportController@createVipeak')->name('admin.dataimport.createVipeak');
    $router->get('createCoupon', 'DataImportController@createCoupon')->name('admin.dataimport.createCoupon');
    $router->get('createWebCouponCode', 'DataImportController@createWebCouponCode')->name('admin.dataimport.createWebCouponCode');

    $router->post('importOrder', 'DataImportController@importOrder')->name('admin.dataimport.importOrder');
    $router->post('importVipeak', 'DataImportController@importVipeak')->name('admin.dataimport.importVipeak');
    $router->post('importCoupon', 'DataImportController@importCoupon')->name('admin.dataimport.importCoupon');
    $router->post('importWebCouponCode', 'DataImportController@importWebCouponCode')->name('admin.dataimport.importWebCouponCode');

    $router->get('getImportOrderDataCount', 'DataImportController@getImportDataCount')->name('admin.dataimport.getImportOrderDataCount');
    $router->get('orderJob', 'DataImportController@orderJob')->name('admin.dataimport.orderJob');
    $router->get('importOrderModal', 'DataImportController@importOrderModal')->name('admin.dataimport.importOrderModal');

    $router->get('handOrderData', 'DataImportController@handOrderData')->name('admin.dataimport.handOrderData');
});

//weChat card
$router->group(['prefix' => 'admin/wechat', 'namespace' => 'WeChat'], function () use ($router) {
    $router->get('card', 'CardController@index')->name('admin.wechat.card.index');
    $router->get('card/create', 'CardController@create')->name('admin.wechat.card.create');
    $router->post('card/store', 'CardController@store')->name('admin.wechat.card.store');
    $router->delete('card/delete/{id}', 'CardController@deleteCard')->name('admin.wechat.card.delete');

    $router->get('landingPage', 'CardController@landingPage')->name('admin.wechat.landingPage.index');
    $router->get('landingPage/create', 'CardController@createLandingPage')->name('admin.wechat.landingPage.create');
    $router->post('landingPage/store', 'CardController@storeLandingPage')->name('admin.wechat.landingPage.store');

    $router->get('mate', 'MateController@index')->name('admin.wechat.mate.index');
    $router->get('mate/create', 'MateController@create')->name('admin.wechat.mate.create');
    $router->post('mate/store', 'MateController@store')->name('admin.wechat.mate.store');
    $router->post('mate/upload', 'MateController@upload')->name('admin.wechat.mate.upload');
});

//Repair Data
$router->group(['prefix' => 'admin/repairData', 'namespace' => 'RepairData'], function () use ($router) {
    $router->get('order/', 'OrdersController@index')->name('admin.repair.order.index');
    $router->post('order/createPoint', 'OrdersController@createPoint')->name('admin.repair.order.createPoint');
});

/*$router->group(['prefix' => 'admin/balance'], function () use ($router) {
    $router->get("/", "BalanceController@index")->name('admin.balance.cash.index');
    $router->get("show/{id}", "BalanceController@show")->name('admin.balance.cash.show');
    $router->get("operatePay/{id}", "BalanceController@operatePay")->name('admin.balance.cash.operatePay');
    
    $router->post("view", "BalanceController@review")->name('admin.balance.cash.review');
    $router->post("applyPay", "BalanceController@applyPay")->name('admin.balance.cash.applyPay');
});*/

$router->group(['prefix' => 'admin/store/point-mall', 'namespace' => 'PointMall'], function () use ($router) {
    $router->group(['prefix' => 'goods'], function () use ($router) {
        $router->get('/', 'GoodsController@index')->name('admin.point-mall.goods.index');
        $router->get('create', 'GoodsController@create')->name('admin.point-mall.goods.create');
        $router->get('edit/{id}', 'GoodsController@edit')->name('admin.point-mall.goods.edit');
    });

    $router->group(['prefix' => 'orders'], function () use ($router) {
        $router->get('/', 'OrdersController@index')->name('admin.point-mall.orders.index');
        $router->get('show/{id}', 'OrdersController@show')->name('admin.point-mall.orders.show');
        $router->get('getExportData', 'OrdersController@getExportData')->name('admin.point-mall.orders.getExportData');
    });
});

//供应商
$router->group(['prefix' => 'admin/store/supplier'], function () use ($router) {
    $router->get('/', 'SupplierController@index')->name('admin.supplier.index');
    $router->get('create', 'SupplierController@create')->name('admin.supplier.create');
    $router->post('store', 'SupplierController@store')->name('admin.supplier.store');
    $router->get('edit/{id}', 'SupplierController@edit')->name('admin.supplier.edit');
    $router->post('delete/{id}', 'SupplierController@delete')->name('admin.supplier.delete');
});

$router->group(['prefix' => 'admin'], function () use ($router) {
    //$router->get('setting', 'SettingController@index');

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

//游记
$router->group(['prefix' => 'admin/store/travel', 'namespace' => 'Travel', 'middleware' => ['market.bootstrap']], function () use ($router) {
    $router->resource('contents', 'ContentsController');
    $router->post('contents/audit', 'ContentsController@audit');
    $router->resource('comments', 'CommentController');
    $router->resource('tags', 'TagsController');
    $router->get('setting', 'SettingsController@index')->name('admin.travel.setting');
});