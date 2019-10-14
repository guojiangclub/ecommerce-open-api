<?php
namespace GuoJiangClub\EC\Open\Backend\Store\Console;

use GuoJiangClub\EC\Open\Backend\Store\Model\Spec;
use GuoJiangClub\EC\Open\Backend\Store\Model\SpecsValue;
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

    protected $signature = 'ibrand:store-default-specs';

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
            ['spec_id' => 2, 'name' => '黑色', 'rgb' => '000000'],
            ['spec_id' => 2, 'name' => '橘红色', 'rgb' => 'ff7500'],
            ['spec_id' => 2, 'name' => '玫红色', 'rgb' => 'df1b76'],
            ['spec_id' => 2, 'name' => '粉红色', 'rgb' => 'ffb6c1'],
            ['spec_id' => 2, 'name' => '红色', 'rgb' => 'ff0000'],
            ['spec_id' => 2, 'name' => '藕色', 'rgb' => 'eed0d8'],
            ['spec_id' => 2, 'name' => '西瓜红', 'rgb' => 'f05654'],
            ['spec_id' => 2, 'name' => '酒红色', 'rgb' => '990000'],
            ['spec_id' => 2, 'name' => '军绿色', 'rgb' => '5d762a'],
            ['spec_id' => 2, 'name' => '浅绿色', 'rgb' => '98fb98'],
            ['spec_id' => 2, 'name' => '绿色', 'rgb' => '008000'],
            ['spec_id' => 2, 'name' => '翠绿色', 'rgb' => '0aa344'],
            ['spec_id' => 2, 'name' => '青色', 'rgb' => '00e09e'],
            ['spec_id' => 2, 'name' => '天蓝色', 'rgb' => '44cef6'],
            ['spec_id' => 2, 'name' => '孔雀蓝', 'rgb' => '00a4c5'],
            ['spec_id' => 2, 'name' => '宝蓝色', 'rgb' => '4b5cc4'],
            ['spec_id' => 2, 'name' => '浅蓝色', 'rgb' => 'd2f0f4'],
            ['spec_id' => 2, 'name' => '深蓝色', 'rgb' => '041690'],
            ['spec_id' => 2, 'name' => '湖蓝色', 'rgb' => '30dff3'],
            ['spec_id' => 2, 'name' => '蓝色', 'rgb' => '0000fe'],
            ['spec_id' => 2, 'name' => '藏青色', 'rgb' => '2e4e7e'],
            ['spec_id' => 2, 'name' => '浅紫色', 'rgb' => 'ede0e6'],
            ['spec_id' => 2, 'name' => '深紫色', 'rgb' => '430653'],
            ['spec_id' => 2, 'name' => '紫红色', 'rgb' => '8b0062'],
            ['spec_id' => 2, 'name' => '紫罗兰', 'rgb' => 'b7ace4'],
            ['spec_id' => 2, 'name' => '紫色', 'rgb' => '800080'],
            ['spec_id' => 2, 'name' => '咖啡色', 'rgb' => '603912'],
            ['spec_id' => 2, 'name' => '巧克力色', 'rgb' => 'd2691e'],
            ['spec_id' => 2, 'name' => '栗色', 'rgb' => '60281e'],
            ['spec_id' => 2, 'name' => '浅棕色', 'rgb' => 'b35c44'],
            ['spec_id' => 2, 'name' => '深卡其布色', 'rgb' => 'bdb76b'],
            ['spec_id' => 2, 'name' => '深棕色', 'rgb' => '7c4b00'],
            ['spec_id' => 2, 'name' => '褐色', 'rgb' => '855b00'],
            ['spec_id' => 2, 'name' => '驼色', 'rgb' => 'a88462'],
            ['spec_id' => 2, 'name' => '浅灰色', 'rgb' => 'e4e4e4'],
            ['spec_id' => 2, 'name' => '深灰色', 'rgb' => '666666'],
            ['spec_id' => 2, 'name' => '灰色', 'rgb' => '808080'],
            ['spec_id' => 2, 'name' => '银色', 'rgb' => 'c0c0c0']
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