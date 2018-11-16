{{--@extends('store-backend::dashboard')

@section ('title','添加套餐商品')


@section('breadcrumbs')
    <h2>创建套餐商品</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active"><a href="{{route('admin.promotion.suit.index')}}">套餐列表</a></li>
        <li class="active">添加套餐商品</li>
    </ol>
@endsection


@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="row">
                <div class="panel-body">
                    {!! Form::open( [ 'url' => [route('admin.promotion.suit.store.item',['id'=>$id])], 'method' => 'POST', 'id' => 'create-suit-goods-form','class'=>'form-horizontal'] ) !!}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">*套餐商品类型：</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="item_type" id="select_item_type">
                                <option value ="spu">SPU</option>
                                {{--<option value ="sku">SKU</option>--}}
                            </select>
                        </div>
                    </div>

                    <div class="form-group spu-box">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="goods_name" value="{{request('goods_name')}}"  placeholder="请输入商品名称" />
                            <input type="text" class="form-control"
                                   style="opacity: 0"
                                   name="spu" placeholder="请输入商品ID"   />
                            @if(count($goods)>0)
                            <select class="form-control" name="goods" id="goods">
                                @foreach($goods as $k=>$item)
                                    @if($k==0)
                                   <option value ="0">——————请选择要添加的商品——————</option>
                                    @endif
                                   <option value ="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @endif

                            <span>&nbsp;&nbsp;</span><span id="spu-type-error" style="color: red"></span>
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-primary" type="button" id="spu-add-new">搜索</button>
                            <button class="btn btn-primary" type="button" id="spu-add" style="opacity: 0">添加</button>
                        </div>
                    </div>

                    <div class="form-group spu-box-img" style="display: none" >
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-1">
                            <img  src="" alt="" width="80" height="80">
                        </div>
                        <div class="col-sm-9">
                            <span class="goods_id_spu"></span>
                            <br>
                            <span class="goods_name_spu"></span>
                            <br>
                            <span class="goods_nums_spu"></span>
                            <br>
                        </div>
                    </div>



                    <div class="form-group sku-box-spu" style="display: block" >
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <table class="table table-bordered" id="discount-table-spu">
                                <thead>
                                <tr>
                                    <th>数量</th>
                                    <th>销售价(元)</th>
                                    <th>套餐价(元)</th>
                                    <th>排序（越大越靠前）</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="showData">
                                    <input type="hidden" id="item_type_spu" name="item_type" class="form-control" value="spu">
                                    <input type="hidden" id="item_id_spu"  name="item_id" class="form-control" value="1">

                                    <td> <input type="number" name="quantity_spu" class="form-control" value="1" disabled="disabled" ></td>
                                    <td> <input type="text" name="origin_price_spu" class="form-control" value="" disabled="disabled"></td>
                                    <td> <input type="text" name="package_price_spu" class="form-control" value="" ></td>
                                    <td> <input type="text" name="sort_spu" class="form-control" value="1" ></td>
                                    <td><a href="javascript:;" id="empty_spu">清空</a></td>
                                </tr>
                                </tbody>
                                {{--<tfoot>--}}
                                {{--<tr>--}}
                                {{--<td colspan="6">--}}
                                {{--<button class="btn btn-primary" id="add-condition" type="button">添加单品</button>--}}
                                {{--<a class="btn btn-success" id="chapter-create-btn" data-toggle="modal"--}}
                                {{--data-target="#spu_modal" data-backdrop="static" data-keyboard="false"--}}
                                {{--data-url="">--}}
                                {{--从列表选择--}}
                                {{--</a>--}}
                                {{--</td>--}}
                                {{--</tr>--}}
                                {{--</tfoot>--}}
                            </table>

                        </div>
                    </div>


                    <div class="form-group sku-box" style="display:none">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="sku" placeholder="请手动输入产品SKU" />
                            <span>&nbsp;&nbsp;</span><span id="sku-type-error" style="color: red"></span>
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-primary" type="button" id="sku-add">添加</button>
                        </div>
                    </div>

                    <div class="form-group sku-box-img" style="display: none" >
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-1">
                              <img  src="" alt="" width="80" height="80">
                        </div>
                        <div class="col-sm-9">
                            <span class="goods_name"></span>
                            <br>
                            <span class="goods_nums"></span>
                            <br>
                            <span class="goods_spc_name"></span>
                        </div>
                    </div>

                    <div class="form-group sku-box"  style="display:none" >
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <table class="table table-bordered" id="discount-table">
                                <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>数量</th>
                                    <th>原价(元)</th>
                                    <th>套餐价(元)</th>
                                    <th>排序（越大越靠前）</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <input type="hidden" id="item_type" name="item_type" class="form-control" value="">
                                <input type="hidden" id="item_id"  name="item_id" class="form-control" value="" >
                                <tr class="showData">
                                    <td> <input type="text" name="sku" class="form-control" value=""  disabled="disabled"></td>
                                    <td> <input type="number" name="quantity" class="form-control" value="1" disabled="disabled"></td>
                                    <td> <input type="text" name="origin_price" class="form-control" value="" disabled="disabled"></td>
                                    <td> <input type="text" name="package_price" class="form-control" value=""></td>
                                    <td> <input type="text" name="sort" class="form-control" value="1"></td>
                                    <td><a href="javascript:;" id="empty">清空</a></td>
                                </tr>
                                </tbody>
                                {{--<tfoot>--}}
                                {{--<tr>--}}
                                    {{--<td colspan="6">--}}
                                        {{--<button class="btn btn-primary" id="add-condition" type="button">添加单品</button>--}}
                                        {{--<a class="btn btn-success" id="chapter-create-btn" data-toggle="modal"--}}
                                           {{--data-target="#spu_modal" data-backdrop="static" data-keyboard="false"--}}
                                           {{--data-url="">--}}
                                            {{--从列表选择--}}
                                        {{--</a>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--</tfoot>--}}
                            </table>

                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-2 control-label">*必选：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="fixed" type="radio"
                                                                           value="1" checked>
                                是</label>
                            {{--<label class="checkbox-inline i-checks"><input name="fixed" type="radio"--}}
                                                                           {{--value="0"> 否</label>--}}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">*状态：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                           value="1" checked>
                                有效</label>
                            <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                           value="0"> 无效</label>
                        </div>
                    </div>


                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        {{--<button class="btn btn-primary" id="goods_add_sku" type="submit" >保存</button>--}}
                        <button class="btn btn-primary" id="goods_add" type="button">保存</button>
                        <input type="hidden" name="suit_id" value="{{$id}}">
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
        var Type='spu';
        var Status=1;
        var AddUrl="{{route('admin.promotion.suit.create.item',$id)}}";
    </script>
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
    {{--{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}
    <script>
        $(function () {
            $('#select_item_type').change(function () {
                var type= $('#select_item_type').val();
                if(type=='sku'){
                    Type='sku';
                    $('#discount-table-spu input').val('');
                    $("#item_type_spu").val('');
                    $("#item_id_spu").val('');
                    $('.sku-box').show();
                    $('#goods_add_spu').hide();
                    $('.sku-box-spu').hide();
                    $('.spu-box').hide();
                    $('.spu-box-img').hide();

                }
                if(type=='spu'){
                    Type='spu';
                    $('.spu-box').show();
                    $('#discount-table input').val();
                    $("#item_type").val('');
                    $("#item_id").val('');
                    $('.sku-box-img').hide();
                    $('.sku-box').hide();
                    $('#goods_add_spu').show();
                    $('.sku-box-spu').show();
                    $('input[name=quantity_spu]').val(1);
                    $('input[name=sort_spu]').val(1);
                }
            })

            $("input[name=sku]").focus(function () {
                $('#sku-type-error').text('');
            })

            $("input[name=spu]").focus(function () {
                $('#spu-type-error').text('');
            })


            $('input[name=sort_spu]').change(function () {
                var num=$('input[name=sort_spu]').val();
                var reg = /^\d+(?=\.{0,1}\d+$|$)/;
                if(!reg.test(num)) {
                    $('input[name=sort_spu]').val('');
                }
            })


//            清空
            $('#empty').click(function () {
                $('#discount-table input').val('');
                $('input[name=quantity]').val(1);
                $('input[name=sort]').val(1);
                $('input[name=sku]').val("");
                $('#item_id').val("");
                $('#item_type').val("");
                $('.sku-box-img').hide();
                $('input[name=origin_price]').val('');
                $('input[name=package_price]').val('');
            })

            $('#empty_spu').click(function () {
                $('input[name=quantity_spu]').val(1);
                $('input[name=sku]').val("");
                $('#item_id_spu').val("");
                $('#item_type_spu').val("");
                $('.spu-box-img').hide();
                $('input[name=origin_price_spu]').val('');
                $('input[name=package_price_spu]').val('');
                $('input[name=spu]').val('');
                $("#goods").val(0);
            })


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



            $('#sku-add').click(function () {
                var sku= $("input[name=sku]").val();
                if(sku==""){
                    $('#sku-type-error').text('请输入产品SKU');
                    return false;
                }
                var url="{{route('admin.promotion.suit.getGoodsInfo',['_token'=>csrf_token()])}}";
                $.post(url,{sku:sku},function (res) {
                    if(!res.status){
                        $('#sku-type-error').text('产品SKU不存在，添加失败');
                        return false;
                    }

                    if(res.data==null||res.data.is_show!==1){
                        $('#sku-type-error').text('产品SKU不存在，添加失败');
                        return false;
                    }else if(res.data.store_nums==0){
                        $('#sku-type-error').text('当前产品SKU库存为0，添加失败');
                        return false;
                    }
                    $('#discount-table').find('input[name=sku]').val(res.data.sku);
                    $('#item_id').val(res.data.id);
                    $('#discount-table').find('input[name=origin_price]').val(res.data.sell_price);
                    if(res.data.goods.img!==""){
                        $('.sku-box-img img').attr('src',res.data.goods.img);
                        $('.goods_nums').text('库存：'+res.data.store_nums);
                        $('.goods_spc_name').text(res.data.specs_value_name);
                        $('.goods_name').text('Name：' +res.data.goods.name);
                        $('.sku-box-img').show();
                    }
                })
            })


            $('#spu-add').click(function () {
                var spu = $("input[name=spu]").val();
                if (spu == "") {
//                    $('#spu-type-error').text('请输入商品ID');
                    return false;
                }
                var url = "{{route('admin.promotion.suit.getGoodsInfo',['_token'=>csrf_token()])}}";
                $.post(url, {spu: spu}, function (res) {
                    if(!res.status){
//                        $('#spu-type-error').text('商品不存在，添加失败');
                        return false;
                    }
                    if (res.data == null) {
//                        $('#spu-type-error').text('商品不存在，添加失败');
                        return false;
                    } else if (res.data.store_nums == 0) {
//                        $('#spu-type-error').text('当前商品库存为0，添加失败');
                        return false;
                    }
                    $('#item_id_spu').val(res.data.id);
                    $('input[name=origin_price_spu]').val(res.data.sell_price);
                    $('.spu-box-img img').attr('src', res.data.img);
                    $('.goods_nums_spu').text('库存：' + res.data.store_nums);
                    $('.goods_name_spu').text('Name：' +res.data.name);
                    $('.goods_id_spu').text('ID：' + res.data.id);
                    $('.spu-box-img').show();

            })
        })


            $('#spu-add-new').click(function () {
                var goods_name=$('input[name=goods_name]').val();
                var url= AddUrl+"?goods_name="+goods_name;
                window.location.href=url;
            })

//            选中事件
            $('#goods').change(function () {
                var goods_id=$(this).val();
                if(goods_id!=0) {
                    $('input[name=spu]').val(goods_id);
                    $('#spu-add').trigger("click");
                }else{
                    $('input[name=spu]').val(0);
                }

            })


         $('input[name=status]').on('ifChecked', function(event){
                if(Status==1){
                    Status=0;
                }else{
                    Status=1;
                }
            });

            $('#goods_add').click(function () {
                if(Type=='sku'){
                    var data={
                        'suit_id':$('input[name=suit_id]').val(),
                        'item_id':$('#item_id').val(),
                        'item_type':Type,
                        'origin_price':$('input[name=origin_price]').val(),
                        'package_price':$('input[name=package_price]').val(),
                        'fixed':1,
                        'quantity':$('input[name=quantity]').val(),
                        'sort':$('input[name=sort]').val(),
                        'status':Status,
                    }
                }

                if(Type=='spu'){
                    var data={
                        'suit_id':parseInt($('input[name=suit_id]').val()),
                        'item_id':$('#item_id_spu').val(),
                        'item_type':Type,
                        'origin_price':$('input[name=origin_price_spu]').val(),
                        'package_price':$('input[name=package_price_spu]').val(),
                        'fixed':1,
                        'quantity':$('input[name=quantity_spu]').val(),
                        'sort':$('input[name=sort_spu]').val(),
                        'status':Status,
                    }
                }
                var ing=true;
                jQuery.each(data, function(k, item) {
                    if(item==""&&item!==0){
                        if(k=='origin_price'||k=='item_id'){
                            swal("保存失败!", "请输入商品ID，并点击添加按钮自动获取相关产品信息", "warning");
                            return ing=false;
                        }
                        swal("保存失败!", "套餐价或排序不能为空", "warning");
                        return ing=false;
                    }
                });
                if(ing){
                    var url="{{route('admin.promotion.suit.store.item',['_token'=>csrf_token()])}}";
                    $.post(url,data,function (ret) {
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
                    })
                }
            })



        })
    </script>

    <script>
        var goods_num="{{count($goods)}}";
        if("{{request('goods_name')}}" && goods_num==0){
            swal("搜索失败", "输入的商品名称不存在或该商品当前库存为0", "warning");
        }
    </script>

{{--@stop--}}











                        