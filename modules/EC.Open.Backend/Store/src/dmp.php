<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/3/29
 * Time: 16:35
 */
return [
    'color' => [
        '黑色',
        '绿色',
        '白色',
        '紫色',
        '红色',
        '黄色',
        '蓝色',
        '棕色',
        '灰色',
        '彩色'
    ],
    'produce' => false,

    'page_title' => env('BACKEND_PAGE_TITLE', 'ibrand后台管理系统'),

    'short_title' =>env('BACKEND_PAGE_TITLE', 'ibrand'),

    'copyright' =>env('BACKEND_PAGE_TITLE', 'ibrand © 2018-2019'),

    'technical_support' => env('BACKEND_PAGE_TITLE', '上海艾游文化传播有限公司'),

    'login_logo' => env('BACKEND_PAGE_TITLE', '/assets/backend/images/tnf-logo.png'),

    'setting-cache'=>env('SETTING_CACHE',true),
];