{{--@extends('store-backend::dashboard')

@section ('title','限购商品管理')

@section ('breadcrumbs')
    <h2>限购商品管理</h2>
@stop

@section('after-styles-end')--}}
    <style type="text/css">
        .thumb {
            float: left;
            margin-right: 15px;
            text-align: center;
            width: 50px;
        }
    </style>
{{--@stop


@section('content')--}}
    <div class="tabs-container">

        <ul class="nav nav-tabs">
            <li class="{{ Active::query('status','ACTIVITY') }}"><a no-pjax
                        href="{{route('admin.store.goods.limit',['status'=>'ACTIVITY'])}}">参与限购商品</a></li>
            <li class="{{ Active::query('status','UNACTIVITY') }}"><a no-pjax
                        href="{{route('admin.store.goods.limit',['status'=>'UNACTIVITY'])}}">未参与限购商品</a></li>
            <li class="pull-right">
                <button class="btn btn-primary" data-toggle="modal"
                        data-target="#modal" data-backdrop="static" data-keyboard="false" data-url="{{route('admin.store.goods.limit.syncGoods')}}" type="button">
                一键同步商品
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div>
                        <div class="col-md-8">
                            {{--<input type="checkbox" class="check-full"> 全选 &nbsp;&nbsp;&nbsp;&nbsp;--}}
                            <a class="btn btn-primary" data-toggle="modal-filter" data-target="#modal" data-backdrop="static" data-keyboard="false" data-url="{{route('admin.store.goods.limit.editBatchGoods',['type'=>'status','status'=>request('status'),'value'=>request('value')])}}" href="javascript:;">
                                状态设置
                            </a>
                        </div>
                        <div class="col-md-4">
                            <form action="" method="get" class="form-horizontal">
                                <div class="form-group">
                                        <input type="hidden" name="status" value="{{request('status')}}">
                                        <div class="input-group">
                                            <input type="text" name="value" placeholder="商品名称" value="{{!empty(request('value'))?request('value'):''}}" class=" form-control"> <span class="input-group-btn"><button type="submit" class="btn btn-primary">查找</button></span>
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-hover table-striped table-bordered" id="goods-table">
                        <thead>
                        <tr>
                            <th><input type="checkbox" class="check-all"></th>
                            <th>商品</th>
                            <th>是否参与限购</th>
                            <th>限购开始时间</th>
                            <th>限购结束时间</th>
                            <th>限购数量</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($goods as $value)
                            @if($good=$value->goods)
                                <tr class="goods{{$value->id}}" data-id="{{$value->id}}">
                                    <td><input class="checkbox" type="checkbox" value="{{$value->id}}" name="ids[]"></td>
                                    <td>
                                        <div class="thumb"><img src="{{$good->img}}" width="50"></div>
                                        <p>{{$good->name}}</p>
                                    </td>
                                    <td>
                                        {{$value->activity==1?'参与':'不参与'}}
                                    </td>
                                    <td>
                                        {{$value->starts_at}}
                                    </td>
                                    <td>
                                        {{$value->ends_at}}
                                    </td>
                                    <td>
                                        {{$value->quantity ? $value->quantity : ''}}
                                    </td>
                                    <td>
                                        <a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal" data-backdrop="static" data-keyboard="false" data-url="{{route('admin.store.goods.limit.editGoods',['id'=>$value->id])}}" href="javascript:;">
                                            <i data-toggle="tooltip"data-placement="top" class="fa fa-pencil-square-o" title="编辑"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            <div class="pull-left">
                {!! $goods->total() !!} 件商品
            </div>

            <div class="pull-right">
                {!! $goods->render() !!}
            </div>
            <div class="box-footer clearfix">
            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade" data-keyboard=false data-backdrop="static"></div>
{{--@endsection

@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/sortable/Sortable.min.js') !!}
    @include('store-backend::goods.includes.script')
{{--@endsection--}}