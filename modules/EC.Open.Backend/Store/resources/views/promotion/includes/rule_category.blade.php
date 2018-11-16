<div class="form-group">
    <label class="col-sm-2 control-label">请选择分类：</label>
    <div class="col-sm-10">

        <table class="table table-hover category_table" id="category_list_table">
            @foreach($category as $item)
                <tr id="{{ $item->id }}" parent="{{ $item->parent_id }}" style="{{$item->parent_id == 0 ? '' : 'display: none'}}">
                    <td>
                        <img style='margin-left:{{ ($item->level - 1) * 20 }}px' class="operator"
                             src="{!! url('assets/backend/images/open.gif') !!}" onclick="displayData(this);" alt="打开"/>
                        <input value="{{$item->id}}" name="rules[{{$num}}][value][items][]" type="checkbox" icheck-name="dep{{$item->dep}}"/>&nbsp;
                        {{ $item->name }}
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
        (已排除 <i class="countExcludeSpu"> 0 </i> 个商品，<a data-toggle="modal"
                                                data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
                                                data-url="{{route('admin.promotion.getSpu', ['action' => 'view_exclude'])}}">点击查看</a> )
        <input type="hidden" id="exclude_spu" name="rules[{{$num}}][value][exclude_spu]">
    </div>
</div>
