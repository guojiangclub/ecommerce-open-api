{!! Form::open( [ 'url' => [route('admin.promotion.seckill.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}
<div class="form-group">
    <label class="col-sm-2 control-label">活动名称：</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="base[title]" placeholder="" required/>
    </div>
</div>

<div class="form-group" id="two-inputs">
    <label class="col-sm-2 control-label">活动时间：</label>
    <div class="col-sm-4">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
            <input type="text" name="base[starts_at]" class="form-control inline" id="date-range200" size="20" value=""
                   placeholder="点击选择时间" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        <div id="date-range12-container"></div>
    </div>

    <div class="col-sm-4">
        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
            <input type="text" name="base[ends_at]" class="form-control inline" id="date-range201" size="20" value=""
                   placeholder="" readonly>
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<div class="form-group" style="display: none;">
    <label class="col-sm-2 control-label">状态：</label>
    <div class="col-sm-10">
        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                       value="1" checked>
            有效</label>
        <label class="checkbox-inline i-checks"><input name="base[status]" type="radio"
                                                       value="0"> 无效</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">拍下商品N分钟不付款自动关闭订单：
        <i class="fa fa-question-circle"
           data-toggle="tooltip" data-placement="top"
           data-original-title="如果设置为0，自动关闭订单时间以商城统一设置为准"></i>
    </label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="base[auto_close]" placeholder="" value="0"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">活动标签：</label>
    <div class="col-sm-10">
        {!! Form::text('base[tags]',  '' , ['class' => 'form-control form-inputTagator col-sm-10','id'=>'inputTags', 'placeholder' => '']) !!}
        <label>输入活动标签，按回车添加</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">商品列表：</label>
    <div class="col-sm-10">
        <a class="btn btn-success" id="chapter-create-btn" data-toggle="modal"
           data-target="#modal" data-backdrop="static" data-keyboard="false"
           data-url="{{route('admin.promotion.seckill.getSpu')}}">
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
                <th width="100">秒杀价</th>
                <th width="80">每人限购数量</th>
                <th width="100">佣金比例<i class="fa fa-question-circle"
                                       data-toggle="tooltip" data-placement="top"
                                       data-original-title="如何设置为0，则表示不参与分销"></i></th>
                <th width="80">是否可获得积分</th>
                <th width="80">是否可使用积分</th>
                <th width="80">参与状态</th>
                <th>广告图</th>
                <th width="100">销量展示<i class="fa fa-question-circle"
                                       data-toggle="tooltip" data-placement="top"
                                       data-original-title="0则秒杀列表页显示真实销量；输入大于0的整数，手动修改秒杀列表页销量展示"></i></th>
                <th width="80">排序<i class="fa fa-question-circle"
                                    data-toggle="tooltip" data-placement="top"
                                    data-original-title="数值越大排序越靠前"></i></th>
                <th width="50">操作</th>
            </tr>
            </thead>
            <tbody id="select-goods-box">

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