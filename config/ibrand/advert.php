<?php

/*
 * This file is part of ibrand/advert.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'models' => [
        'goods' => GuoJiangClub\Component\Product\Models\Goods::class,
        'discount' => GuoJiangClub\Component\Discount\Models\Discount::class,
        'category' => GuoJiangClub\Component\Category\Category::class,
        'microPage' => GuoJiangClub\Component\Advert\Models\MicroPage::class
    ],
    'type'=>[
        'store_detail'=>[
            'name'=>'商品详情页',
            'page'=>'/pages/store/detail/detail?id='
        ],
        'store_list'=>[
            'name'=>'商品分类页',
            'page'=>'/pages/store/list/list?c_id='
        ],
        'other_micro'=>[
            'name'=>'微页面',
            'page'=>'/pages/index/microPages/microPages?id='
        ],
        'other_links'=>[
            'name'=>'公众号文章',
            'page'=>'/pages/other/links/links?url='
        ],
        'other'=>[
            'name'=>'自定义',
            'page'=>'other'
        ],
    ],

    'meta'=>[

        '下边距'=>[

            'name'=>'margin_bottom',

            'value'=>0
        ],

    ]

];
