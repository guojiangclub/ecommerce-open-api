@extends('store-backend::dashboard')

@section('breadcrumbs')
    <h2>购物车列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">购物车列表</li>
    </ol>
@endsection

@section('content')
    <div class="tabs-container">
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    系统未开启购物车记录功能
                </div>
            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade"></div>
@endsection











