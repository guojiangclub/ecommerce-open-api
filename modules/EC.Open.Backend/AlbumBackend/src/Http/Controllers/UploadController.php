<?php

namespace GuoJiangClub\EC\Open\Backend\Album\Http\Controllers;

use Illuminate\Http\Request;
use iBrand\Backend\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function postUpload(Request $request)
    {
        $destinationPath = '/uploads/';
        $file = $request->file('upload_image');
        $paths = [];
        $img_url = [];
        $name = [];
        $data = [];
        $category_id = request('category_id') ? request('category_id') : 1;

        if ($result = $this->validated($file) AND !$result['status']) {
            return response()->json(['status' => false, 'message' => $result['message']]);
        }
        foreach ($file as $key => $item) {
            $extension = $item->getClientOriginalExtension();

            $pic_name = $this->generaterandomstring() . '.' . $extension;

            //$dir = $destinationPath . $this->formatDir();
            $dir = $this->formatDir();
            $file_path = $dir . $pic_name;

            $item->move(storage_path('app/public/images/') . $dir, $pic_name);
            $url = $this->replaceImgCDN(asset('storage/images/' . $file_path));

            $data[$key]['path'] = asset('storage/images/' . $file_path);
            $data[$key]['url'] = $url;
            $data[$key]['name'] = str_replace('.' . $extension, '', $item->getClientOriginalName());
        }

        if (count($data) == count($file)) {
            event('image.uploaded', [$data, $category_id]);
            return response()->json(['status' => true, 'data' => $data]);
        }

        return response()->json(['status' => false, 'message' => '上传失败']);
    }


    public function generaterandomstring($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // 替换图片CDN
    protected function replaceImgCDN($value)
    {
        $parse = parse_url($value);
        $parse_path = isset($parse['path']) ? $parse['path'] : '';
        $parse_host = isset($parse['host']) ? $parse['host'] : '';
        $app_parse = parse_url(env('APP_URL'));
        if ($app_parse['host'] !== $parse_host) {
            return $value;
        }
        $cdn_status = settings('store_img_cdn_status') ? settings('store_img_cdn_status') : 0;
        if ($cdn_status && $value) {
            $cdn_url = settings('store_img_cdn_url') ? settings('store_img_cdn_url') : '';
            $parse_path = isset($parse['path']) ? $parse['path'] : '';
            return $cdn_url . $parse_path;
        }
        return $value;
    }


    protected function formatDir()
    {
        $directory = config('ibrand.file-manage.dir', '{Y}/{m}/{d}');
        $replacements = [
            '{Y}' => date('Y'),
            '{m}' => date('m'),
            '{d}' => date('d'),
            '{H}' => date('H'),
            '{i}' => date('i'),
        ];

        return str_replace(array_keys($replacements), $replacements, $directory);
    }

    protected function maxSize($file)
    {
        $size = $file->getClientSize() / 1024;

        if ($size > config('ibrand.file-manage.size', 2) * 1024) {
            return false;
        }
        return true;
    }

    protected function maxNum($num)
    {
        if ($num > config('ibrand.file-manage.num', 5)) {
            return false;
        }
        return true;
    }

    protected function mines($extension)
    {
        if (in_array($extension, config('ibrand.file-manage.mines', ['jpg', 'jpeg', 'png', 'bmp', 'gif']))) {
            return true;
        }
        return false;
    }

    protected function validated($files)
    {
        foreach ($files as $item) {
            $extension = $item->getClientOriginalExtension();

            if (!$this->maxSize($item)) {
                return ['status' => false, 'message' => '图片过大'];
            }

            if (!$this->maxNum(count($files))) {
                return ['status' => false, 'message' => '图片上传数量不得超过5张'];
            }

            if (!$this->mines($extension)) {
                return ['status' => false, 'message' => '图片格式不正确'];
            }
        }
        return ['status' => true];
    }
}