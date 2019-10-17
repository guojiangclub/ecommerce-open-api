<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$router->post('oauth/sms', 'AuthController@smsLogin')->name('api.oauth.sms');

$router->post('oauth/MiniProgramLogin', 'MiniProgramLoginController@login')->name('api.oauth.miniprogram.login');
$router->post('oauth/MiniProgramMobileLogin', 'MiniProgramLoginController@mobileLogin')->name('api.oauth.miniprogram.mobile.login');
//小程序获取openId
$router->get('oauth/miniprogram/openid', 'MiniProgramLoginController@getOpenIdByCode');

$router->get('store/list', 'GoodsController@index')->name('api.goods.list');
$router->get('store/detail/{id}', 'GoodsController@show')->name('api.goods.detail');
$router->get('store/detail/{id}/stock', 'GoodsController@getStock')->name('api.goods.detail.stock');
$router->get('store/detail/{id}/share/img', 'GoodsController@shareImg')->name('api.goods.detail.share.img');

$router->get('home', 'HomeController@index')->name('api.home.index');
$router->get('category', 'HomeController@category')->name('api.home.category');
$router->get('micro/page/{code}', 'MicroPageController@index')->name('api.micro.page.index');

$router->post('wechat/notify', 'WechatPayNotifyController@notify');

$router->post('shoppingCart/discount', 'DiscountController@shoppingCartDiscount')->name('api.shopping.cart.discount');

$router->group(config('ibrand.ec-open-api.routeAuthAttributes'), function ($router) {

    /************************* 购物车 **********************/
    $router->post('shopping/cart', 'ShoppingCartController@store')->name('api.shopping.cart.store');
    $router->get('shopping/cart', 'ShoppingCartController@index')->name('api.shopping.cart');
    $router->put('shopping/cart/{id}', 'ShoppingCartController@update')->name('api.shopping.cart.put');
    $router->delete('shopping/cart/{id}', 'ShoppingCartController@delete')->name('api.shopping.cart.delete');
    $router->post('shopping/cart/clear', 'ShoppingCartController@clear')->name('api.shopping.cart.clear');
    $router->get('shopping/cart/count', 'ShoppingCartController@count')->name('api.shopping.cart.count');

    /************************* 购物流程 **********************/
    $router->post('shopping/order/checkout', 'ShoppingController@checkout')->name('api.shopping.order.checkout');
    $router->post('shopping/order/confirm', 'ShoppingController@confirm')->name('api.shopping.order.confirm');
    $router->post('shopping/order/charge', 'WechatPayController@createCharge')->name('api.shopping.order.charge');
    $router->post('shopping/order/paid', 'PaymentController@paidSuccess')->name('api.shopping.order.paid');
    $router->post('shopping/order/cancel', 'ShoppingController@cancel')->name('api.shopping.order.cancel');
    $router->post('shopping/order/received', 'ShoppingController@received')->name('api.shopping.order.received');
    $router->post('shopping/order/delete', 'ShoppingController@delete')->name('api.shopping.order.delete');
    $router->post('shopping/order/review', 'ShoppingController@review')->name('api.shopping.order.review');
    $router->post('shopping/order/delivery', 'ShoppingController@delivery')->name('api.order.delivery');

    /************************* 收货地址 **********************/
    $router->get('address', 'AddressController@index')->name('api.address.list');
    $router->post('address', 'AddressController@store')->name('api.address.store');
    $router->put('address/{id}', 'AddressController@update')->name('api.address.update');
    $router->get('address/{id}', 'AddressController@show')->where('id', '[0-9]+')->name('api.address.show');
    $router->delete('address/{id}', 'AddressController@delete')->name('api.address.delete');
    $router->get('address/default', 'AddressController@default')->name('api.address.default');

    /*************************** 我的收藏 ********************/
    $router->get('favorite', 'FavoriteController@index')->name('api.favorite');
    $router->post('favorite', 'FavoriteController@store')->name('api.favorite.store');
    $router->delete('favorite', 'FavoriteController@delete')->name('api.favorite.delete');
    $router->post('favorite/delFavs', 'FavoriteController@delFavs')->name('api.favorite.delFavs');
    $router->get('favorite/isfav', 'FavoriteController@getIsFav')->name('api.favorite.isFav');

    /************************* 用户 **********************/
    $router->get('me', 'UserController@me')->name('api.me');
    $router->get('users/ucenter', 'UserController@ucenter')->name('api.user.ucenter');
    $router->post('users/update/info', 'UserController@updateInfo')->name('api.user.update.info');
    $router->post('users/update/mobile', 'UserController@updateMobile')->name('api.user.update.mobile');
    $router->post('users/upload/avatar', 'UserController@uploadAvatar')->name('api.user.upload.avatar');
    $router->get('order/list', 'OrderController@getOrders')->name('api.order.list');
    $router->get('order/{order_no}', 'OrderController@getOrderDetails')->name('api.order');

    $router->get('coupon', 'CouponController@index')->name('api.coupon.list');
    $router->get('coupon/{id}', 'CouponController@show')->name('api.coupon.show');

    /************************* 促销活动和优惠券 **********************/
    $router->post('discount', 'DiscountController@create')->name('api.discount.create');
    $router->post('coupon', 'CouponController@create')->name('api.coupon.create');
    $router->post('coupon/take', 'CouponController@take')->name('api.coupon.take');
});
