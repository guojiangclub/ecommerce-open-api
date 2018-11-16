{{--@extends('store-backend::dashboard')--}}

{{--@section ('title','购物车列表')--}}

{{--@section('breadcrumbs')--}}
    {{--<h2>购物车列表</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">购物车列表</li>--}}
    {{--</ol>--}}
{{--@endsection--}}


{{--@section('content')--}}


    <div class="tabs-container">

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">

                 <div class="panel-body">

                     <a  data-toggle="modal" class="btn btn-primary" style="margin-bottom: 10px"
                         data-target="#modal" data-backdrop="static" data-keyboard="false"
                         data-link="{{route('admin.cart.getExportData',['type'=>'xls'])}}" id="all-xls"
                         data-url="{{route('admin.export.index',['toggle'=>'all-xls'])}}"
                         data-type="xls"
                         href="javascript:;">导出购物车数据</a>

                    <div class="table-responsive">
                        @if(count($cart)>0)
                            <table class="table table-hover table-striped">
                                <tbody>
                                <!--tr-th start-->
                                <tr>
                                    <th>用户ID</th>
                                    <th>商品类型</th>
                                    <th>商品ID</th>
                                    <th>数量</th>
                                    <th>单价</th>
                                    <th>总价</th>
                                    <th>状态</th>
                                    <th>参数</th>
                                </tr>
                                <!--tr-th end-->
                                @foreach ($cart as $item)
                                    <tr>
                                        {{--<td>{{substr($item->key, 5)}}</td>--}}
                                        <td>{{$item->user_id}}</td>
                                        <td>{{$item->type}}</td>
                                        <td>{{$item->id}}</td>
                                        <th>{{$item->qty}}</th>
                                        <th>{{$item->price}}</th>
                                        <th>{{$item->total}}</th>
                                        <th>{{$item->status == 'online' ? '有效' : '无效'}}</th>
                                        <td>
                                            @if (!empty(json_decode($item->attributes)))
                                            @foreach(json_decode($item->attributes) as $key => $value)
                                                {{$key}} : {{$value}}
                                            @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="8" class="footable-visible">
                                        {!! $cart->render() !!}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        @else
                            <div>
                                &nbsp;&nbsp;&nbsp;当前无数据
                            </div>
                        @endif
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade"></div>
{{--@endsection--}}











