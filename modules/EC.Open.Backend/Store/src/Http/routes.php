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

    $router->group(['prefix' => 'setting'], function () use ($router) {
        $router->group(['prefix' => 'micro/page','namespace' => 'MicroPage'], function () use ($router) {
            $router->get('/', 'MicroPageController@index')->name('admin.setting.micro.page.index');
            $router->get('/store', 'MicroPageController@store')->name('admin.setting.micro.page.store');
            $router->post('/{id}/delete', 'MicroPageController@delete')->name('admin.setting.micro.page.delete');
            $router->post('/{id}/setIndexPage', 'MicroPageController@setIndexPage')->name('admin.setting.micro.page.setIndexPage');
            $router->post('/{id}/setCategoryPage', 'MicroPageController@setCategoryPage')->name('admin.setting.micro.page.setCategoryPage');

            $router->post('/name/update', 'MicroPageController@update')->name('admin.setting.micro.page.name.update');
            $router->post('/{id}/updateMicroPageAd', 'MicroPageController@updateMicroPageAd')->name('admin.setting.micro.page.updateMicroPageAd');
            $router->get('/{id}/edit', 'MicroPageController@edit')->name('admin.setting.micro.page.name.edit');
            $router->get('/get/advert', 'MicroPageController@getAdvertByType')->name('admin.setting.micro.page.get.advert');
            $router->post('compoent/{id}/delete', 'CompoentController@delete')->name('admin.setting.micro.page.compoent.delete');
            $router->post('/update', 'CompoentController@update')->name('admin.setting.micro.page.compoent.update');
            $router->post('/store', 'CompoentController@store')->name('admin.setting.micro.page.compoent.store');
            $router->get('/model/goods', 'CompoentController@modelGoods')->name('admin.setting.micro.page.compoent.model.goods');
            $router->get('/model/coupons', 'CompoentController@modelCoupons')->name('admin.setting.micro.page.compoent.model.coupons');
            $router->get('/model/categorys', 'CompoentController@modelCategorys')->name('admin.setting.micro.page.compoent.model.categorys');
            $router->get('/model/pages', 'CompoentController@modelPages')->name('admin.setting.micro.page.compoent.model.pages');
            $router->get('/model/images', 'CompoentController@modelImages')->name('admin.setting.micro.page.compoent.model.images');
            $router->get('compoent/getGoodsData', 'CompoentController@getGoodsData')->name('admin.setting.micro.page.compoent.getGoodsData');
            $router->get('compoent/getPagesData', 'CompoentController@getPagesData')->name('admin.setting.micro.page.compoent.getPagesData');
            $router->get('compoent/getCategorysData', 'CompoentController@getCategorysData')->name('admin.setting.micro.page.compoent.getCategorysData');
            $router->get('compoent/getCouponsData', 'CompoentController@getCouponsData')->name('admin.setting.micro.page.compoent.getCouponsData');

            $router->group(['prefix' => 'compoent/'], function () use ($router) {
                $router->get('/', 'CompoentController@index')->name('admin.setting.micro.page.compoent.index');
                $router->get('{type}/', 'CompoentController@index')->name('admin.setting.micro.page.compoent.index');
                $router->get('{type}/create', 'CompoentController@create')->name('admin.setting.micro.page.compoent.create');
                $router->get('{type}/{code}/edit', 'CompoentController@edit')->name('admin.setting.micro.page.compoent.edit');
            });
        });

    });

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
});


//物流管理
$router->group(['prefix' => 'admin/store/shippingmethod'], function () use ($router) {

    $router->get('company', 'ShippingMethodController@company')->name('admin.shippingmethod.company');
    $router->get('Create', 'ShippingMethodController@CompanyCreate')->name('admin.shippingmethod.CompanyCreate');
    $router->post('companyStore', 'ShippingMethodController@companyStore')->name('admin.shippingmethod.companyStore');
    $router->post('delcompany/{id}', 'ShippingMethodController@deletedCompany')->name('admin.shippingmethod.deletedCompany');
});


//订单
$router->group(['prefix' => 'admin/store/order'], function () use ($router) {
    $router->get('/', 'OrdersController@index')->name('admin.orders.index');

    $router->get('detail/{id}', 'OrdersController@show')->name('admin.orders.show');


    $router->get('import/orders', 'OrdersController@ordersImport')->name('admin.orders.import');
    $router->post('import/order_send', 'OrdersController@importOrderSend')->name('admin.orders.saveimport');
    $router->get('deliver/{id}', 'OrdersController@ordersDeliver')->name('admin.orders.deliver');
    $router->get('deliver/{id}/edit', 'OrdersController@ordersDeliverEdit')->name('admin.orders.deliver.edit');
    $router->get('multiple_deliver', 'OrdersController@ordersMultipleDeliver')->name('admin.orders.multiple.deliver');

    $router->post('doDeliver', 'OrdersController@deliver')->name('admin.orders.savedeliver');

    $router->post('close/{id}', 'OrdersController@close')->name('admin.orders.close');

    $router->get('export/job', 'OrdersController@exportJob')->name('admin.orders.export.job');

    $router->get('export/getExportData', 'OrdersController@getExportData')->name('admin.orders.getExportData');

    $router->get('editAddress/{id}', 'OrdersController@editAddress')->name('admin.orders.editAddress');
    $router->post('postAddress', 'OrdersController@postAddress')->name('admin.orders.postAddress');
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