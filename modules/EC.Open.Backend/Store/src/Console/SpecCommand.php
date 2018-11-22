<?php
namespace iBrand\EC\Open\Backend\Store\Console;

use iBrand\EC\Open\Backend\Store\Model\Spec;
use iBrand\EC\Open\Backend\Store\Model\SpecsValue;
use Illuminate\Console\Command;
use DB;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/4
 * Time: 22:16
 */
class SpecCommand extends Command
{

    protected $signature = 'el_goods_spec:factory';

    protected $description = 'create specs default data.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        return $this->generateSpecData();
    }

    private function generateSpecData()
    {

        if (Spec::all()->count() == 0) {
            Spec::create([
                'name' => '尺寸',
                'display_name' => '尺寸',
                'type' => 1
            ]);

            Spec::create(['name' => '颜色',
                'display_name' => '颜色',
                'type' => 2]);

        }

        $specValue = [
            ['spec_id' => 2, 'name' => '黑色'],
            ['spec_id' => 2, 'name' => '橘红色'],
            ['spec_id' => 2, 'name' => '玫红色'],
            ['spec_id' => 2, 'name' => '粉红色'],
            ['spec_id' => 2, 'name' => '红色'],
            ['spec_id' => 2, 'name' => '藕色'],
            ['spec_id' => 2, 'name' => '西瓜红'],
            ['spec_id' => 2, 'name' => '酒红色'],
            ['spec_id' => 2, 'name' => '军绿色'],
            ['spec_id' => 2, 'name' => '浅绿色'],
            ['spec_id' => 2, 'name' => '绿色'],
            ['spec_id' => 2, 'name' => '翠绿色'],
            ['spec_id' => 2, 'name' => '青色'],
            ['spec_id' => 2, 'name' => '天蓝色'],
            ['spec_id' => 2, 'name' => '孔雀蓝'],
            ['spec_id' => 2, 'name' => '宝蓝色'],
            ['spec_id' => 2, 'name' => '浅蓝色'],
            ['spec_id' => 2, 'name' => '深蓝色'],
            ['spec_id' => 2, 'name' => '湖蓝色'],
            ['spec_id' => 2, 'name' => '蓝色'],
            ['spec_id' => 2, 'name' => '藏青色'],
            ['spec_id' => 2, 'name' => '浅紫色'],
            ['spec_id' => 2, 'name' => '深紫色'],
            ['spec_id' => 2, 'name' => '紫红色'],
            ['spec_id' => 2, 'name' => '紫罗兰'],
            ['spec_id' => 2, 'name' => '紫色'],
            ['spec_id' => 2, 'name' => '咖啡色'],
            ['spec_id' => 2, 'name' => '巧克力色'],
            ['spec_id' => 2, 'name' => '栗色'],
            ['spec_id' => 2, 'name' => '浅棕色'],
            ['spec_id' => 2, 'name' => '深卡其布色'],
            ['spec_id' => 2, 'name' => '深棕色'],
            ['spec_id' => 2, 'name' => '褐色'],
            ['spec_id' => 2, 'name' => '驼色'],
            ['spec_id' => 2, 'name' => '浅灰色'],
            ['spec_id' => 2, 'name' => '深灰色'],
            ['spec_id' => 2, 'name' => '灰色'],
            ['spec_id' => 2, 'name' => '银色']
        ];

        if (SpecsValue::where('spec_id', 2)->get()->count() == 0) {
            foreach ($specValue as $item) {
                SpecsValue::create($item);
            }
        }

        if (SpecsValue::where('spec_id', 1)->get()->count() == 0) {
            SpecsValue::create(['spec_id' => 1, 'name' => 'S']);
        }
    }


}