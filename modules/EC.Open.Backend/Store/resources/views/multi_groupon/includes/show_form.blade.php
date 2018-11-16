{!! Form::open( [ 'url' => [route('admin.promotion.multiGroupon.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}

<div class="form-group">
    <div class="col-sm-10">

        <div class="row">
            <div class="col-sm-2">
                <img id="img" src="{{$groupon->goods->img}}" width="100">
            </div>
            <div class="col-sm-6">
                <p id="name">{{$groupon->goods->name}}</p>
                <p id="price">销售价：{{$groupon->goods->sell_price}}</p>
                <p id="nums">库存：{{$groupon->goods->store_nums}}</p>
            </div>
            <input type="hidden" name="goods_id" value="{{$groupon->goods_id}}">
        </div>
    </div>
</div>


    <div class="form-group">
        <label class="col-sm-2 control-label">团购价格：</label>
        <div class="col-sm-10">
            <div class="input-group m-b"><span class="input-group-addon">¥</span>
                <input class="form-control" type="text" name="price" disabled  value="{{$groupon->price}}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">成团人数：</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="number" disabled  value="{{$groupon->number}}"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">佣金比例：</label>
        <div class="col-sm-10">
            <div class="input-group m-b">
                <input class="form-control" type="text" name="rate" disabled value="{{$groupon->rate}}">
                <span class="input-group-addon">%</span>
            </div>
        </div>
    </div>


<div class="form-group">
    <label class="col-sm-2 control-label">活动名称：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="title" disabled placeholder="" value="{{$groupon->title}}"/>
    </div>
</div>

<div class="form-group" id="two-inputs">
    <label class="col-sm-2 control-label">活动时间：</label>
    <div class="col-sm-4">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
            <input type="text" disabled name="starts_at" class="form-control inline" id="date-range200" size="20" value="{{$groupon->starts_at}}"
                   placeholder="点击选择时间" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-range12-container"></div>
    </div>

    <div class="col-sm-4">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
            <input type="text" disabled name="ends_at" class="form-control inline" id="date-range201" size="20" value="{{$groupon->ends_at}}"
                   placeholder="" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>


<div class="form-group">
    <label class="col-sm-2 control-label">活动排序：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" disabled name="sort" value="{{$groupon->sort}}"/>
    </div>
</div>


<div class="hr-line-dashed"></div>
<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <a class="btn btn-primary" href="{{route('admin.promotion.multiGroupon.index')}}">返回</a>
    </div>
</div>
{!! Form::close() !!}
