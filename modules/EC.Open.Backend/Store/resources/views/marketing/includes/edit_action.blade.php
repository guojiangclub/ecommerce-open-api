<h4>事件动作</h4>
<hr class="hr-line-solid">

<div class="form-group">
    <label class="col-sm-2 control-label">
        <input type="checkbox" name="action[1][type]" {{isset($market->action['point'])?'checked':''}}
        value="point" class="switch-input"> 赠送积分：
    </label>
    <div class="col-sm-10">
        <input type="text" style="display: {{isset($market->action['point'])?'block':'none'}}" name="action[1][value]"
               class="sw-value form-control" placeholder="分"
               value="{{isset($market->action['point'])?$market->action['point']:''}}">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">
        <input type="checkbox" name="action[2][type]" {{isset($market->action['coupon'])?'checked':''}}
        value="coupon" class="switch-input"> 赠送优惠券：
    </label>
    <div class="col-sm-10 sw-value" style="display: {{isset($market->action['coupon'])?'block':'none'}}">

        <input type="hidden" id="select_coupons" name="action[2][value]"
               value="{{isset($market->action['coupon'])?implode(',',$market->action['coupon']):''}}">

        <a class="btn btn-success" id="chapter-create-btn" data-toggle="modal"
           data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
           data-url="{{route('admin.marketing.getCoupon')}}">
            点击选择优惠券
        </a>

        <span id="selected_count"></span>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <!--tr-th start-->
                <tr>
                    <th>优惠券名称</th>
                    <th>有效期</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <!--tr-th end-->
                </thead>

                <tbody class="selected-coupons-list">

                </tbody>
            </table>
        </div><!-- /.box-body -->

    </div>
</div>
