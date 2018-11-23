<h4>活动基础信息</h4>
<hr class="hr-line-solid">

<div class="form-group">
    <label class="col-sm-2 control-label"><span class="sp-require">*</span>活动名称：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" required name="base[title]" placeholder="" value="{{$discount->title}}"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">排优惠券 <i class="fa fa-question-circle"
                                                  data-toggle="tooltip" data-placement="top"
                                                  data-original-title="设置“是”后在下单时选择促销活动后，不能选择优惠券"></i>：</label>
    <div class="col-sm-10">
        <label class="checkbox-inline i-checks"><input name="base[exclusive]" type="radio" value="0" {{$discount->exclusive == 0?'checked' : ''}}> 否</label>
        <label class="checkbox-inline i-checks"><input name="base[exclusive]" type="radio" value="1" {{$discount->exclusive == 1?'checked' : ''}}> 是</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">规则：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="base[label]" placeholder="" value="{{$discount->label}}"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">使用说明：</label>
    <div class="col-sm-10">
        <textarea class="form-control" name="base[intro]" rows="4">{{$discount->intro}}</textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label"><span class="sp-require">*</span>使用数量：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="base[usage_limit]" placeholder="" required
               value="{{$discount->usage_limit}}"/>
    </div>
</div>

<div class="form-group" id="two-inputs">
    <label class="col-sm-2 control-label">活动时间：</label>
    <div class="col-sm-3">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
            <input type="text" name="base[starts_at]" class="form-control inline" id="date-range200" size="20" value="{{$discount->starts_at}}"
                   placeholder="点击选择时间" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-range12-container"></div>
    </div>

    <div class="col-sm-3">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
            <input type="text" name="base[ends_at]" class="form-control inline" id="date-range201" value="{{$discount->ends_at}}"
                   placeholder="" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>


<div class="form-group">
    <label class="col-sm-2 control-label">状态：</label>
    <div class="col-sm-10">
        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                       value="1" {{$discount->status == 1?'checked' : ''}}> 启用</label>
        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                       value="0" {{$discount->status == 0?'checked' : ''}}> 禁用</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">标签：</label>
    <div class="col-sm-10">
        {!! Form::text('base[tags]',  $discount->tags , ['class' => 'form-control form-inputTagator col-sm-10','id'=>'inputDiscountTags', 'placeholder' => '']) !!}
        <label>输入标签名称，按回车添加</label>
    </div>
</div>