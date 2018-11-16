<h4>事件规则</h4>
<hr class="hr-line-solid">

<div class="form-group">
    <label class="col-sm-2 control-label">指定商品：</label>
    <div class="col-sm-10">
        <a class="btn btn-success" id="chapter-create-btn" data-toggle="modal"
           data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
           data-url="{{route('admin.promotion.getSpu',['action' => 'add'])}}">
            点击添加商品
        </a>
        (已添加
        <i class="countSpu">{{count($market->rules['spu'])}}</i>
        个商品，<a data-toggle="modal"
               data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
               data-url="{{route('admin.promotion.getSpu', ['action' => 'view'])}}">点击查看</a>)

        <input type="hidden" id="selected_spu" name="rules"
               value="{{$market->spu_rule}}">
    </div>
</div>
