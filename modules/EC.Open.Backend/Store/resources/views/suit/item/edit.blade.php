{{--@extends('store-backend::dashboard')

@section ('title','添加套餐商品')


@section('breadcrumbs')
    <h2>编辑套餐商品</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active"><a href="{{route('admin.promotion.suit.index')}}">套餐列表</a></li>
        <li class="active">{{isset($suits->suit->title)?$suits->suit->title:''}}</li>
        <li class="active">编辑套餐商品</li>
    </ol>
@endsection


@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="row">
                <div class="panel-body">
                    {!! Form::open( [ 'url' => [route('admin.promotion.suit.store.item.update',['id'=>$id])], 'method' => 'POST', 'id' => 'edit-suit-goods-form','class'=>'form-horizontal'] ) !!}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">*套餐商品类型：</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="select_item_type">
                                @if($suits->item_type=='spu')
                                <option value ="spu">SPU</option>
                                 @endif
                                 @if($suits->item_type=='sku')
                                      <option value ="sku">SKU</option>
                                 @endif
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="suit_id" value="{{$suits->suit->id}}">


                    <div class="form-group spu-box-img" >
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-1">
                            @if($suits->item_type=='sku')
                              <img  src="{{$suits->product->goods->img}}" alt="" width="80" height="80">
                             @else
                              <img  src="{{$suits->goods->img}}" alt="" width="80" height="80">
                             @endif
                        </div>
                        <div class="col-sm-9">
                            <span class="goods_id">
                                 @if($suits->item_type=='sku')
                                  SKU：{{$suits->product->sku}}
                                @else
                                  商品ID:{{$suits->goods->id}}
                                @endif
                            </span>
                            <br>
                            <span class="goods_name">
                                   @if($suits->item_type=='sku')
                                    Name:{{$suits->product->goods->name}}
                                @else
                                    Name:{{$suits->goods->name}}
                                @endif
                            </span>
                            <br>
                            <span class="goods_nums">
                                  @if($suits->item_type=='sku')
                                   库存:{{$suits->product->store_nums}}
                                @else
                                   库存:{{$suits->goods->store_nums}}
                                @endif
                            </span>
                            <br>
                        </div>
                    </div>

                    <div class="form-group sku-box-spu" style="display: block" >
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <table class="table table-bordered" id="discount-table-spu">
                                <thead>
                                <tr>
                                    @if($suits->item_type=='sku')
                                        <th>SKU</th>
                                    @else
                                        <th>SPU</th>
                                    @endif
                                    <th>数量</th>
                                    <th>销售价(元)</th>
                                    <th>套餐价(元)</th>
                                    <th>排序（越大越靠前）</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="showData">
                                    @if($suits->item_type=='sku')
                                    <td> <input type="text"  class="form-control" value="{{$suits->product->sku}}" disabled="disabled" ></td>
                                    @else
                                        <td> <input type="text" class="form-control" value="{{$suits->item_id}}" disabled="disabled" ></td>
                                     @endif
                                    <td> <input type="number" name="quantity" class="form-control" value="{{$suits->quantity}}" disabled="disabled" ></td>
                                    <td> <input type="text" name="origin_price" class="form-control" value="{{$suits->origin_price}}" disabled="disabled"></td>
                                    <td> <input type="text" name="package_price" class="form-control" value="{{$suits->package_price}}" ></td>
                                    <td> <input type="text" name="sort" class="form-control" value="{{$suits->sort}}" ></td>
                                </tr>
                                </tbody>

                            </table>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*必选：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="fixed" type="radio"
                                                                           value="1"

                                                                           @if($suits->fixed)
                                                                           checked
                                                                           @endif

                                >
                                是</label>
                            {{--<label class="checkbox-inline i-checks"><input name="fixed" type="radio"--}}
                            {{--value="0"> 否</label>--}}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">*状态：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                           value="1"
                                                                           @if($suits->status==1)
                                                                           checked
                                                                           @endif

                                >
                                有效</label>
                            <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                           @if($suits->status==0)
                                                                           checked
                                                                           @endif
                                                                           value="0"> 无效</label>
                        </div>
                    </div>


                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" id="goods_add_sku" type="submit" >保存</button>
                        {{--<button class="btn btn-primary" id="goods_add" type="button">保存</button>--}}
                        {{--<input type="hidden" name="suit_id" value="{{$id}}">--}}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

            <div id="spu_modal" class="modal inmodal fade"></div>

        </div>
    </div>


{{--@endsection

@section('after-scripts-end')--}}
    <script>

        $('input[name=package_price_spu]').change(function () {
            var num=$('input[name=package_price_spu]').val();
            var reg = /^\d+(?=\.{0,1}\d+$|$)/;
            if(!reg.test(num)) {
                $('input[name=package_price_spu]').val('');
            }
        })

        $('input[name=package_price]').change(function () {
            var num=$('input[name=package_price]').val();
            var reg = /^\d+(?=\.{0,1}\d+$|$)/;
            if(!reg.test(num)) {
                $('input[name=package_price]').val('');
            }
        })


        $('input[name=sort]').change(function () {
            var num=$('input[name=sort]').val();
            var reg = /^\d+(?=\.{0,1}\d+$|$)/;
            if(!reg.test(num)) {
                $('input[name=sort]').val('');
            }
        })


        $('#edit-suit-goods-form').ajaxForm({
            beforeSubmit:function (res) {
                var package_price= $('input[name=package_price]').val();
                var package_price_spu= $('input[name=package_price_spu]').val();
                var sort=$('input[name=sort]').val();
                if(package_price==""||package_price_spu==""||sort==""){
                    swal("保存失败!", "套餐价或排序不能为空", "warning");
                    return false;
                };
            },
            success: function (ret) {
                if(ret.status){
                    swal({
                        title: "保存成功",
                        text: "",
                        type: "success",
                        confirmButtonText: "确定"
                    },function () {
                        window.location.href=ret.data.url;
                    });
                }else{
                    swal("保存失败!", "", "warning");
                }

            }
        });
    </script>
{{--@stop--}}











