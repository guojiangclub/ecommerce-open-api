<h4>优惠券基础信息</h4>
<hr class="hr-line-solid">

<div class="form-group">
    <label class="col-sm-3 control-label"><span class="sp-require">*</span>优惠券名称：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" required name="base[title]" placeholder="" oninput="OnInput(event)"
               onpropertychange="OnPropChanged(event)"
               value="{{$discount->title}}"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label"><span class="sp-require">*</span>展示图片<i class="fa fa-question-circle"
                                                                                  data-toggle="tooltip" data-placement="top"
                                                                                  data-original-title="建议上传小于2M的正方形图片；不上传则默认显示商家LOGO"></i>：</label>
    <div class="col-sm-9">
        <div class="pull-left" id="activity-poster">
            <img src="{{$discount->discount_img}}" alt="" class="img" width="182px"  style="margin-right: 23px;">
            <input type="hidden" name="base[discount_img]" class="form-control" value="{{$discount->discount_img}}">
        </div>
        <div class="clearfix" style="padding-top: 22px;">
            <div id="filePicker">添加图片</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label"><span class="sp-require">*</span>兑换码：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" required name="base[code]" placeholder="" value="{{$discount->code}}"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">排促销活动 <i class="fa fa-question-circle"
                                                   data-toggle="tooltip" data-placement="top"
                                                   data-original-title="设置“是”后在下单时选择优惠券后，不能选择促销活动"></i>：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[exclusive]" type="radio"
                                                       value="0" {{$discount->exclusive == 0?'checked' : ''}}> 否</label>
        <label class="checkbox-inline i-checks"><input name="base[exclusive]" type="radio"
                                                       value="1" {{$discount->exclusive == 1?'checked' : ''}}> 是</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">是否对外显示 <i class="fa fa-question-circle"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="默认显示在商品列表和商品详情中，设置为“否”后即不再显示"></i>：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[is_open]" type="radio"
                                                       value="0" {{$discount->is_open == 0?'checked' : ''}}> 否</label>
        <label class="checkbox-inline i-checks"><input name="base[is_open]" type="radio"
                                                       value="1" {{$discount->is_open == 1?'checked' : ''}}> 是</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">是否允许分销员分享 <i class="fa fa-question-circle"
                                                    data-toggle="tooltip" data-placement="top"
                                                    data-original-title="默认不允许分销员分享优惠券，设置为“是”后即允许分享该优惠券"></i>：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[is_agent_share]" type="radio" value="0"
                    {{$discount->is_agent_share == 0?'checked' : ''}}>否</label>
        <label class="checkbox-inline i-checks"><input name="base[is_agent_share]" type="radio" value="1"
                    {{$discount->is_agent_share == 1?'checked' : ''}}
            > 是</label>
    </div>
</div>



<div class="form-group">
    <label class="col-sm-3 control-label">优惠券类型：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[type]" type="radio"
                                                       value="0" {{$discount->type == 0?'checked' : ''}}>
            线上券</label>
        <label class="checkbox-inline i-checks"><input name="base[type]" type="radio"
                                                       value="1" {{$discount->type == 1?'checked' : ''}}>
            线下券</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">规则：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="base[label]" placeholder="" value="{{$discount->label}}"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">使用说明：</label>
    <div class="col-sm-9">
        <textarea id="base_intro" class="form-control" name="base[intro]" rows="4" oninput="OnInput(event)"
                  onpropertychange="OnPropChanged(event)">{{$discount->intro}}</textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label"><span class="sp-require">*</span>发放总量：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="base[usage_limit]" placeholder="" required
               value="{{$discount->usage_limit}}"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">每人限领：</label>
    <div class="col-sm-9">
        <select class="form-control" name="base[per_usage_limit]">
            <option value="1" {{$discount->per_usage_limit==1?'selected':''}}>1张</option>
            <option value="2" {{$discount->per_usage_limit==2?'selected':''}}>2张</option>
            <option value="3" {{$discount->per_usage_limit==3?'selected':''}}>3张</option>
            <option value="4" {{$discount->per_usage_limit==4?'selected':''}}>4张</option>
            <option value="5" {{$discount->per_usage_limit==5?'selected':''}}>5张</option>
            <option value="6" {{$discount->per_usage_limit==6?'selected':''}}>6张</option>
            <option value="8" {{$discount->per_usage_limit==8?'selected':''}}>8张</option>
            <option value="10" {{$discount->per_usage_limit==10?'selected':''}}>10张</option>
        </select>
    </div>
</div>

<div class="form-group" id="two-inputs">
    <label class="col-sm-3 control-label">领取有效期：</label>
    <div class="col-sm-9">
        <div class="input-group date form_datetime" id="start_at">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
            <input type="text" name="base[starts_at]" class="form-control inline" id="date-range200" size="20" value="{{$discount->starts_at}}"
                   placeholder="点击选择时间" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-range12-container"></div>
    </div>

    <div class="col-sm-9 col-sm-offset-3" style="margin-top: 10px">
        <div class="input-group date form_datetime" id="end_at">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
            <input type="text" name="base[ends_at]" class="form-control inline" id="date-range201" value="{{$discount->ends_at}}"
                   placeholder="" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">使用开始时间<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-original-title="如果不设置使用开始时间，默认以领取有效期的开始日期为准"></i>：</label>
    <div class="col-sm-9">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp</span>
            <input type="text" name="base[usestart_at]" class="form-control inline"  id="date-range14" value="{{$discount->usestart_at}}" placeholder="点击选择使用开始时间">
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-usestart_at-container" style="width: 228px"></div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">使用截止时间<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-original-title="如果不设置使用截止时间，默认以领取有效期的截止日期为准"></i>：</label>
    <div class="col-sm-9">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp</span>
            <input type="text" name="base[useend_at]" class="form-control inline"  id="date-range13" value="{{$discount->useend_at}}" placeholder="点击选择使用截止时间">
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-useend-container" style="width: 228px"></div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">状态：</label>
    <div class="col-sm-9">
        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                       value="1" {{$discount->status == 1?'checked' : ''}}> 启用</label>
        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                       value="0" {{$discount->status == 0?'checked' : ''}}> 禁用</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">标签：</label>
    <div class="col-sm-9">
        {!! Form::text('base[tags]',  $discount->tags , ['class' => 'form-control form-inputTagator col-sm-10','id'=>'inputDiscountTags', 'placeholder' => '']) !!}
        <label>输入标签名称，按回车添加</label>
    </div>
</div>


<div class="form-group">
    <label class="col-sm-3 control-label">跳转链接 <i class="fa fa-question-circle"
                                                  data-toggle="tooltip" data-placement="top"
                                                  data-original-title="设置该值后，在H5版本中立即使用点击后将跳转到该设置值，否则默认展示可以使用该优惠券的商品列表"></i>：</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="base[url]" value="{{$discount->url}}" placeholder="请输入整个url，包含http"/>
    </div>
</div>