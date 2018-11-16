@if($refund->status==8)
    {!! Form::open( [ 'url' => [route('admin.refund.paid')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
@else
    {!! Form::open( [ 'url' => [route('admin.refund.store')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
@endif

<input type="hidden" name="id" value="{{$refund->id}}">

<div class="form-group">
    <label class="control-label col-lg-2">售后编号：</label>
    <div class="col-lg-9">
        <p class="form-control-static">{{$refund->refund_no}}</p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">订单编号：</label>
    <div class="col-lg-9">
        <p class="form-control-static">
            <a href="{{route('admin.orders.show',['id'=>$refund->order->id])}}" target="_blank">
                {{$refund->order->order_no}}</a>
        </p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">支付信息：</label>
    <div class="col-lg-9">
        <p class="form-control-static">
            <strong>支付平台：</strong> {{$refund->order->PayTypeText?$refund->order->PayTypeText:'/'}}
            &nbsp;&nbsp;&nbsp;&nbsp;
            <strong>支付平台交易流水号：</strong>
            @foreach($refund->order->payments as $val)
                {{$val->channel_no}}<br>
            @endforeach
        </p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">申请商品：</label>
    <div class="col-lg-9">
        <p class="form-control-static">{{$refund->orderItem->item_name}} </p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">商品数量：</label>
    <div class="col-lg-9">
        <p class="form-control-static">{{$refund->quantity}} </p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">申请用户：</label>
    <div class="col-lg-9">
        <p class="form-control-static">
            @if($user_info=$refund->user)
                @if($user_info->nick_name)
                    {{$user_info->nick_name}}
                @elseif($user_info->name)
                    {{$user_info->name}}
                @else
                    {{$user_info->mobile}}
                @endif
            @endif
        </p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">申请时间：</label>
    <div class="col-lg-9">
        <p class="form-control-static">{{$refund->created_at}}</p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">申请类型：</label>
    <div class="col-lg-9">
        <p class="form-control-static">{{$refund->TypeText}}</p>
        <input type="hidden" name="type" value="{{$refund->type}}">
        <input type="hidden" name="typeText" value="{{$refund->TypeText}}">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">状态：</label>
    <div class="col-lg-9">
        <p class="form-control-static">
            {{$refund->StatusText}}<br>
            @if($refund->status==6)
                快递公司：{{$refund->shipping->shipping_name}} &nbsp;&nbsp;
                单号:
                <a href="http://m.kuaidi100.com/index_all.html?type={{$refund->shipping->code}}&postid={{$refund->shipping->shipping_tracking}}"
                   target="_blank">{{$refund->shipping->shipping_tracking}}</a>
            @endif
        </p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">申请金额：</label>
    <div class="col-lg-9">
        @if(!in_array($refund->status,[0,2,4,7]))
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <tbody>
                    <!--tr-th start-->
                    <tr>
                        <th>金额</th>
                        <th>资金流向</th>
                    </tr>
                    <!--tr-th end-->
                    @foreach ($refund->refundAmount as $item)
                        <tr>
                            <td>{{$item->amount/100}}元</td>
                            <td>{{$item->type_text}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        @endif
        <p class="form-control-static">总金额：{{$refund->amount}} 元</p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">退款原因：</label>
    <div class="col-lg-9">
        <p class="form-control-static">{{$refund->reason}}</p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">退款说明：</label>
    <div class="col-lg-9">
        <p class="form-control-static">{{$refund->content}}</p>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-2">其他凭证：</label>
    <div class="col-lg-9">
        <p class="form-control-static">
            @foreach($refund->pic_list as $val)
                <img src={{$val}}> &nbsp;&nbsp;
            @endforeach
        </p>
    </div>
</div>

<!----申请 待审核操作-->
@if($refund->status == 0)
    <div class="form-group">
        <label class="control-label col-lg-2">处理：</label>
        <div class="col-lg-9">
            <label>
                <input type="radio" name="opinion" value="1" checked="">
                同意
            </label>
            <label>
                <input type="radio" name="opinion" value="2">
                拒绝
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-2">处理意见：</label>
        <div class="col-lg-9">
            <textarea class="form-control" name="remarks" placeholder=""></textarea>
        </div>
    </div>
    @endif

            <!----确认发货操作-->
    @if($refund->status == 7)
        <div class="form-group">
            <label class="control-label col-lg-2">处理：</label>
            <div class="col-lg-9">
                <select name="express" class="form-control">
                    @foreach($freight as $val)
                        <option value="{{$val->name}}">{{$val->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-lg-2">运单号：</label>
            <div class="col-lg-9">
                <input class="form-control" name="number" placeholder="">
            </div>
        </div>
        @endif

                <!--商家打款操作-->
        <input type="hidden" value="{{$refundAmount}}" name="refundAmount">
        @if($refund->status==8 AND $refundAmount AND settings('enabled_pingxx_pay'))
            <div class="form-group">
                <label class="control-label col-lg-2">退款渠道：</label>
                <div class="col-lg-9">
                    <select id="channel" name="channel" class="form-control">
                        <option value="">请选择退款渠道</option>
                        <option value="artificial">人工打款</option>

                        <option value="wechat">自动转账到用户微信钱包</option>

                    </select>

                    <div class="alert alert-danger" style="display: none; margin-top: 10px" id="channel_tips">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">退款备注：</label>
                <div class="col-lg-9">
                    <textarea class="form-control" name="remarks" placeholder=""></textarea>
                </div>
            </div>
            @endif

                    <!--等待用户退货，商家可以代用户填写物流信息-->

            @if($refund->status==5)
                <div class="alert alert-danger">
                    <p align="center">如用户长时间未提交退货物流信息，可由商家代填退货物流信息</p>
                    <div class="form-group">
                        <label class="control-label col-lg-2">快递公司：</label>
                        <div class="col-lg-9">
                            <select name="express" class="form-control">
                                @foreach($freight as $val)
                                    <option value="{{$val->name}}">{{$val->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">运单号：</label>
                        <div class="col-lg-9">
                            <input class="form-control" name="number" placeholder="">
                        </div>
                    </div>
                </div>
                @endif

                        <!--如果用户已退货-->
                @if($refund->status==6)
                    <input type="hidden" name="action" value="{{$action}}">
                    <div class="form-group">
                        <label class="control-label col-lg-2">备注：</label>
                        <div class="col-lg-9">
                            <textarea id="remarks" class="form-control" name="remarks" placeholder=""></textarea>
                        </div>
                    </div>
                @endif

                <input type="hidden" name="status" value="{{$refund->status}}">
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8 controls">
                        @if($refund->status==8 AND $treasurer)
                            <button type="submit" class="btn btn-primary">确认退款</button>
                        @else
                            {!! $refund->ActionBtnText !!}
                        @endif

                    </div>
                </div>
                {!! Form::close() !!}