<h4>基本规则</h4>
<hr class="hr-line-solid">

<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
        <label>
            <input type="checkbox" name="rules[1][type]"
                   value="item_total" class="switch-input"> 订单总金额满
        </label>
        <span class="sw-hold">XX</span>
        <input type="text" style="display: none" name="rules[1][value]" class="sw-value">元
    </div>
</div>

<!--订单总金额 end-->
<div class="hr-line-dashed"></div>

<!--商品数量-->

<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
        <label>
            <input type="checkbox" name="rules[2][type]"
                   value="cart_quantity" class="switch-input"> 商品数量满
        </label>
        <span class="sw-hold">XX</span>
        <input type="text" name="rules[2][value]" class="sw-value"
               style="display: none">件
    </div>
</div>

<!--商品数量 end-->
<div class="hr-line-dashed"></div>

<!--指定产品-->

<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
        <label>
            <input type="checkbox" value="contains_product" name="rules[3][type]" class="switch-input"> 指定产品
        </label>

        <fieldset class="sw-value" style="display: none;">
            <div class="form-group">
                <label class="col-sm-2 control-label">SKU：</label>
                <div class="col-sm-10">
<textarea style="height: 200px" class="form-control  col-sm-10"
          name="rules[3][value][sku]"></textarea>

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
                    <i class="countSpu">0</i>
                    个商品，<a data-toggle="modal"
                           data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
                           data-url="{{route('admin.promotion.getSpu', ['action' => 'view'])}}">点击查看</a>)

                    <input type="hidden" id="selected_spu" name="rules[3][value][spu]"
                           value="">

                </div>
            </div>
        </fieldset>
    </div>
</div>

<!--指定产品 end-->
<div class="hr-line-dashed"></div>

<!--指定分类-->

<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
        <label>
            <input type="checkbox" name="rules[4][type]"
                   value="contains_category" class="switch-input"> 指定分类
        </label>

        <fieldset class="sw-value" style="display: none">
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-1 rule-category">

                    <table class="table table-hover category_table" id="category_list_table">
                        @foreach($category as $val)
                            <tr id="{{ $val->id }}" parent="{{ $val->parent_id }}"
                                style="{{$val->parent_id == 0 ? '' : 'display: none'}}">
                                <td>
                                    <img style='margin-left:{{ ($val->level - 1) * 20 }}px'
                                         class="operator"
                                         src="{!! url('assets/backend/images/open.gif') !!}"
                                         onclick="displayData(this);" alt="打开"/>
                                    <input value="{{$val->id}}"
                                           name="rules[4][value][items][]"
                                           type="checkbox"
                                           icheck-name="dep{{$val->dep}}"/>
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
                    <i class="countExcludeSpu">0</i>
                    个商品，<a data-toggle="modal"
                           data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
                           data-url="{{route('admin.promotion.getSpu', ['action' => 'view_exclude'])}}">点击查看</a>
                    )
                    <input type="hidden" id="exclude_spu" name="rules[4][value][exclude_spu]">
                </div>
            </div>
        </fieldset>
    </div>
</div>

<!--指定分类END-->