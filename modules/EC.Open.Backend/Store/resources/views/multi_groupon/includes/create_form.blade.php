{!! Form::open( [ 'url' => [route('admin.promotion.multiGroupon.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}

<div class="form-group">
    <label class="col-sm-2 control-label">选择商品：</label>
    <div class="col-sm-10">
        <a class="btn btn-success" id="chapter-create-btn" data-toggle="modal"
           data-target="#modal" data-backdrop="static" data-keyboard="false"
           data-url="{{route('admin.promotion.multiGroupon.getSpuModal')}}">
            点击选择
        </a>

        <div class="row">
            <div class="col-sm-2">
                <img id="img" src="" width="100">
            </div>
            <div class="col-sm-6">
                <p id="name"></p>
                <p id="price"></p>
                <p id="nums"></p>
            </div>
            <input type="hidden" name="goods_id">
        </div>
    </div>
</div>


<div class="form-group">
    <label class="col-sm-2 control-label">团购价格：</label>
    <div class="col-sm-10">
        <div class="input-group m-b"><span class="input-group-addon">¥</span>
            <input class="form-control" type="text" name="price">
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">成团人数：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="number" placeholder="" required/>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">佣金比例：</label>
    <div class="col-sm-10">
        <div class="input-group m-b">
            <input class="form-control" type="text" name="rate">
            <span class="input-group-addon">%</span>
        </div>
    </div>
</div>


<div class="form-group">
    <label class="col-sm-2 control-label">活动名称：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="title" placeholder="" required/>
    </div>
</div>

<div class="form-group" id="two-inputs">
    <label class="col-sm-2 control-label">活动时间：</label>
    <div class="col-sm-4">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
            <input type="text" name="starts_at" class="form-control inline" id="date-range200" size="20" value=""
                   placeholder="点击选择时间" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-range12-container"></div>
    </div>

    <div class="col-sm-4">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
            <input type="text" name="ends_at" class="form-control inline" id="date-range201" size="20" value=""
                   placeholder="" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<div class="form-group" style="display: none;">
    <label class="col-sm-2 control-label">状态：</label>
    <div class="col-sm-10">
        <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                       value="1" checked>
            有效</label>
        <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                       value="0"> 无效</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">活动排序：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="sort" value="9"/>
    </div>
</div>


<div class="hr-line-dashed"></div>
<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <button class="btn btn-primary" type="submit" id="submit_btn">保存设置</button>
    </div>
</div>
{!! Form::close() !!}
