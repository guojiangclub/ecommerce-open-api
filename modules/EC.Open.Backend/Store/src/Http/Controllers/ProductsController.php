<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/5
 * Time: 15:09
 */

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;


use Carbon\Carbon;
use iBrand\EC\Open\Backend\Store\Model\Product;
use Excel;
use iBrand\Backend\Http\Controllers\Controller;
use Maatwebsite\Excel\Collections\SheetCollection;

class ProductsController extends Controller
{
    protected $cache;

    public function __construct()
    {
        $this->cache = cache();
    }

    public function create()
    {
        return view('store-backend::product.create');
    }

    public function importBarCodeModal()
    {
        $calculateUrl = route('admin.goods.barCode.getImportDataCount') . '?path=' . request('path');
        return view('store-backend::product.import_modal', compact('calculateUrl'));
    }

    /**
     * 计算导入的数据数量
     * @param $path
     */
    public function getImportDataCount()
    {
        $filename = 'public' . request('path');

        Excel::load($filename, function ($reader) {
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $count = count($reader->toArray()) - 1;
            $expiresAt = Carbon::now()->addMinutes(30);
            $this->cache->forget('barCodeImportCount');
            $this->cache->put('barCodeImportCount', $count, $expiresAt);
        });

        $limit = 20;
        $total = ceil($this->cache->get('barCodeImportCount') / $limit);

        $url = route('admin.goods.barCode.saveImportData', ['total' => $total, 'limit' => $limit, 'path' => request('path')]);
        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url]);
    }

    /**
     * 执行导入操作
     * @return mixed
     */
    public function saveImportData()
    {
        $filename = 'public' . request('path');
        $conditions = [];
        $page = request('page') ? request('page') : 1;
        $total = request('total');
        $limit = request('limit');

        if ($page > $total) {
            return $this->ajaxJson(true, ['status' => 'complete']);
        }

        Excel::load($filename, function ($reader) use ($conditions, $page, $limit) {
            $reader = $reader->get();
            if ($reader instanceof SheetCollection) {
                $reader = $reader->first();
            }
            $data = $reader->forPage($page, $limit)->toArray();

            if (count($data) > 0) {

                foreach ($data as $key => $value) {

                    $bar_code = trim($value['bar_code']);
                    $sku = trim($value['sku']);

                    if ($bar_code AND $sku) {
                        if ($product = Product::where('sku', $sku)->first() AND !$product->bar_code) {
                            $product->bar_code = $bar_code;
                            $product->save();
                        }
                    }
                }
            }
        });

        $url = route('admin.goods.barCode.saveImportData', ['page' => $page + 1, 'total' => $total, 'limit' => $limit, 'path' => request('path')]);

        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url, 'current_page' => $page, 'total' => $total]);
    }
}