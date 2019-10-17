<?php

/*
 * This file is part of ibrand/distribution-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Backend\Store\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreBackendTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $lastOrder = DB::table(config('admin.database.menu_table'))->max('order');


        $parent = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => 0,
            'order' => $lastOrder++,
            'title' => '商城管理',
            'icon' => 'iconfont icon-shangchengguanli-',
            'blank' => 1,
            'uri' => 'store/goods',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);


        $parent_setting = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '商城设置',
            'icon' => 'iconfont icon-shangchengshezhi-',
            'blank' => 1,
            'uri' => '',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);


        $setting_menu_list = [
            ['parent_id' => $parent_setting,
                'order' => $lastOrder++,
                'title' => '微页面',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/setting/micro/page',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())],
            ['parent_id' => $parent_setting,
                'order' => $lastOrder++,
                'title' => '模块管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/setting/micro/page/compoent',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())],

        ];

        DB::table(config('admin.database.menu_table'))->insert($setting_menu_list);


        $parent_goods = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '商品管理',
            'icon' => 'iconfont icon-shangpinshezhi-',
            'blank' => 1,
            'uri' => '',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        $goods_menu_list = [
            ['parent_id' => $parent_goods,
                'order' => $lastOrder++,
                'title' => '模型管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/models',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())],
            [
                'parent_id' => $parent_goods,
                'order' => $lastOrder++,
                'title' => '规格管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/specs',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ],
            [
                'parent_id' => $parent_goods,
                'order' => $lastOrder++,
                'title' => '参数管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/attribute',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ],
            [
                'parent_id' => $parent_goods,
                'order' => $lastOrder++,
                'title' => '品牌管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/brand',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ],
            [
                'parent_id' => $parent_goods,
                'order' => $lastOrder++,
                'title' => '分类管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/category',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ],
            [
                'parent_id' => $parent_goods,
                'order' => $lastOrder++,
                'title' => '商品列表',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/goods',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]
        ];
        DB::table(config('admin.database.menu_table'))->insert($goods_menu_list);

        $parent_discount = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '促销管理',
            'icon' => 'iconfont icon-cuxiaoguanli',
            'blank' => 1,
            'uri' => '',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        $discount_menu_list = [
            ['parent_id' => $parent_discount,
                'order' => $lastOrder++,
                'title' => '促销活动管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/promotion/discount',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())],
            [
                'parent_id' => $parent_discount,
                'order' => $lastOrder++,
                'title' => '优惠券管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/promotion/coupon',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]
        ];
        DB::table(config('admin.database.menu_table'))->insert($discount_menu_list);

        $parent_order = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '订单管理',
            'icon' => 'iconfont icon-dingdanguanli',
            'blank' => 1,
            'uri' => '',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        $order_menu_list = [
            ['parent_id' => $parent_order,
                'order' => $lastOrder++,
                'title' => '订单列表',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/order?status=all',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())],

            [
                'parent_id' => $parent_order,
                'order' => $lastOrder++,
                'title' => '评论管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/comments?status=show',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ],
        ];
        DB::table(config('admin.database.menu_table'))->insert($order_menu_list);


        $parent_shipping = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '物流管理',
            'icon' => 'iconfont icon-wuliuguanli',
            'blank' => 1,
            'uri' => '',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        $shipping_menu_list = [
            ['parent_id' => $parent_shipping,
                'order' => $lastOrder++,
                'title' => '物流列表',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/shippingmethod/company',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())],
        ];
        DB::table(config('admin.database.menu_table'))->insert($shipping_menu_list);

        $parent_image = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '图片管理',
            'icon' => 'iconfont icon-tupianguanli',
            'blank' => 1,
            'uri' => '',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        $image_menu_list = [
            ['parent_id' => $parent_image,
                'order' => $lastOrder++,
                'title' => '图片列表',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/image/file?category_id=1',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())],
            ['parent_id' => $parent_image,
                'order' => $lastOrder++,
                'title' => '图片分类管理',
                'icon' => '',
                'blank' => 1,
                'uri' => 'store/image/category',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())],
        ];
        DB::table(config('admin.database.menu_table'))->insert($image_menu_list);


    }
}
