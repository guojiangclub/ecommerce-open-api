<h4>优惠券基础信息</h4>
<input type="hidden" name="base[channel]" value="ec">
<hr class="hr-line-solid">

<div class="form-group">
    <label class="col-sm-3 control-label"><span class="sp-require">*</span>优惠券名称：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="base[title]" placeholder="" oninput="OnInput(event)"
               onpropertychange="OnPropChanged(event)"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">展示图片<i class="fa fa-question-circle"
                                                 data-toggle="tooltip" data-placement="top"
                                                 data-original-title="建议上传小于2M的正方形图片；不上传则默认显示商家LOGO"></i>：</label>
    <div class="col-sm-9">
        <div class="pull-left" id="activity-poster">
            <img src="{{settings('shop_show_logo')?settings('shop_show_logo'):'/assets/backend/activity/backgroundImage/pictureBackground.png'}}"
                 alt="" class="img" width="182px"  style="margin-right: 23px;">
            <input type="hidden" name="base[discount_img]" class="form-control" value="{{settings('shop_show_logo')?settings('shop_show_logo'):''}}">
        </div>
        <div class="clearfix" style="padding-top: 22px;">
            <div id="filePicker">添加图片</div>
        </div>
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
    <label class="col-sm-3 control-label">是否对外显示 <i class="fa fa-question-circle"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="默认显示在商品列表和商品详情中，设置为“否”后即不再显示"></i>：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[is_open]" type="radio" value="0"> 否</label>
        <label class="checkbox-inline i-checks"><input name="base[is_open]" type="radio" value="1" checked> 是</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">是否允许分销员分享 <i class="fa fa-question-circle"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="默认不允许分销员分享优惠券，设置为“是”后即允许分享该优惠券"></i>：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[is_agent_share]" type="radio" value="0" checked>否</label>
        <label class="checkbox-inline i-checks"><input name="base[is_agent_share]" type="radio" value="1"> 是</label>
    </div>
</div>


<div class="form-group">
    <label class="col-sm-3 control-label">优惠券类型：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[type]" type="radio" checked
                                                       value="0">
            线上券</label>
        <label class="checkbox-inline i-checks"><input name="base[type]" type="radio"
                                                       value="1">
            线下券</label>
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

            {{--<input type="text" class="form-control" name="base[ends_at]"--}}
            {{--value="{{date("Y-m-d H:m",time()+60*60*24*30)}}"--}}
            {{--placeholder="点击选择结束时间" readonly>--}}
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

            {{--<input type="text" class="form-control inline" name="base[useend_at]"--}}
            {{--value="{{date("Y-m-d H:m",time()+60*60*24*30)}}"--}}
            {{--placeholder="点击选择时间" readonly>--}}
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

            {{--<input type="text" class="form-control inline" name="base[useend_at]"--}}
            {{--value="{{date("Y-m-d H:m",time()+60*60*24*30)}}"--}}
            {{--placeholder="点击选择时间" readonly>--}}
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


<div class="form-group">
    <label class="col-sm-3 control-label">跳转链接 <i class="fa fa-question-circle"
                                                  data-toggle="tooltip" data-placement="top"
                                                  data-original-title="设置该值后，在H5版本中立即使用点击后将跳转到该设置值，否则默认展示可以使用该优惠券的商品列表"></i>：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="base[url]" placeholder="请输入整个url，包含http"/>
    </div>
</div>