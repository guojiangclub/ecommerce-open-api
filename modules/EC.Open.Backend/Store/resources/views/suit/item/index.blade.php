{{--@extends('store-backend::dashboard')

@section ('title','套餐列表')

@section('breadcrumbs')
    <h2>

            {{$title}}

    </h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active"><a href="{{route('admin.promotion.suit.index')}}">套餐列表</a></li>
        <li class="active">
            {{$title}}
        </li>
    </ol>
@endsection


@section('content')--}}

    @if(Session::has('message'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <a class="btn btn-primary margin-bottom" href="{{route('admin.promotion.suit.create.item',['id'=>$id])}}">添加商品</a>
            <div class="box box-primary">
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <!--tr-th start-->
                        <tr>
                            <th>
                                商品
                            </th>
                            <th>库存</th>
                            {{--<th>类型</th>--}}
                            <th>商品ID</th>
                            <th>销售价</th>
                            <th>套餐价</th>
                            <th>数量</th>
                            <th>排序(数字越大越靠前)</th>
                            <th>操作</th>
                        </tr>

                        @if(count($suits)>0)
                            @foreach($suits as $item)
                                <tr>
                                    <td>
                                        @if($item->item_type=='spu')
                                            <a href="{{route('admin.goods.edit',['id'=>$item->item_id])}}" target="_blank" >
                                                <img src="{{$item->goods->img}}" alt="" width="50" height="50">
                                            </a>
                                            <span>{{$item->goods->name}}</span>
                                        @else
                                            <a href="{{route('admin.goods.edit',['id'=>$item->product->goods->id])}}" target="_blank" >
                                                <img src="{{$item->product->goods->img}}" alt="" width="50" height="50">
                                            </a>
                                            <span>{{$item->product->goods->name}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->item_type=='spu')
                                            {{$item->goods->store_nums}}
                                        @else
                                            {{$item->product->store_nums}}
                                        @endif

                                    </td>
                                    {{--<td>--}}
                                        {{--{{strtoupper($item->item_type)}}--}}
                                    {{--</td>--}}
                                    <td>
                                        @if($item->item_type=='spu')
                                        {{$item->item_id}}
                                        @else
                                            {{$item->product->sku}}
                                        @endif

                                    </td>
                                    <td>
                                        {{$item->origin_price}}
                                    </td>
                                    <td>
                                        {{$item->package_price}}
                                    </td>
                                    <td>
                                        {{$item-> quantity}}
                                    </td>
                                    <td>
                                        {{$item->sort}}
                                    </td>
                                    <td>
                                        <a no-pjax
                                                class="btn btn-xs btn-primary"
                                                href="{{route('admin.promotion.suit.create.item.edit',['id'=>$item->id])}}">
                                            <i data-toggle="tooltip" data-placement="top"
                                               class="fa fa-pencil-square-o"
                                               title="编辑"></i></a>


                                        <span  class="btn btn-xs btn-danger del" data-url="{{route('admin.promotion.suit.delete.item',['id'=>$item->id])}}">
                                            <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="删除"></i>
                                        </span>

                                        <a>
                                            <i class="fa switch @if($item->status) fa-toggle-on @else fa-toggle-off @endif" title="切换状态" value = {{$item->status}} >
                                                <input type="hidden" value={{$item->id}}>
                                            </i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                        @endif


                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>

{{--@endsection

@section('after-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
    <script>
        $('.copyBtn').zclip({
            path: "{{url('assets/backend/libs/jquery.zclip/ZeroClipboard.swf')}}",
            copy: function(){
                return $(this).prev().val();
            }
        });
    </script>

    <script>
        $(function () {
            $('.del').on('click', function () {
                var obj = $(this);
                swal({
                    title: "确定删除该商品吗?",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "取消",
                    confirmButtonText: "删除",
                    closeOnConfirm: false
                }, function () {
                    var url =obj.data('url');
                    $.post(url, function (ret) {
                        if(ret.status){
                            swal({
                                title: "删除成功",
                                text: "",
                                type: "success",
                                confirmButtonText: "确定"
                            },function () {
                                location.reload();
                            });
                        }else{
                            swal("删除失败!", "", "warning");
                        }
                    });

                });

            });
        });

        $('.switch').on('click', function(){
            var value = $(this).attr('value');
            var modelId = $(this).children('input').attr('value');
            value = parseInt(value);
            modelId = parseInt(modelId);
            value = value ? 0 : 1;
            var that = $(this);
            $.post("{{route('admin.suit.toggle.suit.item.status')}}",
                    {
                        status: value,
                        aid: modelId
                    },
                    function(res){
                        if(res.status){
                            that.toggleClass("fa-toggle-off , fa-toggle-on");
                            that.attr('value', value);
                            location.reload()
                        }else{
                            swal({
                                title: "操作失败",
                                text: "",
                                type: "error",
                                confirmButtonText: "确定"
                            },function () {
                                location.reload();
                            });
                        }
                    });

        })
    </script>
{{--@endsection--}}










