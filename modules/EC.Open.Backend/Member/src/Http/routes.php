<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$router->group(['prefix' => 'admin/member'], function () use ($router) {

    $router->resource('users', 'UserController', ['except' => ['show'],
        'names' => [
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ],
    ]);

    $router->get('users/banned', 'UserController@banned')->name('admin.users.banned');
    $router->post('users/getexport', 'UserController@getexport')->name('admin.users.getexport');
    $router->get('users/userexport', 'UserController@userexport')->name('admin.users.userexport');
    $router->get('users/download', 'UserController@download')->name('admin.users.download');

    $router->get('account/confirm/resend/{user_id}', 'UserController@resendConfirmationEmail')->name('admin.account.confirm.resend');

    $router->get('users/{id}/integrallist', 'UserController@integrallist')->name('admin.users.integrallist');
    $router->get('users/{id}/couponslist', 'UserController@couponslist')->name('admin.users.couponslist');
    $router->get('users/{id}/orderslist', 'UserController@orderslist')->name('admin.users.orderslist');

    $router->get('users/getUserPointData/{id}/{type}', 'UserController@getUserPointData')->name('admin.users.getUserPointList');

    $router->post('users/addPoint', 'UserController@addPoint')->name('admin.users.addPoint');

    $router->delete('users/{id}/everDelete', 'UserController@everDelete')->name('admin.users.everDelete');

    $router->get('users/getExportData', 'UserController@getExportData')->name('admin.users.getExportData');


    $router->group(['prefix' => 'user/{id}', 'where' => ['id' => '[0-9]+']], function () use ($router) {
        $router->get('restore', 'UserController@restore')->name('admin.user.restore');
        $router->get('mark/{status}', 'UserController@mark')->name('admin.user.mark')->where(['status' => '[0,1,2]']);
        $router->get('password/change', 'UserController@changePassword')->name('admin.user.change-password');
        $router->post('password/change', 'UserController@updatePassword')->name('admin.user.change-password');
    });

    $router->get('users/importUser', 'UserController@importUser')->name('admin.users.importUser');
    $router->post('users/importUser/saveImport', 'UserController@saveImport')->name('admin.users.importUser.saveImport');
});

//会员积分
$router->group(['prefix' => 'admin/member/points'], function () use ($router) {
    $router->get('/', 'PointController@index')->name('admin.users.pointlist');

    $router->group(['prefix' => 'import'], function () use ($router) {
        $router->get('importPointModal', 'PointController@importPointModal')->name('admin.member.points.importPointModal');
        $router->get('getImportDataCount', 'PointController@getImportDataCount')->name('admin.member.points.getImportDataCount');
        $router->get('saveImportData', 'PointController@saveImportData')->name('admin.member.points.saveImportData');
    });
});