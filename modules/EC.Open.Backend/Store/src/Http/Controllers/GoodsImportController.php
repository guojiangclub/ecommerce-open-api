<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/7/12
 * Time: 12:10
 */

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use iBrand\EC\Open\Backend\Store\Service\ImportGoodsService;
use Excel;
use iBrand\Backend\Http\Controllers\Controller;
use Maatwebsite\Excel\Collections\SheetCollection;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class GoodsImportController extends Controller
{

    protected $cache;
    protected $importService;

    public function __construct(ImportGoodsService $importGoodsService)
    {
        $this->cache = cache();
        $this->importService = $importGoodsService;
    }

    public function index()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('商品参数列表');

            $content->breadcrumb(
                ['text' => '参数管理', 'url' => 'store/attribute', 'no-pjax' => 1],
                ['text' => '商品参数列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '参数管理']

            );
            $content->body(view('store-backend::commodity.import.import'));
        });
        
    }

    public function importGoodsModal()
    {        
        $calculateUrl = route('admin.goods.import.getImportDataCount') . '?path=' . request('path') . '&import_type=' . request('import_type');
      
        return view('store-backend::commodity.import.import_modal', compact('calculateUrl'));
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
            $expiresAt = Carbon::now()->addMinutes(60);
            $this->cache->forget('goodsImportCount');
            $this->cache->put('goodsImportCount', $count, $expiresAt);
        });

        $limit = 10;
        $total = ceil($this->cache->get('goodsImportCount') / $limit);

        $url = route('admin.goods.import.saveImportData', ['total' => $total, 'limit' => $limit, 'path' => request('path'), 'import_type' => request('import_type')]);
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
        $import_type = request('import_type');
        if ($page > $total) {
            return $this->ajaxJson(true, ['status' => 'complete']);
        }

        Excel::load($filename, function ($reader) use ($conditions, $page, $limit, $import_type) {
            $reader = $reader->get();
            if ($reader instanceof SheetCollection) {
                $reader = $reader->first();
            }
            $data = $reader->forPage($page, $limit)->toArray();

            if (count($data) > 0) {
                if ($import_type == 'goods') {
                    $this->importService->handleImportGoods($data);
                } elseif ($import_type == 'add') {
                    $this->importService->handleImportAddGoods($data);
                }

            }
        });

        $url = route('admin.goods.import.saveImportData', ['page' => $page + 1, 'total' => $total, 'limit' => $limit, 'path' => request('path'), 'import_type' => $import_type]);
        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url, 'current_page' => $page, 'total' => $total]);
    }

}