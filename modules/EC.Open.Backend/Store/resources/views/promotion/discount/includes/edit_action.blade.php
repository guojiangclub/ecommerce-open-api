<h4>优惠动作</h4>
<hr class="hr-line-solid">

<div class="form-group">
    <label class="col-sm-2 control-label">金额类型：</label>
    <div class="col-sm-10">
        <input type="hidden" value="{{$discount->discountAction->id or 0}}" name="action_id">
        <select class="form-control m-b action-select" name="action[type]" onchange="actionChange(this)">
            <?php $type = $discount->discountAction ? $discount->discountAction->type : null; ?>
            <option selected="selected" value="order_fixed_discount"
                    {{$type == 'order_fixed_discount'?'selected' : ''}}>订单减金额
            </option>
            <option value="order_percentage_discount"
                    {{$type == 'order_percentage_discount'?'selected' : ''}}>订单打折
            </option>

            <option {{$type == 'goods_fixed_discount'?'selected' : ''}} value="goods_fixed_discount">
                商品减金额
            </option>
            <option {{$type == 'goods_percentage_discount'?'selected' : ''}} value="goods_percentage_discount">
                商品打折
            </option>
            <option {{$type == 'goods_percentage_by_market_price_discount'?'selected' : ''}} value="goods_percentage_by_market_price_discount">
                员工内购折扣
            </option>

        </select>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2" id="promotion-action">

    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">积分类型：</label>
    <div class="col-sm-10">
        <input type="hidden" value="{{$discount->discountPointAction->id or 0}}" name="point_action_id">
        <select class="form-control m-b action-select" name="point-action[type]">
            <option selected value="goods_times_point">商品积分</option>
        </select>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2" id="promotion-point-action">
        <div class="input-group m-b">
            <input class="form-control" type="text" name="point-action[configuration]"
                   value="{{$discount->discountPointAction->ActionValue or 0}}">
            <span class="input-group-addon">%</span>
        </div>
        <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>

        <p style="color: red;">值为 0 时，将以商品中设置的规则为准，否则以此处设置为准</p>

    </div>
</div>

@include('store-backend::promotion.public.template')