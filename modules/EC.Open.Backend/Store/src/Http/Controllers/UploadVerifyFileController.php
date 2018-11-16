<?php

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use Exception;

use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class UploadVerifyFileController extends Controller
{
    const UPLOAD_KEY = 'file';

    public function index()
    {
        $fileList = settings('security_files');
        return LaravelAdmin::content(function (Content $content) use ($fileList) {

            $content->header('上传验证文件');

            $content->breadcrumb(
//				['text' => '模板消息设置', 'url' => 'setting/wechat/message', 'no-pjax' => 1],
                ['text' => '上传验证文件', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '上传验证文件']

            );
            $content->body(view('store-backend::upload_verify_file.index', compact('fileList')));
        });
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        if (!$file->getMimeType()) {
            return $this->ajaxJson(false, 402, 'file type error', []);
        }

        if (!$request->hasFile(self::UPLOAD_KEY)) {
            return $this->ajaxJson(false, 422, 'no file found.', []);
        }

        $type = $file->getMimeType();
        $filesize = $file->getSize();
        $this->checkSize($filesize);
        $this->checkMime($type);
        $originalName = $file->getClientOriginalName();
        $dir = base_path('storage/share/security_files');
        $res = $file->move($dir, $originalName);
        if (count($res) > 0) {
            $file = env('APP_URL') . '/' . $originalName;
            $fileList = settings('security_files');
            if (!$fileList) {
                $fileList = ['security_files' => [['url' => $file, 'name' => $originalName]]];
            } else {
                array_push($fileList, ['url' => $file, 'name' => $originalName]);
                $fileList = ['security_files' => $fileList];
            }

            settings()->setSetting($fileList);

            return $this->ajaxJson(true, 200, '', []);
        }
    }

    /**
     * 检查大小.
     */
    protected function checkSize($size)
    {
        if ($size > 2 * M) {
            return $this->ajaxJson(false, 422, '文件过大', []);
        }
    }

    /**
     * 检测Mime类型.
     */
    protected function checkMime($mime)
    {
        if (!preg_match("/^text/", $mime)) {
            return $this->ajaxJson(false, 422, '文件类型只支持TXT', []);
        }
    }

}