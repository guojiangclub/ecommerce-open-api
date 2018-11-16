{!! Form::open( [ 'url' => [route('admin.promotion.groupon.updateDisable')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}
<input type="hidden" value="{{$groupon->id}}" name="id">
<div class="form-group">
    <label class="col-sm-2 control-label">活动名称：</label>
    <div class="col-sm-10">
        <input type="text" disabled value="{{$groupon->title}}" class="form-control" name="base[title]" placeholder=""
               required/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">活动时间：</label>
    <div class="col-sm-4">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
            <input type="text" disabled name="base[starts_at]" class="form-control inline" id="date-range200" size="20"
                   value="{{$groupon->starts_at}}"
                   placeholder="点击选择时间" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-range12-container"></div>
    </div>

    <div class="col-sm-4">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
            <input type="text" disabled name="base[ends_at]" class="form-control inline" id="date-range201" size="20"
                   value="{{$groupon->ends_at}}"
                   placeholder="" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">状态：</label>
    <div class="col-sm-10">
        <label class="checkbox-inline i-checks">
            <input name="base[status]" type="radio" {{$groupon->status?'checked':''}}
            disabled value="1">
            有效</label>
        <label class="checkbox-inline i-checks">
            <input name="base[status]" type="radio" {{!$groupon->status?'checked':''}}
            disabled value="0"> 无效</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">拍下商品N分钟不付款自动关闭订单：
        <i class="fa fa-question-circle"
           data-toggle="tooltip" data-placement="top"
           data-original-title="如果设置为0，自动关闭订单时间以商城统一设置为准"></i>
    </label>
    <div class="col-sm-10">
        <input type="text" disabled class="form-control" name="base[auto_close]" placeholder=""
               value="{{$groupon->auto_close}}"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">活动标签：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" value="{{$groupon->tags}}" disabled>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">商品列表：</label>
    <div class="col-sm-10">
        <a class="btn btn-success" href="javascript:;" disabled="">
            添加商品
        </a>
    </div>
</div>


<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>商品信息</th>
                <th width="100">拼团价</th>
                <th width="80">已参与</th>
                <th width="80">每人限购数量</th>
                <th width="80">成团人数</th>
                <th width="100">佣金比例<i class="fa fa-question-circle"
                                       data-toggle="tooltip" data-placement="top"
                                       data-original-title="如何设置为0，则以分销商品设置为准"></i></th>
                <th width="80">是否可获得积分</th>
                <th width="80">是否可使用积分</th>
                <th width="80">参与状态</th>
                <th>广告图</th>
                <th width="100">销量展示<i class="fa fa-question-circle"
                                       data-toggle="tooltip" data-placement="top"
                                       data-original-title="0则拼团列表页显示真实销量；输入大于0的整数，手动修改拼团列表页销量展示"></i></th>
                <th width="80">排序<i class="fa fa-question-circle"
                                    data-toggle="tooltip" data-placement="top"
                                    data-original-title="数值越大排序越靠前"></i></th>
                <th width="50">操作</th>
            </tr>
            </thead>
            <tbody id="select-goods-box">
            @foreach($items as $key=>$item)
                <tr data-key="{{$num+$key}}">
                    <td>
                        <input type="hidden" name="goods_name[{{$num+$key}}]" value="{{$item->goods->name}}">
                        <input type="hidden" name="item[{{$num+$key}}][id]" value="{{$item->id}}">
                        <input type="hidden" name="item[{{$num+$key}}][goods_id]" value="{{$item->goods_id}}">
                        <img src="{{$item->goods->img}}" width="50">
                        销售价：{{$item->goods->GoodsSectionSellPrice}}
                        <br>
                        <a href="{{route('admin.goods.edit',['id'=>$item->goods_id])}}" target="_blank">
                            {{$item->goods->name}}
                        </a>
                    </td>

                    <td><input type="text" disabled value="{{$item->groupon_price}}" class="form-control"
                               name="item[{{$num+$key}}][groupon_price]"></td>
                    <td><input type="text" disabled value="{{$item->sale_number}}" class="form-control"
                               name="item[{{$num+$key}}][groupon_number]"></td>
                    <td><input type="text" disabled value="{{$item->limit}}" class="form-control"
                               name="item[{{$num+$key}}][limit]"></td>
                    <td><input type="text" disabled  value="{{$item->number}}" class="form-control"
                               name="item[{{$key}}][number]"></td>

                    <td>
                        <div class="input-group m-b rate_div">
                            <input class="form-control" disabled value="{{$item->rate}}"
                                   name="item[{{$num+$key}}][rate]" type="text">
                            <span class="input-group-addon">%</span>
                        </div>
                    <td>
                        <a>
                            <i style="color: grey;"  class="fa  @if($item->get_point) fa-toggle-on @else fa-toggle-off @endif"
                               title="切换状态" >
                                <input type="hidden"  value="{{$item->get_point}}"  name="item[{{$num+$key}}][get_point]">
                            </i>
                        </a>

                        {{--<input type="radio" disabled value="1" {{$item->get_point?'checked':''}}--}}
                        {{--name="item[{{$num+$key}}][get_point]"> 是--}}
                        {{--&nbsp;&nbsp;--}}
                        {{--<input type="radio" disabled value="0" {{!$item->get_point?'checked':''}}--}}
                        {{--name="item[{{$num+$key}}][get_point]"> 否--}}
                    </td>
                    <td>
                        <a>
                            <i style="color: grey;" class="fa  @if($item->use_point) fa-toggle-on @else fa-toggle-off @endif"
                               title="切换状态" >
                                <input type="hidden"  value="{{$item->use_point}}"  name="item[{{$num+$key}}][use_point]">
                            </i>
                        </a>

                        {{--<input type="radio" disabled value="1" {{$item->use_point?'checked':''}}--}}
                        {{--name="item[{{$num+$key}}][use_point]"> 是--}}
                        {{--&nbsp;&nbsp;--}}
                        {{--<input type="radio" disabled value="0" {{!$item->use_point?'checked':''}}--}}
                        {{--name="item[{{$num+$key}}][use_point]"> 否--}}
                    </td>
                    <td>
                        <a>
                            <i class="fa switch @if($item->status) fa-toggle-on @else fa-toggle-off @endif"
                               title="切换状态" >
                                <input type="hidden"  value="{{$item->status}}"  name="item[{{$num+$key}}][status]">
                            </i>
                        </a>

                        {{--<input type="radio" value="1" {{$item->status?'checked':''}}--}}
                        {{--name="item[{{$num+$key}}][status]"> 是--}}
                        {{--&nbsp;&nbsp;--}}
                        {{--<input type="radio" value="0" {{!$item->status?'checked':''}}--}}
                        {{--name="item[{{$num+$key}}][status]"> 否--}}
                    </td>
                    <td>
                        <label class="block_banner img-plus">
                            <img width="50" src="{{$item->img}}">
                            <input type="hidden" name="item[{{$num+$key}}][img]" value="{{$item->img}}">
                        </label>
                    </td>
                    <td>
                        <input type="text" value="{{$item->sell_num}}" class="form-control"
                               name="item[{{$num+$key}}][sell_num]">
                        <small>当前销量：{{$item->RealSale}}</small>
                    </td>
                    <td>
                        <input type="text" value="{{$item->sort}}" class="form-control"
                               name="item[{{$num+$key}}][sort]">
                    </td>
                    <td>
                        <a class="btn btn-xs btn-danger" disabled data-id="{{$item->goods_id}}"
                           href="javascript:;">
                            <i data-toggle="tooltip" data-placement="top"
                               class="fa fa-trash"
                               title="删除"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>


<div class="hr-line-dashed"></div>
<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <button class="btn btn-primary" type="submit" id="submit_btn">保存设置</button>
    </div>
</div>
{!! Form::close() !!}