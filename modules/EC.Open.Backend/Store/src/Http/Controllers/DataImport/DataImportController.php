<?php

namespace iBrand\EC\Open\Backend\Store\Http\Controllers\DataImport;

use iBrand\Backend\Http\Controllers\Controller;

use ElementVip\Member\Backend\Models\Card;
use ElementVip\TNF\Core\Models\CardVipCode;
use ElementVip\Member\Backend\Repository\CouponRepository;
use ElementVip\Member\Backend\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use ElementVip\TNF\Core\Tnf;
use Carbon\Carbon;
use Maatwebsite\Excel\Collections\SheetCollection;

class DataImportController extends Controller
{

    protected $couponCodeRepository;
    protected $orderRepository;
    protected $tnf;
    protected $cache;

    public function __construct(CouponRepository $couponCodeRepository, Tnf $tnf)
    {
        $this->couponCodeRepository = $couponCodeRepository;
        $this->tnf = $tnf;
        $this->cache = cache();
    }

    /**
     * 导入线下订单数据
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrder()
    {
        return view('store-backend::dataimport.createOrder');
    }

    public function importOrderModal()
    {
        $calculateUrl = route('admin.dataimport.getImportOrderDataCount') . '?path=' . storage_path(request('path'));

        return view('store-backend::dataimport.import_modal', compact('calculateUrl'));
    }

    /**
     * 计算导入的数据数量
     * @param $path
     */
    public function getImportDataCount()
    {
        $filename = request('path');

        Excel::load($filename, function ($reader) {
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $count = count($reader->toArray()) - 1;

            $expiresAt = Carbon::now()->addMinutes(30);
            $this->cache->forget('offlineOrderImportCount');
            $this->cache->put('offlineOrderImportCount', $count, $expiresAt);
        });

        $limit = 30;
        $total = ceil($this->cache->get('offlineOrderImportCount') / $limit);

        $url = route('admin.dataimport.importOrder', ['total' => $total, 'limit' => $limit, 'path' => $filename]);
        return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url]);
    }

    /**
     * 导入订单文件数据
     * @param Request $request
     * @return mixed
     */
    public function importOrder(Request $request)
    {
        $files = $request->input('path');
        $page = request('page') ? request('page') : 1;
        $total = request('total');
        $limit = request('limit');

        if ($page == 1) {
            $minID = DB::table('transaction_report')->max('id') + 1;
            session(['transactionMinID' => $minID]);
        }

        if ($page > $total) {
            return $this->ajaxJson(true, ['status' => 'complete']);
        }

        try {
            DB::beginTransaction();

            Excel::load($files, function ($reader) use ($page, $limit) {

                $reader = $reader->get();

                if ($reader instanceof SheetCollection) {
                    $reader = $reader->first();
                }

                $results = $reader->forPage($page, $limit)->toArray();

                if (count($results) > 0) {
                    foreach ($results as $key => $value) {

                        $input = array(
                            'Issue_Store_ID' => $value['issue_store_id'],
                            'VIP_Code_in_TTPOS' => $value['vip_code_in_ttpos'],
                            'Issue_Store_Name' => $value['issue_store_name'],
                            'Transaction_ID' => $value['transaction_id'],
                            'SKU_ID' => $value['sku_id'],
                            'SKU_Ops_Sales_Amount' => $value['sku_ops_sales_amount'],
                            'Quantity' => intval($value['quantity']),
                            'Transaction_Date' => $value['transaction_date'],
                            'Transaction_Status' => $value['transaction_status'],
                            'Point_Earning' => $value['point_earning'],
                            'Point_Redeemed' => $value['point_redeemed'],
                            'Issued_Item_Coupon_ID' => $value['issued_item_coupon_id'],
                            'Issued_Memo_Coupon_ID' => $value['issued_memo_coupon_id'],
                            'Usage_Status' => $value['usage_status'],
                            'Article_Description' => $value['article_description'],
                            'Current_Year' => $value['current_year'],
                            'Current_Season' => $value['current_season'],
                            'CN_Retail_Category' => $value['cn_retail_category'],
                            'Collection' => $value['collection'],
                            'Original_Retail_Selling_Price' => $value['original_retail_selling_price'],
                            'Size_Code' => $value['size_code'],
                            'Color_Code' => $value['color_code'],
                            'Color_Description' => $value['color_description'],
                            'Order_NO' => $value['order_no'],
                            'Telephone' => $value['telephone']

                        );
                        DB::table('transaction_report')->insert($input);

                    }
                }

            });

            DB::commit();

            $url = route('admin.dataimport.importOrder', ['page' => $page + 1, 'total' => $total, 'limit' => $limit, 'path' => request('path')]);
            return $this->ajaxJson(true, ['status' => 'goon', 'url' => $url, 'current_page' => $page, 'total' => $total]);

        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::info($exception->getMessage() . $exception->getTraceAsString());
            return $this->ajaxJson(false, [], 404, '保存失败');
        }
    }


    public function orderJob()
    {
        $page = request('page') ? request('page') + 1 : 2;
        $limit = request('limit') ? request('limit') : 15;
        $maxID = request('maxID') ? request('maxID') : DB::table('transaction_report')->max('id');
        $minID = request('minID') ? request('minID') : session('transactionMinID');

        session()->forget('transactionMinID');
        $message = '正在处理线下订单数据...' . $maxID . '--' . $minID;

        $transactions = DB::table('transaction_report')
            ->where('status', 0)
            ->whereBetween('id', [$minID, $maxID])->paginate($limit);

        if (count($transactions) > 0) {
            foreach ($transactions as $item) {
                if ($item->Telephone AND $user = User::where('mobile', $item->Telephone)->first()) {
                    $orders = DB::table('transaction_report')
                        ->where('Telephone', $item->Telephone)
                        ->where('status', 0)
                        ->whereBetween('id', [$minID, $maxID])->get();
                    if (count($orders) > 0) {
                        $this->tnf->handleOfflineOrder($orders, $this->userHandle($user));
                    }
                }
            }
        } else {
            $message = '本次线下订单数据处理完成';
        }
        $interval = 5;
        $url_bit = route('admin.dataimport.orderJob', ['page' => $page, 'limit' => $limit, 'maxID' => $maxID, 'minID' => $minID]);

        return view('store-backend::show_message', compact('message', 'interval', 'url_bit'));
    }

    private function userHandle($user)
    {
        $userinfo['uid'] = $user->id;
        $userinfo['mobile'] = $user->mobile;      
        $userinfo['open_id'] = '';
        $userinfo['nick_name'] =  $user->name ? $user->name : $user->nick_name;
        if ($card = $user->card AND $card->name) {
            $userinfo['nick_name'] = $card->name;
        }
        return $userinfo;
    }


    /**
     * 导入线下会员卡数据
     * @return mixed
     */
    public function createVipeak()
    {
        return view('store-backend::dataimport.createVipeak');
    }

    public function importVipeak(Request $request)
    {
        $filename = 'public' . $request['upload_excel'];

        Excel::load($filename, function ($reader) {

            $reader = $reader->getSheet(0);

            //获取表中的数据
            $results = $reader->toArray();

            if (count($results) > 0) {
                foreach ($results as $key => $value) {
                    if ($key > 0) {
                        $input = array(
                            'VIP_Code_In_TTPOS' => $value[0],
                            'First_Name' => $value[1],
                            'Last_name' => $value[2],
                            'Gender' => $value[3],
                            'VIP_Center' => $value[4],
                            'VIP_Grade_Code' => $value[5],
                            'VIP_ID' => $value[6],
                            'Birth_Year' => $value[7],
                            'Birth_Month' => $value[8],
                            'Birth_Day' => $value[9],
                            'Join_date' => $value[10],
                            'Mobile_Phone_Number' => $value[11],
                            'Join_Store' => $value[12]
                        );

                        DB::table('vip_report')->insert($input);
                        /*if ($input['VIP_Code_In_TTPOS'] AND !DB::table('vip_report')->where(['VIP_Code_In_TTPOS' => $input['VIP_Code_In_TTPOS'], 'VIP_ID' => $input['VIP_ID']])->first()) {
                            if (DB::table('vip_report')->insert($input)) {
                                if (!CardVipCode::where('vip_code_in_ttpos', $input['VIP_Code_In_TTPOS'])->first() AND $card = Card::where('number', $input['VIP_ID'])->first()) {
                                    CardVipCode::create(['card_id' => $card->id, 'vip_code_in_ttpos' => $input['VIP_Code_In_TTPOS']]);
                                }
                            }
                        }*/

                    }
                }
            }
        });
    }


    /**
     * 导入线下优惠券数据
     * @return mixed
     */
    public function createCoupon()
    {
        return view('store-backend::dataimport.createCoupon');
    }

    public function importCoupon(Request $request)
    {
        $filename = 'public' . $request['upload_excel'];

        Excel::load($filename, function ($reader) {

            $reader = $reader->getSheet(0);

            //获取表中的数据
            $results = $reader->toArray();

            if (count($results) > 1) {
                foreach ($results as $key => $value) {
                    if ($key > 1) {
                        $input = array(
                            'Issued_Coupon_ID' => $value[0],
                            'Usage_Status' => $value[1]
                        );
                        DB::table('coupon_report')->insert($input);
                        if ($value[1] == 'Y') {
                            $coupon = $this->couponCodeRepository->updateOfflineCouponStatus($value[0]);

                            /*发送模板消息*/
//                            if($coupon) {
//                                $order = $this->orderRepository->findWhere(['user_id' => $coupon->user_id,'coupon_item' => $value[0]])->first();
//                                if($order) {
//                                    $openid = $this->tnf->getOpenIdByUserId($coupon->user_id);
//                                    if($openid) {
//                                        $wxMsgData = [
//                                            'open_id' => $openid,
//                                            'order_no' => $order->order_no,
//                                            'order_amount' => $order->order_amount,
//                                            'coupon_code' => $value[0],
//                                            'coupon_info' => $coupon->coupon->name,
//                                            'used_time' => Carbon::now()->format('Y-m-d')
//                                        ];
//                                        Wx::sendTemplateMessage('useCouponType', $wxMsgData);
//                                    }
//
//                                }
//                            }

                        }

                    }
                }
            }
        });
    }


    /**
     * 导入官网优惠券码数据
     * @return mixed
     */
    public function createWebCouponCode()
    {
        return view('store-backend::dataimport.createWebCouponCode');
    }

    public function importWebCouponCode(Request $request)
    {
        $filename = 'public' . $request['upload_excel'];

        Excel::load($filename, function ($reader) {

            $reader = $reader->getSheet(0);

            //获取表中的数据
            $results = $reader->toArray();

            if (count($results) > 1) {
                foreach ($results as $key => $value) {

                    $couponID = $results[2][1];

                    if ($key > 1) {
                        $input = array(
                            'coupon_code' => $value[0],
                            //'coupon_id' => $value[1]
                            'coupon_id' => $couponID
                        );
                        DB::table('web_coupon_code_report')->insert($input);

                    }
                }
            }
        });
    }


    /**
     * 线下订单数据重新跑一次，根据card表里面的number（卡号），获取VIP_CODE_IN_TTPOS，然后根据VIP_CODE_IN_TTPOS查询订单数据，插入到订单里面去
     */

    public function handOrderData()
    {
        $card = Card::all();
        foreach ($card as $item) {
            $codes = '';
            foreach ($item->codes as $value) {
                $codes = $codes . "'" . $value->vip_code_in_ttpos . "',";
            }

            $transactions = DB::select("SELECT * FROM  transaction_report WHERE VIP_Code_In_TTPOS IN (" . rtrim($codes, ",") . ") AND SKU_Ops_Sales_Amount > 0 AND Transaction_ID NOT IN (SELECT DISTINCT order_no FROM orders WHERE user_id = " . $item->user_id . ")");

            if ($transactions !== NULL AND $user = User::find($item->user_id)) {
                $this->tnf->transactionHandle($transactions, $user);
            }
        }
    }

}
