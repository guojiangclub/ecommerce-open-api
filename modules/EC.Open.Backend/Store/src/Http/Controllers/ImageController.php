<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/28
 * Time: 21:03
 */

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Storage;

class ImageController extends Controller
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function postUpload(Request $request)
    {
        $prefix = 'storage/';
        $file = $request->file('upload_image');
        $path = $prefix . $file->store('uploads/images/' . date('Y_m_d'), 'public');
        $url = $this->replaceImgCDN(asset($path));

        return response()->json(['success' => true, 'file' => asset($path), 'url' => $url]);
    }

    public function ExcelUpload(Request $request)
    {
        $destinationPath = '/uploads/excel/';

        $file = $request->file('upload_excel');

        $extension = $request->file('upload_excel')->getClientOriginalExtension();

        $excelname = $this->generaterandomstring() . '.' . $extension;

        $filepath = $destinationPath . $excelname;

        $request->file('upload_excel')->move(public_path() . $destinationPath, $excelname);

        return response()->json(['success' => true, 'file' => $filepath, 'url' => asset($filepath)]);
    }

    public function imagevalidate($input)
    {
        $rules = [
            'articleid' => 'Required|Integer',
            'pic' => 'Required|image|mimes:jpeg,jpg,bmp,png,gif|max:3000',
        ];

        return Validator::make($input, $rules);
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

    public function uploadExcelFile(Request $request)
    {
        Storage::makeDirectory('public/import/excel');

        $destinationPath = 'app/public/import/excel/';

        $extension = $request->file('upload_excel')->getClientOriginalExtension();

        $excelname = $this->generaterandomstring() . '.' . $extension;

        $filepath = $destinationPath . $excelname;

        $request->file('upload_excel')->move(storage_path($destinationPath), $excelname);

        return response()->json(['success' => true, 'file' => $filepath, 'url' => asset($filepath)]);
    }

}