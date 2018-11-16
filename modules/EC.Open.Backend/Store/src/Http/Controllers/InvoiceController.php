<?php
namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use iBrand\Backend\Http\Controllers\Controller;
use iBrand\EC\Open\Backend\Store\Model\Order;
use Illuminate\Http\Request;
use Response;
use iBrand\EC\Open\Backend\Store\Model\Invoice;
use iBrand\EC\Open\Backend\Store\Repositories\OrderRepository;
use iBrand\EC\Open\Backend\Store\Repositories\OrderItemRepository;



class InvoiceController extends Controller
{

    protected $orderRepository;
    protected $orderItemsRepository;
    protected $productRepository;
    protected $orderDeliveryRepository;
    protected $freightCompanyRepository;
    protected $orderLogRepository;


    public function __construct(OrderRepository $orderRepository
        , OrderItemRepository $orderItemsRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemsRepository = $orderItemsRepository;


    }


    public function edit($id)
    {
        $invoice = Invoice::find($id);
        
        $content = config('Invoice.content');
        $type = config('Invoice.type');
        
        return view('store-backend::orders.includes.order_invoice_edit', compact('invoice', 'type', 'content'));
    }
    
    public function update(Request $request)
    {
        $input = $request->except('id','_token');
        
        $invoice = Invoice::find($request->input('id'));
        $invoice->fill($input);
        $invoice->save();

        return $this->ajaxJson();
    }


}
