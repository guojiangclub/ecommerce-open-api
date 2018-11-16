<h4>事件基础信息</h4>
<hr class="hr-line-solid">

<div class="form-group">
    <label class="col-sm-2 control-label"><span class="sp-require">*</span>事件名称：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="title" value="{{$market->title}}" />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">说明：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="label" value="{{$market->label}}"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label"><span class="sp-require">*</span>可使用数量：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="usage_limit" value="{{$market->usage_limit}}"/>
    </div>
</div>


<div class="form-group" id="two-inputs">
    <label class="col-sm-2 control-label">有效期：</label>
    <div class="col-sm-10">
        <div class="input-group date form_datetime" id="start_at">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
            <input type="text" name="starts_at" class="form-control inline" id="date-range200" size="20" value="{{$market->starts_at}}"
                   placeholder="点击选择时间" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-range12-container"></div>
    </div>

    <div class="col-sm-10 col-sm-offset-2" style="margin-top: 10px">
        <div class="input-group date form_datetime" id="end_at">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
            <input type="text" name="ends_at" class="form-control inline" id="date-range201" size="20" value="{{$market->ends_at}}"
                   placeholder="" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">状态：</label>
    <div class="col-sm-10">
        <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                       value="1" {{$market->status==1?'checked':''}}> 启用</label>
        <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                       value="0" {{$market->status==0?'checked':''}}> 禁用</label>
    </div>
</div>