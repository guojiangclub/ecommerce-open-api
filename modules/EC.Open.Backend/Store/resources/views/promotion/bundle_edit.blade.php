@extends('store-backend::dashboard')


@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}
    <style type="text/css">
        .money{width: 100px; display: inline-block}
        .pd20{padding-top: 20px}
    </style>

@stop


@section('breadcrumbs')
    <h2>编辑优惠套餐</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">编辑优惠套餐</li>
    </ol>
@endsection

@section('content')

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="row">

                <div class="panel-body">
                    {!! Form::open( [ 'url' => [route('admin.promotion.bundle.edit',['id' => $bundle->id])], 'method' => 'POST', 'id' => 'create-discount-form','class'=>'form-horizontal'] ) !!}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">标题：</label>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" name="id" value="{{$bundle->id}}"/>
                            <input type="hidden" class="form-control" name="type" value="1"/>
                            <input type="text" class="form-control" name="title" value="{{$bundle->title}}" required="required"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">商品列表：</label>
                        <div class="col-sm-10">
                            <table class="table table-bordered" id="discount-table">
                                <thead>
                                <tr>
                                    <th>类型</th>
                                    <th>SPU/SKU</th>
                                    <th>数量</th>
                                    <th>原价(元)</th>
                                    <th>套餐价(元)</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($bundle->bundleItems as $key => $item)
                                <tr class="showData">
                                    <td> <select name="items[{{$key}}][type]" style="height: 34px;" {{$item->type}}>
                                            @if ($item->type == 'sku')
                                                <option value="sku" selected="selected">SKU</option>
                                                <option value="spu">SPU</option>
                                            @else
                                                <option value="sku">SKU</option>
                                                <option value="spu" selected="selected">SPU</option>
                                            @endif
                                        </select></td>
                                    <td> <input type="text" name="items[{{$key}}][item_id]" class="form-control" value="{{$item->value}}" required="required"></td>
                                    <td> <input type="text" name="items[{{$key}}][quantity]" class="form-control" value="{{$item->quantity}}" required="required"></td>
                                    <td> <input type="text" name="items[{{$key}}][original_price]" class="form-control" value="{{$item->original_price}}" disabled="disabled"></td>
                                    <td> <input type="text" name="items[{{$key}}][bundle_price]" class="form-control" value="{{$item->bundle_price}}" required="required"></td>
                                    <td> <a href="javascript:;" onclick="deltr(this)">删除</a> </td>
                                </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <button class="btn btn-primary" id="add-condition" type="button">添加单品</button>
                                        <a class="btn btn-success" id="chapter-create-btn" data-toggle="modal"
                                           data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
                                           data-url="{{route('admin.promotion.bundle.getSpu')}}">
                                            从列表选择
                                        </a>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">保存设置</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div id="spu_modal" class="modal inmodal fade"></div>

            </div>
        </div>
    </div>

    <script type="text/x-template" id="discount_template">
        <tr class="showData">
            <td> <select name="items[{NUM}][type]" style="height: 34px;" class="bundle-item-type">
                    <option value="sku">SKU</option>
                    <option value="spu">SPU</option>
                </select></td>
            <td> <input type="text" name="items[{NUM}][item_id]" class="form-control bundle-item-id" value="" required="required"></td>
            <td> <input type="text" name="items[{NUM}][quantity]" class="form-control" value="1" required="required"></td>
            <td> <input type="text" name="items[{NUM}][original_price]" class="form-control bundle-item-sellPrice" value="" disabled="disabled"></td>
            <td> <input type="text" name="items[{NUM}][bundle_price]" class="form-control" value="" required="required"></td>
            <td> <a href="javascript:;" onclick="deltr(this)">删除</a> </td>
        </tr>
    </script>
@stop

@section('after-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    @include('store-backend::promotion.includes.singleScript')
    <script>
        $(function () {
            $('#create-discount-form').ajaxForm({
                success: function (result) {
                    if(result.status){
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function() {
                            location = '{{route('admin.promotion.bundle.index')}}';
                        });
                    }else{
                        swal({
                            title: "保存失败！",
                            text: "",
                            type: "error"})
                    }

                }
            });
        });
        function addItem(_self) {
            var num = $('#discount-table tbody').find('tr[class="showData"]').length;
            $('#discount-table tbody').append(discont_html.replace(/{NUM}/g, num));

            var bundle_item = $('#discount-table tbody .showData:last');
            bundle_item.find(".bundle-item-type").val('spu');
            bundle_item.find(".bundle-item-id").val($(_self).data('id'));
            bundle_item.find(".bundle-item-sellPrice").val($(_self).data('price'));

            $('#spu_modal').modal('hide');
        }
    </script>
@stop
