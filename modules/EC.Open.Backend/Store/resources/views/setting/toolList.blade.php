{{--@extends('store-backend::dashboard')

@section ('title','工具管理')

@section('breadcrumbs')
    <h2>工具管理</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">工具管理</li>
    </ol>
@endsection

@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <div class="box box-primary">
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <td>更新库存</td>
                                <td>如果库存数据不对可以点击此处进行库存数据修复</td>
                                <td>
                                    {{--<a href="{!!route('admin.data.fixGoodsStore')!!}">--}}
                                        <button class="btn btn-primary" id="fix_goods_store" type="button">开始处理</button>
                                    {{--</a>--}}
                                </td>
                            </tr>
                            <tr>
                                <td>更新价格</td>
                                <td>如果商品价格区间数据不对可以点击此处进行价格数据修复</td>
                                <td>
                                    <button class="btn btn-primary" id="fix_goods_price" type="button">开始处理</button>
                                </td>
                            </tr>

                            <tr>
                                <td>解冻收货积分</td>
                                <td>如果购物积分用户确认收货后，仍未解冻可以通过此工具来修复</td>
                                <td>
                                    <a class="btn btn-primary" target="_blank" href="{{route('admin.data.handleReceivePoint')}}">开始处理</a>
                                </td>
                            </tr>
                            <tr>
                                <td>清除移动端首页缓存</td>
                                <td></td>
                                <td>
                                    <a class="btn btn-primary" target="_blank" href="{{route('admin.data.handleHomeCache')}}">开始处理</a>
                                </td>
                            </tr>

                            <tr>
                                <td>清除PC头部导航菜单缓存</td>
                                <td></td>
                                <td>
                                    <a class="btn btn-primary" target="_blank" href="{{route('admin.data.handlePCHeadMenuCache')}}">开始处理</a>
                                </td>
                            </tr>

                            <tr>
                                <td>清除商品所有缓存（单品折扣，促销活动，优惠券，属性数据等）</td>
                                <td></td>
                                <td>
                                    <a class="btn btn-primary" target="_blank" href="{{route('admin.data.clearGoodsCache')}}">开始处理</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>

{{--@endsection
@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}

    <script>
        $('#fix_goods_store').on('click',function(){
            $.get('{{route('admin.data.fixGoodsStore')}}',function(data){
                swal({
                    title: data,
                    text: "",
                    type: "success"
                });
            })
        })

        $('#fix_goods_price').on('click',function(){
            $.get('{{route('admin.data.fixGoodsPrice')}}',function(data){
                swal({
                    title: data,
                    text: "",
                    type: "success"
                });
            })
        })



    </script>
{{--@stop--}}