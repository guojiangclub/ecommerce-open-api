<div class="col-lg-6">
    <h2 class="header">规则：</h2>
    <div class="alert alert-danger">
        提示：请勿添加重复的规则类型
    </div>
    <fieldset id="rules_box">
        @foreach($discount->discountRules as $key => $item)
            <div class="promotion_rules_box" id="promotion_rules_{{$key + 1}}">
                <div class="form-group">
                    <label class="col-sm-2 control-label">规则类型：</label>
                    <div class="col-sm-10">
                        <select class="form-control m-b rules-select" data-num="{{$key + 1}}"
                                name="rules[{{$key + 1}}][type]" onchange="rulesChange(this)">
                            <option value="item_total" {{$item->type == 'item_total'?'selected' : ''}}>订单总金额</option>
                            <option value="cart_quantity" {{$item->type == 'cart_quantity'?'selected' : ''}}>商品数量
                            </option>
                            <option value="contains_product" {{$item->type == 'contains_product'?'selected' : ''}}>
                                指定产品
                            </option>
                            <option value="contains_category" {{$item->type == 'contains_category'?'selected' : ''}}>
                                指定分类
                            </option>
                            <option value="contains_role" {{$item->type == 'contains_role'?'selected' : ''}}>指定角色
                            </option>
                        </select>
                    </div>
                </div>

                <fieldset id="configuration_{{$key + 1}}">
                    @if($item->type == 'item_total')
                        <div class="form-group">
                            <label class="col-sm-2 control-label">金额：</label>
                            <div class="col-sm-10">
                                <div class="input-group m-b">
                                    <span class="input-group-addon">$</span>
                                    <input class="form-control" type="text" name="rules[{{$key + 1}}][value]"
                                           value="{{$item->RulesValue / 100}}">
                                </div>
                            </div>
                        </div>
                    @elseif($item->type == 'cart_quantity')
                        <div class="form-group">
                            <label class="col-sm-2 control-label">数量：</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="rules[{{$key + 1}}][value]"
                                       value="{{$item->RulesValue}}">
                            </div>
                        </div>
                    @elseif($item->type == 'contains_product')
                        <div class="form-group">
                            <label class="col-sm-2 control-label">SKU：</label>
                            <div class="col-sm-10">
                                <textarea style="height: 200px" class="form-control  col-sm-10" name="rules[{{$key + 1}}][value][sku]">{{ showTextArea($item->RulesValue['sku']) }}</textarea>
                                {{--<input class="form-control form-inputTagator col-sm-10 goodsSku"--}}
                                       {{--name="rules[{{$key + 1}}][value][sku]" placeholder="" type="text"--}}
                                       {{--value="{{$item->RulesValue['sku']}}">--}}
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
                                (已添加
                                <i class="countSpu">{{$item->RulesValue['spu'] ? count(explode(',',$item->RulesValue['spu'])) : 0 }}</i>
                                个商品，<a data-toggle="modal"
                                       data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
                                       data-url="{{route('admin.promotion.getSpu', ['action' => 'view'])}}">点击查看</a>)

                                <input type="hidden" id="selected_spu" name="rules[{{$key + 1}}][value][spu]"
                                       value="{{$item->RulesValue['spu']}}">
                                {{--<input class="form-control form-inputTagator col-sm-10 goodsSku"--}}
                                {{--name="rules[{{$key + 1}}][value][spu]" placeholder="" type="text"--}}
                                {{--value="{{$item->RulesValue['spu']}}">--}}
                                {{--<label>输入商品ID，按回车添加</label>--}}
                            </div>
                        </div>
                    @elseif($item->type == 'contains_role')
                        <div class="form-group">
                            <label class="col-sm-2 control-label">选择角色：</label>
                            <div class="col-sm-10">
                                {{--<input class="form-control" type="text" name="el_promotion[rules][{NUM}][configuration][count]">--}}
                                {{--<input class="form-control" type="text" name="rules[{NUM}][value]">--}}
                                <select name="rules[{{$key + 1}}][value]" id="" class="form-control">
                                    {{--<option selected="selected" value="amount">订单总金额</option>
                                    <option value="count">商品数量</option>
                                    <option value="sku">指定产品</option>
                                    <option value="category">指定分类</option>
                                    <option value="role">指定角色</option>--}}
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}" {{$item->RulesValue == $role->name ? 'selected' : ''}} >{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <label class="col-sm-2 control-label">请选择分类：</label>
                            <div class="col-sm-10 rule-category">
                                <?php $category_key = $key + 1; ?>
                                {{--@foreach($category as $val)--}}
                                {{--<div style="padding:0 0 10px {{$val->level * 10}}px">--}}
                                {{--<input value="{{$val->id}}" name="rules[{{$key + 1}}][value][items][]" type="checkbox"--}}
                                {{--icheck-name="dep{{$val->dep}}"--}}
                                {{--{{in_array($val->id, $item->RulesValue['items'])?'checked':''}}/>--}}
                                {{--{{$val->name}}</div>--}}
                                {{--@endforeach--}}

                                <table class="table table-hover category_table" id="category_list_table">
                                    @foreach($category as $val)
                                        <tr id="{{ $val->id }}" parent="{{ $val->parent_id }}"
                                            style="{{$val->parent_id == 0 ? '' : 'display: none'}}">
                                            <td>
                                                <img style='margin-left:{{ ($val->level - 1) * 20 }}px' class="operator"
                                                     src="{!! url('assets/backend/images/open.gif') !!}"
                                                     onclick="displayData(this);" alt="打开"/>
                                                <input value="{{$val->id}}" name="rules[{{$key + 1}}][value][items][]"
                                                       type="checkbox"
                                                       icheck-name="dep{{$val->dep}}"
                                                        {{in_array($val->id, $item->RulesValue['items'])?'checked':''}}/>
                                                &nbsp;
                                                {{$val->name}}
                                            </td>

                                        </tr>
                                    @endforeach
                                </table>

                                <div>
                                    <label><input type="checkbox" icheck-name="dep"/>全选</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">排除指定产品：</label>
                            <div class="col-sm-10">
                                <a class="btn btn-success" id="chapter-create-btn" data-toggle="modal"
                                   data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
                                   data-url="{{route('admin.promotion.getSpu',['action' => 'exclude'])}}">
                                    点击添加
                                </a>
                                (已排除
                                <i class="countExcludeSpu"> {{isset($item->RulesValue['exclude_spu']) ? count(explode(',',$item->RulesValue['exclude_spu'])) : 0 }} </i>
                                个商品，<a data-toggle="modal"
                                       data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
                                       data-url="{{route('admin.promotion.getSpu', ['action' => 'view_exclude'])}}">点击查看</a>
                                )
                                <input type="hidden" id="exclude_spu" name="rules[{{$key + 1}}][value][exclude_spu]"
                                       value="{{isset($item->RulesValue['exclude_spu']) ? $item->RulesValue['exclude_spu'] :''}}">
                            </div>
                        </div>
                    @endif
                </fieldset>

                <button type="button" class="col-lg-offset-5 btn btn-w-m btn-danger" onclick="delRules(this)">删除
                </button>
                <div class="hr-line-dashed"></div>
            </div>
        @endforeach
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
            <input type="hidden" value="{{$discount->discountAction->id or 0}}" name="action_id">
            <select class="form-control m-b action-select" name="action[type]" onchange="actionChange(this)">
                <option selected="selected" value="order_fixed_discount"
                        {{$discount->discountAction->type == 'order_fixed_discount'?'selected' : ''}}>订单减金额
                </option>
                <option value="order_percentage_discount"
                        {{$discount->discountAction->type == 'order_percentage_discount'?'selected' : ''}}>订单打折
                </option>

                <option {{$discount->discountAction->type == 'goods_fixed_discount'?'selected' : ''}} value="goods_fixed_discount">
                    商品减金额
                </option>
                <option {{$discount->discountAction->type == 'goods_percentage_discount'?'selected' : ''}} value="goods_percentage_discount">
                    商品打折
                </option>
                <option {{$discount->discountAction->type == 'goods_percentage_by_market_price_discount'?'selected' : ''}} value="goods_percentage_by_market_price_discount">
                    员工内购折扣
                </option>

            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3" id="promotion-action">

        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">积分类型：</label>
        <div class="col-sm-9">
            <input type="hidden" value="{{$discount->discountPointAction->id or 0}}" name="point_action_id">
            <select class="form-control m-b action-select" name="point-action[type]">
                <option selected value="goods_times_point">商品积分</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3" id="promotion-point-action">
            <div class="input-group m-b">
                <input class="form-control" type="text" name="point-action[configuration]"
                       value="{{$discount->discountPointAction->ActionValue or 0}}">
                <span class="input-group-addon">%</span>
            </div>
            <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>

            <p style="color: red;">值为 0 时，将以商品中设置的规则为准，否则以此处设置为准</p>

            <p style="color: red;">促销为优惠券时，此设置无效</p>
        </div>
    </div>

</div>

@include('store-backend::promotion.includes.template')