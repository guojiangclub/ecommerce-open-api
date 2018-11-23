<h4>优惠券基础信息</h4>

<hr class="hr-line-solid">

<div class="form-group">
    <label class="col-sm-3 control-label"><span class="sp-require">*</span>优惠券名称：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="base[title]" placeholder="" oninput="OnInput(event)"
               onpropertychange="OnPropChanged(event)"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label"><span class="sp-require">*</span>兑换码：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="base[code]" placeholder=""/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">排促销活动 <i class="fa fa-question-circle"
                                                   data-toggle="tooltip" data-placement="top"
                                                   data-original-title="设置“是”后在下单时选择优惠券后，不能选择促销活动"></i>：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[exclusive]" type="radio" value="0" checked> 否</label>
        <label class="checkbox-inline i-checks"><input name="base[exclusive]" type="radio" value="1"> 是</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">规则：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="base[label]" placeholder=""/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">使用说明：</label>
    <div class="col-sm-9">
        <textarea id="base_intro" class="form-control" oninput="OnInput(event)" onpropertychange="OnPropChanged(event)"
                  name="base[intro]" rows="4"></textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label"><span class="sp-require">*</span>发放总量：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="base[usage_limit]" placeholder=""/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">每人限领：</label>
    <div class="col-sm-9">
        <select class="form-control" name="base[per_usage_limit]">
            <option value="1">1张</option>
            <option value="2">2张</option>
            <option value="3">3张</option>
            <option value="4">4张</option>
            <option value="5">5张</option>
            <option value="6">6张</option>
            <option value="8">8张</option>
            <option value="10">10张</option>
        </select>
    </div>
</div>

<div class="form-group" id="two-inputs">
    <label class="col-sm-3 control-label">领取有效期：</label>
    <div class="col-sm-9">
        <div class="input-group date form_datetime" id="start_at">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
            <input type="text" name="base[starts_at]" class="form-control inline" id="date-range200" size="20" value=""
                   placeholder="点击选择时间" readonly>

            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-range12-container"></div>
    </div>

    <div class="col-sm-9 col-sm-offset-3" style="margin-top: 10px">
        <div class="input-group date form_datetime" id="end_at">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>

            <input type="text" name="base[ends_at]" class="form-control inline" id="date-range201" value=""
                   placeholder="" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">使用开始时间<i class="fa fa-question-circle" data-toggle="tooltip"
                                                   data-placement="top"
                                                   data-original-title="如果不设置使用开始时间，默认以领取有效期的开始日期为准"></i>：</label>
    <div class="col-sm-9">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp</span>
            <input type="text" name="base[usestart_at]" class="form-control inline" value="" id="date-range14"
                   placeholder="点击选择使用开始时间">

            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-usestart_at-container" style="width: 228px"></div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">使用截止时间<i class="fa fa-question-circle" data-toggle="tooltip"
                                                   data-placement="top"
                                                   data-original-title="如果不设置使用截止时间，默认以领取有效期的截止日期为准"></i>：</label>
    <div class="col-sm-9">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp</span>
            <input type="text" name="base[useend_at]" class="form-control inline" value="" id="date-range13"
                   placeholder="点击选择使用截止时间">

            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-useend-container" style="width: 228px"></div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">状态：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                       value="1" checked> 启用</label>
        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                       value="0"> 禁用</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">标签：</label>
    <div class="col-sm-9">
        {!! Form::text('base[tags]',  '' , ['class' => 'form-control form-inputTagator col-sm-10','id'=>'inputDiscountTags', 'placeholder' => '']) !!}
        <label>输入标签名称，按回车添加</label>
    </div>
</div>