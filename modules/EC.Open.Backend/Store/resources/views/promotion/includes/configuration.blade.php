<div class="col-lg-6">
    <h2 class="header">规则：</h2>
    <div class="alert alert-danger">
        提示：请勿添加重复的规则类型
    </div>
    <fieldset id="rules_box">

    </fieldset>


    <div class="form-group">
        <button type="button" id="add-rules" class="btn btn-w-m btn-info">添加规则</button>
    </div>

</div>


<div class="col-lg-6">
    <h2 class="header">动作：</h2>

    <div class="form-group">
        <label class="col-sm-3 control-label">金额类型：</label>
        <div class="col-sm-9">
            <select class="form-control m-b action-select" name="action[type]" onchange="actionChange(this)">
                <option selected="selected" value="order_fixed_discount">订单减金额</option>
                <option value="order_percentage_discount">订单打折</option>
                <option value="goods_fixed_discount">商品减金额</option>
                <option value="goods_percentage_discount">商品打折</option>
                <option value="goods_percentage_by_market_price_discount">员工内购折扣</option>
                {{-- <option value="goods_times_point">商品积分</option>--}}
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3" id="promotion-action">
            <div class="input-group m-b">
                <span class="input-group-addon">$</span>
                <input class="form-control" type="text" name="action[configuration]" value="0">
            </div>
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">积分类型：</label>
        <div class="col-sm-9">
            <select class="form-control m-b action-select" name="point-action[type]">
                <option selected value="goods_times_point">商品积分</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3" id="promotion-point-action">
            <div class="input-group m-b">
                <input class="form-control" type="text" name="point-action[configuration]">
                <span class="input-group-addon">%</span>
            </div>
            <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>

            <p style="color: red;">值为 0 时，将以商品中设置的规则为准，否则以此处设置为准</p>

            <p style="color: red;">促销为优惠券时，此设置无效</p>
        </div>
    </div>

</div>

@include('store-backend::promotion.includes.template')