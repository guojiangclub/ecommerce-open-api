<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$router->group(['prefix' => 'admin/member'], function () use ($router) {
    $router->get('card', 'MemberCardController@index')->name('admin.member.card');
    $router->get('card/create', 'MemberCardController@create')->name('admin.member.card.create');
    $router->get('card/update/{id}', 'MemberCardController@update')->name('admin.member.card.update');
    $router->post('card/store', 'MemberCardController@store')->name('admin.member.card.store');
    $router->post('card/status', 'MemberCardController@status')->name('admin.member.card.status');
    $router->get('card/delMemberCard', 'MemberCardController@delMemberCard')->name('admin.member.card.delMemberCard');
    $router->post('card/wxCard', 'MemberCardController@wxCard')->name('admin.member.card.wxCard');
    $router->post('card/wxCardActivate', 'MemberCardController@wxCardActivate')->name('admin.member.card.wxCardActivate');

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

//    $router->get('users/deleted', 'UserController@deleted')->name('admin.users.deleted');
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

    $router->get('users/edit/balance/list/{id}', 'BalanceController@getBalancePaginate')->name('admin.users.edit.balance.list');
    $router->post('users/edit/balance/add', 'BalanceController@operateBalance')->name('admin.users.edit.balance.operateBalance');    

    $router->group(['prefix' => 'user/{id}', 'where' => ['id' => '[0-9]+']], function () use ($router) {
        $router->get('restore', 'UserController@restore')->name('admin.user.restore');
        $router->get('mark/{status}', 'UserController@mark')->name('admin.user.mark')->where(['status' => '[0,1,2]']);
        $router->get('password/change', 'UserController@changePassword')->name('admin.user.change-password');
        $router->post('password/change', 'UserController@updatePassword')->name('admin.user.change-password');
    });

    $router->get('users/importUser', 'UserController@importUser')->name('admin.users.importUser');
    $router->post('users/importUser/saveImport', 'UserController@saveImport')->name('admin.users.importUser.saveImport');
});

//分组
$router->group(['prefix' => 'admin/member/group'], function () use ($router) {
    $router->get('/', 'GroupController@index')->name('admin.users.group.list');
    $router->get('create', 'GroupController@create')->name('admin.users.group.create');
    $router->post('store', 'GroupController@store')->name('admin.users.group.store');
    $router->get('edit/{id}', 'GroupController@edit')->name('admin.users.group.edit');
    $router->post('delete/{id}', 'GroupController@delete')->name('admin.users.group.delete');
});

//会员
$router->group(['prefix' => 'admin/member/groups'], function () use ($router) {
    $router->get('grouplist', 'UserGroupController@grouplist')->name('admin.users.grouplist');
    $router->get('groupcreate', 'UserGroupController@groupcreate')->name('admin.users.groupcreate');
    $router->post('groupstore', 'UserGroupController@groupstore')->name('admin.users.groupstore');
    $router->post('groupchange/{id}', 'UserGroupController@groupupdate')->name('admin.users.groupchange ');
    $router->post('group/{id}/del', 'UserGroupController@deletedGroup')->name('admin.users.deletedGroup');
});

//管理员
$router->group(['prefix' => 'admin/member/manager'], function () use ($router) {
    $router->get('/', 'AdminController@index')->name('admin.manager.index');
    $router->get('create', 'AdminController@create')->name('admin.manager.create');
    $router->get('edit/{id}', 'AdminController@edit')->name('admin.manager.edit');

    $router->post('store', 'AdminController@store')->name('admin.manager.store');
    $router->patch('update/{id}', 'AdminController@update')->name('admin.manager.update');

    $router->delete('destroy/{id}', 'AdminController@destroy')->name('admin.manager.delete');

    $router->get('password/change/{id}', 'AdminController@changePassword')->name('admin.manager.changePassword');
    $router->post('password/change/{id}', 'AdminController@updatePassword')->name('admin.manager.updatePassword');

    $router->get('loginLog', 'AdminController@loginLog')->name('admin.manager.loginLog');
    $router->get('login-log/datatable', 'AdminController@getLoginLog')->name('admin.manager.log.datatable');
});

//员工
//$router->group(['middleware' => 'admin_role:administrator', 'prefix' => 'admin/staff'], function () use ($router) {

$router->group(['prefix' => 'admin/member/staff'], function () use ($router) {

    $router->get('/', 'StaffController@index')->name('admin.staff.index');
    $router->get('create', 'StaffController@createStaff')->name('admin.staff.create');
    $router->post('store', 'StaffController@store')->name('admin.staff.store');
    $router->patch('update/{id}', 'StaffController@update')->name('admin.staff.update');
    $router->get('staffimport', 'StaffController@staffImport')->name('admin.staff.staffimport');
    $router->post('saveimport', 'StaffController@saveImport')->name('admin.staff.saveimport');
    $router->get('/{id}/info', 'StaffController@edit')->name('admin.staff.edit');
});

