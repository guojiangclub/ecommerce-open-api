<script type="text/x-template" id="rules_template">
    <div class="promotion_rules_box" id="promotion_rules_{NUM}">
        <div class="form-group">
            <label class="col-sm-2 control-label">规则类型：</label>
            <div class="col-sm-10">
                <select class="form-control m-b rules-select" data-num="{NUM}" name="rules[{NUM}][type]"
                        onchange="rulesChange(this)">
                    <option selected="selected" value="item_total">订单总金额</option>
                    <option value="cart_quantity">商品数量</option>
                    <option value="contains_product">指定产品</option>
                    <option value="contains_category">指定分类</option>
                    <option value="contains_role">指定角色</option>
                </select>
            </div>
        </div>

        <fieldset id="configuration_{NUM}">
            <div class="form-group">
                <label class="col-sm-2 control-label">金额：</label>
                <div class="col-sm-10">
                    <div class="input-group m-b">
                        <span class="input-group-addon">$</span>
                        {{--<input class="form-control" type="text" name="el_promotion[rules][{NUM}][configuration][amount]">--}}
                        <input class="form-control" type="text" name="rules[{NUM}][value]">
                    </div>
                </div>
            </div>
        </fieldset>

        <button type="button" class="col-lg-offset-5 btn btn-w-m btn-danger" onclick="delRules(this)">删除</button>
        <div class="hr-line-dashed"></div>
    </div>
</script>

<!-- item_total configuration-->
<script type="text/x-template" id="rules_item_total_template">
    <div class="form-group">
        <label class="col-sm-2 control-label">金额：</label>
        <div class="col-sm-10">
            <div class="input-group m-b">
                <span class="input-group-addon">$</span>
                {{--<input class="form-control" type="text" name="el_promotion[rules][{NUM}][configuration][amount]">--}}
                <input class="form-control" type="text" name="rules[{NUM}][value]" placeholder="订单总金额大于或等于输入值时执行所设定的动作">
            </div>
        </div>
    </div>
</script>

{{--cart_quantity configuration--}}
<script type="text/x-template" id="rules_cart_quantity_template">
    <div class="form-group">
        <label class="col-sm-2 control-label">数量：</label>
        <div class="col-sm-10">
            {{--<input class="form-control" type="text" name="el_promotion[rules][{NUM}][configuration][count]">--}}
            <input class="form-control" type="text" name="rules[{NUM}][value]" placeholder="数量大于或等于输入值时执行所设定的动作">
        </div>
    </div>
</script>

{{--sku configuration--}}
<script type="text/x-template" id="rules_sku_template">
    <div class="form-group">
        <label class="col-sm-2 control-label">SKU：</label>
        <div class="col-sm-10">
            <textarea style="height: 200px" class="form-control  col-sm-10" name="rules[{NUM}][value][sku]"></textarea>
{{--            {!! Form::text('tags',  '' , ['class' => 'form-control form-inputTagator col-sm-10 goodsSku','name' => 'rules[{NUM}][value][sku]', 'placeholder' => '']) !!}--}}
            <label>输入产品SKU值，每行一个</label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">SPU：</label>
        <div class="col-sm-10">
            <a class="btn btn-success" id="chapter-create-btn" data-toggle="modal"
               data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
               data-url="{{route('admin.promotion.getSpu',['action' => 'add'])}}">
                点击添加商品
            </a>
            (已添加 <i class="countSpu"> 0 </i> 个商品，<a data-toggle="modal"
                                                    data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
                                                    data-url="{{route('admin.promotion.getSpu', ['action' => 'view'])}}">点击查看</a> )
            <input type="hidden" id="selected_spu" name="rules[{NUM}][value][spu]">
            {{--{!! Form::text('tags',  '' , ['class' => 'form-control form-inputTagator col-sm-10 goodsSku','name' => 'rules[{NUM}][value][spu]', 'placeholder' => '']) !!}--}}
            {{--<label>输入商品ID，按回车添加</label>--}}
        </div>
    </div>
</script>

{{--sku configuration--}}
<script type="text/x-template" id="rules_role_template">
    <div class="form-group">
        <label class="col-sm-2 control-label">选择角色：</label>
        <div class="col-sm-10">
            {{--<input class="form-control" type="text" name="el_promotion[rules][{NUM}][configuration][count]">--}}
            {{--<input class="form-control" type="text" name="rules[{NUM}][value]">--}}
            <select name="rules[{NUM}][value]" id="" class="form-control">
                {{--<option selected="selected" value="amount">订单总金额</option>
                <option value="count">商品数量</option>
                <option value="sku">指定产品</option>
                <option value="category">指定分类</option>
                <option value="role">指定角色</option>--}}
                @foreach($roles as $role)
                    <option value="{{$role->name}}">{{$role->display_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</script>

{{--percentage action --}}
<script type="text/x-template" id="percentage_action_template">
    <div class="input-group m-b">
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
        <span class="input-group-addon">%</span>
    </div>
</script>

<script type="text/x-template" id="goods_percentage_action_template">
    <div class="input-group m-b">
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
        <span class="input-group-addon">%</span>
    </div>
    <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>
</script>

{{--discount action --}}
<script type="text/x-template" id="discount_action_template">
    <div class="input-group m-b">
        <span class="input-group-addon">$</span>
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
    </div>
</script>

<script type="text/x-template" id="goods_discount_action_template">
    <div class="input-group m-b">
        <span class="input-group-addon">$</span>
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
    </div>
    <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>
</script>

<script type="text/x-template" id="goods_percentage_action_template">
    <div class="input-group m-b">
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
        <span class="input-group-addon">%</span>
    </div>
    <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>
</script>

<script type="text/x-template" id="goods_times_point_action_template">
    <div class="input-group m-b">
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
        <span class="input-group-addon">%</span>
    </div>
    <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>
</script>
