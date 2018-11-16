<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Http\Controllers;

use iBrand\EC\Open\Backend\Member\Models\User;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use ZipArchive;

class EntityCardController extends Controller
{
    protected $cache;

    public function __construct()
    {
        $this->cache = cache();
    }

    public function index()
    {
        $cards = $this->getCardData();

        //return view('member-backend::entity_card.index', compact('cards'));

        return LaravelAdmin::content(function (Content $content) use ($cards) {
            $content->header('实体卡申请管理');

            $content->breadcrumb(
                ['text' => '实体卡申请管理', 'url' => 'member/entity', 'no-pjax' => 1]
            );

            $content->body(view('member-backend::entity_card.index', compact('cards')));
        });
    }

    protected function getCardData($where = [])
    {
        $time = [];

        if (!empty(request('etime'))) {
            $time['created_at'] = ['<=', request('etime').' 23:59:59'];
        }

        if (!empty(request('stime'))) {
            $time['created_at'] = ['>=', request('stime')];
        }

        if (!empty(request('etime')) && !empty(request('stime'))) {
            $where['created_at'] = ['<=', request('etime').' 23:59:59'];
            $time['created_at'] = ['>=', request('stime')];
        }

        return $this->getDataConditions($where, $time, 15);
    }

    protected function getDataConditions($where, $time = [], $limit = 15)
    {
        $data = \DB::table('funtasy_entity_card')->where(function ($query) use ($where, $time) {
            if (is_array($where) && count($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            if (is_array($time)) {
                foreach ($time as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }
        })->orderBy('created_at', 'desc')->paginate($limit);

        return $data;
    }

    /**
     * 获取导出数据.
     *
     * @return mixed
     */
    public function getExportData()
    {
        $page = request('page') ? request('page') : 1;
        $limit = request('limit') ? request('limit') : 50;
        $type = request('type');
        $cards = $this->getCardData();
        $lastPage = $cards->lastPage();
        $cardExcelData = $this->formatToExcelData($cards);

        if (1 == $page) {
            session(['export_entity_cards_cache' => generate_export_cache_name('export_entity_cards_cache_')]);
        }
        $cacheName = session('export_entity_cards_cache');

        if ($this->cache->has($cacheName)) {
            $cacheData = $this->cache->get($cacheName);
            $this->cache->put($cacheName, array_merge($cacheData, $cardExcelData), 300);
        } else {
            $this->cache->put($cacheName, $cardExcelData, 300);
        }

        if ($page == $lastPage) {
            $title = ['姓名', '拼音', '出生日期', '所在地', '手机', '邮箱', '申请时间', '申请用户'];

            return $this->ajaxJson(true, ['status' => 'done', 'url' => '', 'type' => $type, 'title' => $title, 'cache' => $cacheName, 'prefix' => 'entity_cards_data_']);
        }
        $url_bit = route('admin.users.entity.getExportData', array_merge(['page' => $page + 1, 'limit' => $limit], request()->except('page', 'limit')));

        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url_bit, 'page' => $page, 'totalPage' => $lastPage]);
    }

    private function formatToExcelData($data)
    {
        $list = [];
        if (count($data) > 0) {
            $i = 0;
            foreach ($data as $item) {
                $user = User::find($item->user_id);
                $list[$i][] = $item->name;
                $list[$i][] = $item->e_name;
                $list[$i][] = $item->birthday;
                $list[$i][] = $item->address;
                $list[$i][] = $item->mobile;
                $list[$i][] = $item->email;
                $list[$i][] = $item->created_at;
                $list[$i][] = $user ? $user->mobile : '';
                ++$i;
            }
        }

        return $list;
    }

    private function compressDir($dir, $zip, $prev = '.')
    {
        $handler = opendir($dir);
        $basename = basename($dir); //return entity

        $zip->addEmptyDir($prev.'/'.$basename);

        while ($file = readdir($handler)) { //.
            $realpath = $dir.'/'.$file;

            if (is_dir($realpath)) {
                if ('.' !== $file && '..' !== $file) {
                    $start = str_replace('-', '', request('stime'));
                    $end = str_replace('-', '', request('etime'));
                    if ($start <= $file and $file <= $end) {
                        $zip->addEmptyDir($prev.'/'.$basename.'/'.$file);
                        self::compressDir($realpath, $zip, $prev.'/'.$basename);
                    }
                }
            } else {
                $zip->addFile($realpath, $prev.'/'.$basename.'/'.$file);
            }
        }

        closedir($handler);

        return null;
    }

    public function zipFiles()
    {
        $page = request('page') ? request('page') : 1;
        $stime = request('stime');
        $etime = request('etime');

        $year = date('Y', strtotime($stime));
        $start_m = date('n', strtotime($stime));
        $end_m = date('n', strtotime($etime));
        $month = request('month') ? request('month') : $start_m;

        $zip = new ZipArchive();

        if ($month == $start_m) {
            $res = $zip->open('entity.zip', ZipArchive::OVERWRITE | ZipArchive::CREATE);
        } else {
            $res = $zip->open('entity.zip');
        }

        if ($res) {
            $this->compressDir(storage_path('app/public/entity/').$year.'/'.$month, $zip, $year);
        }
        $zip->close();

        if ($month == $end_m) {
            header('Content-Type:text/html;charset=utf-8');
            header('Content-disposition:attachment;filename=entity.zip');
            $filesize = filesize('./entity.zip');
            readfile('./entity.zip');
            header('Content-length:'.$filesize);

            unlink('./entity.zip');

            dd('下载完成');
        } else {
            $month = $month + 1;
            $message = '正在下载图片数据';
            $interval = 5;
            $url_bit = route('admin.users.entity.zipFiles', ['stime' => $stime, 'etime' => $etime, 'month' => $month]);

            //return view('member-backend::entity_card.show_message', compact('message', 'url_bit', 'interval'));

            return LaravelAdmin::content(function (Content $content) use ($message,$url_bit,$interval) {
                $content->header('实体卡申请管理');

                $content->breadcrumb(
                    ['text' => '实体卡申请管理', 'url' => 'member/entity', 'no-pjax' => 1]
                );

                $content->body(view('member-backend::entity_card.show_message', compact('message', 'url_bit', 'interval')));
            });
        }
    }
}
