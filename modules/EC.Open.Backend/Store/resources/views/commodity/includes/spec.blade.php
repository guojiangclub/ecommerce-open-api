@include('store-backend::commodity.includes.spec_template')

<div id="sku-builder">

    <div id="module-specs" class="{{isset($goods_info)?'':'module-specs-create'}}">

    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">批量填充：</label>

        <div class="form-group col-sm-3">
            <div class="input-group m-b"><span
                        class="input-group-addon">市场价:</span>
                <input class="form-control" type="text" id="market-price"
                       placeholder="请输入销售价格" data-name="market_price"
                       data-action="batch">
            </div>
        </div>
        <div class="form-group col-sm-1"></div>
        <div class="form-group col-sm-3">
            <div class="input-group m-b"><span
                        class="input-group-addon">销售价:</span>
                <input class="form-control" type="text" id="all-price"
                       placeholder="请输入销售价格" data-name="sell_price"
                       data-action="batch">
            </div>
        </div>
        <div class="form-group col-sm-1"></div>
        <div class="form-group col-sm-2">
            <div class="input-group m-b"><span
                        class="input-group-addon">数量:</span>
                <input class="form-control" type="text" id="all-quantity"
                       placeholder="请输入数量" data-name="store_nums"
                       data-action="batch">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">产品列表：</label>
        <div class="col-sm-10">
            <table class="table table-bordered" id="sku-table">
                <thead>
                <tr>
                    <!--规格-->
                    <th class="spec_img_th_active">颜色图片</th>
                    <th>市场价</th>
                    <th>销售价</th>
                    <th>数量</th>
                    <th class="sku-th">SKU</th>
                    <th style="width: 40px">操作</th>
                </tr>
                </thead>
                <tbody>
                <!--数据列表-->
                </tbody>
            </table>
        </div>
    </div>

</div>