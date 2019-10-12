<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-03-01
 * Time: 14:07
 */

namespace GuoJiangClub\EC\Open\Backend\Store\Console;

use GuoJiangClub\EC\Open\Backend\Album\Models\ImageCategory;
use Illuminate\Console\Command;

use GuoJiangClub\EC\Open\Backend\Store\Model\Category as ElCategory;

class SetDefaultValueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibrand:store-default-value';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install ibrand\'s store system default value.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //1. 设置站点信息默认值
        if (empty(settings()->getSetting('store_name'))) {
            settings()->setSetting(['store_name' => 'ibrand']);
        }


        if (empty(settings()->getSetting('goods_spec_color'))) {
            settings()->setSetting(['goods_spec_color' => ['黑色',
                '绿色',
                '白色',
                '紫色',
                '红色',
                '黄色',
                '蓝色',
                '棕色',
                '灰色']]);
        }

        $point_enabled = settings()->getSetting('point_enabled');
        if (empty($point_enabled)) {
            settings()->setSetting(['point_enabled' => 0]);
        }

        //前端是否显示库存
        $shop_show_store = settings()->getSetting('shop_show_store');
        if (empty($shop_show_store)) {
            settings()->setSetting(['shop_show_store' => 0]);
        }

        if (!ImageCategory::where('name', '默认分组')->first()) {
            ImageCategory::create(['parent_id' => 0, 'name' => '默认分组', 'sort' => 0]);
        }

        $category = ElCategory::all();
        if (count($category) <= 0) {
            ElCategory::create([              
                'name' => '默认分类',
                'description' => '默认分类',
                'sort' => 1,
                'status' => 1,
                'level' => 1,
                'parent_id' => 0,
            ]);
        }
    }
}