$router->group(['prefix' => 'admin/member/RoleManagement'], function () use ($router) {
    $router->get('role/index', 'RoleController@index')->name('admin.RoleManagement.role.index');
    $router->get('role/create', 'RoleController@create')->name('admin.RoleManagement.role.create');
    $router->post('role/store', 'RoleController@store')->name('admin.RoleManagement.role.store');
    $router->post('role/{id}/delete', 'RoleController@delete')->name('admin.RoleManagement.role.delete');
    $router->patch('role/{id}/update', 'RoleController@update')->name('admin.RoleManagement.role.update');
    $router->get('role/{id}/edit', 'RoleController@edit')->name('admin.RoleManagement.role.edit');
    $router->get('roleUser/{id}/edit', 'RoleController@roleUserEdit')->name('admin.RoleManagement.roleUser.edit');
    $router->patch('roleUser/{id}/update', 'RoleController@roleUserUpdate')->name('admin.RoleManagement.userRole.update');

    $router->get('role/userList/{id}', 'RoleController@userList')->name('admin.RoleManagement.role.userList');

    // 批量用户分配角色
    $router->get('role/{id}/userModal', 'RoleController@userModal')->name('admin.RoleManagement.role.userModal');
    $router->post('role/allotRole', 'RoleController@allotAddRole')->name('admin.RoleManagement.role.allotAddRole');
    $router->get('role/UsersSearchList', 'RoleController@UsersSearchList')->name('admin.RoleManagement.role.UsersSearchList');

    $router->post('role/{id}/allotDelRole', 'RoleController@allotDelRole')->name('admin.RoleManagement.role.allotDelRole');

    $router->get('role/importUser', 'RoleController@importUser')->name('admin.RoleManagement.role.importUser');
    $router->post('role/importUser/saveImport', 'RoleController@saveImport')->name('admin.RoleManagement.role.importUser.saveImport');

    $router->get('permissions/index', 'PermissionController@index')->name('admin.RoleManagement.permission.index');
    $router->get('permissions/create', 'PermissionController@create')->name('admin.RoleManagement.permission.create');
    $router->post('permissions/store', 'PermissionController@store')->name('admin.RoleManagement.permission.store');
    $router->post('permissions/{id}/delete', 'PermissionController@delete')->name('admin.RoleManagement.permission.delete');
    $router->patch('permissions/{id}/update', 'PermissionController@update')->name('admin.RoleManagement.permission.update');
    $router->get('permissions/{id}/edit', 'PermissionController@edit')->name('admin.RoleManagement.permission.edit');
});

//数据统计
$router->group(['prefix' => 'admin/member/statistics'], function () use ($router) {
    $router->get('index', 'StatisticsController@index')->name('admin.statistics.index');
});

//会员卡
$router->group(['prefix' => 'admin/member/vip/card'], function () use ($router) {
    $router->get('/', 'CardController@index')->name('admin.card.index');
    $router->get('exportExcel', 'CardController@exportExcel')->name('admin.card.exportExcel');
    $router->get('download/{url}', 'CardController@download')->name('admin.card.download');

    $router->get('edit/{id}', 'CardController@edit')->name('admin.card.edit');
    $router->post('update/{id}', 'CardController@update')->name('admin.card.update');

    $router->get('getExportData', 'CardController@getExportData')->name('admin.card.getExportData');
});

//会员积分
$router->group(['prefix' => 'admin/member/points'], function () use ($router) {
    $router->get('/', 'PointController@index')->name('admin.users.pointlist');

    $router->get('offline', 'PointController@pointOffline')->name('admin.member.points.offline');

    $router->get('default', 'PointController@pointDefault')->name('admin.member.points.default');

    $router->group(['prefix' => 'import'], function () use ($router) {
        $router->get('importPointModal', 'PointController@importPointModal')->name('admin.member.points.importPointModal');
        $router->get('getImportDataCount', 'PointController@getImportDataCount')->name('admin.member.points.getImportDataCount');
        $router->get('saveImportData', 'PointController@saveImportData')->name('admin.member.points.saveImportData');
    });
});

//会员余额
$router->group(['prefix' => 'admin/member/balances'], function () use ($router) {
    $router->get('/', 'BalanceController@index')->name('admin.users.balances.list');
    $router->get('importBalance/modal', 'BalanceController@importBalance')->name('admin.users.balance.importBalance');
    $router->post('importBalance/saveBalanceImport', 'BalanceController@saveBalanceImport')->name('admin.users.balance.saveBalanceImport');    
});

$router->group(['prefix' => 'admin/member/entity'], function () use ($router) {
    $router->get('/', 'EntityCardController@index')->name('admin.users.entity.list');
    $router->get('getExportData', 'EntityCardController@getExportData')->name('admin.users.entity.getExportData');
    $router->get('zipFiles', 'EntityCardController@zipFiles')->name('admin.users.entity.zipFiles');
});

//储值管理
$router->group(['prefix' => 'admin/member/recharge'], function () use ($router) {
    $router->get('/', 'RechargeController@index')->name('admin.users.recharge.index');

    $router->get('/create', 'RechargeController@create')->name('admin.users.recharge.create');

    $router->post('/store', 'RechargeController@store')->name('admin.users.recharge.store');

    $router->get('/{id}/edit', 'RechargeController@edit')->name('admin.users.recharge.edit');

    $router->post('/{id}/update', 'RechargeController@update')->name('admin.users.recharge.update');

    $router->post('/{id}/delete', 'RechargeController@destroy')->name('admin.users.recharge.delete');

    $router->post('/toggleStatus', 'RechargeController@toggleStatus')->name('admin.users.recharge.toggleStatus');

    $router->get('/api/coupon', 'RechargeController@coupon_api')->name('admin.users.recharge.api.coupon');
});

$router->group(['prefix' => 'admin/member/log_recharge'], function () use ($router) {
    $router->get('/', 'RechargeController@log')->name('admin.users.recharge.log.index');
});

//消息管理
$router->group(['prefix' => 'admin/member/message'], function () use ($router) {
    $router->get('/', 'MessageController@index')->name('admin.users.message.index');
    $router->get('create', 'MessageController@create')->name('admin.users.message.create');

    $router->post('store', 'MessageController@store')->name('admin.users.message.store');
});

$router->group(['prefix' => 'admin/member/data'], function () use ($router) {
    $router->get('/', 'DataController@index')->name('admin.users.data.index');
});
