@extends('store-backend::dashboard')

@section ('title','余额提现申请列表')

@section('breadcrumbs')

    <h2>余额提现申请列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.distribution.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">余额提现申请列表</li>
    </ol>

@endsection

@section('after-styles-end')

@stop


@section('content')
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="{{ Active::query('status','STATUS_AUDIT') }}"><a
                        href="{{route('admin.balance.cash.index',['status'=>'STATUS_AUDIT'])}}">待审核
                </a>
            </li>

            <li class="{{ Active::query('status','STATUS_WAIT_PAY') }}"><a
                        href="{{route('admin.balance.cash.index',['status'=>'STATUS_WAIT_PAY'])}}">待打款提现
                </a>
            </li>
            <li class="{{ Active::query('status','STATUS_PAY') }}"><a
                        href="{{route('admin.balance.cash.index',['status'=>'STATUS_PAY'])}}">已打款提现
                </a>
            </li>
            <li class="{{ Active::query('status','STATUS_FAILED') }}"><a
                        href="{{route('admin.balance.cash.index',['status'=>'STATUS_FAILED'])}}">审核未通过
                </a>
            </li>


        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>申请人</th>
                            <th>提现金额</th>
                            <th>申请时间</th>
                            @if(request('status')!='STATUS_AUDIT')
                                <th>处理时间</th>
                            @endif
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cash as $item)
                            <tr>
                                <td>{{$item->user->nick_name}}</td>
                                <td>{{$item->amount}}</td>
                                <td>{{$item->created_at}}</td>
                                @if(request('status')!='STATUS_AUDIT')
                                    <td>{{$item->updated_at}}</td>
                                @endif
                                <td>
                                    @if(request('status')=='STATUS_WAIT_PAY')
                                        <a class="btn btn-xs btn-primary"
                                           href="{{route('admin.balance.cash.operatePay',['id'=>$item->id])}}">
                                            <i data-toggle="tooltip" data-placement="top"
                                               class="fa fa-pencil-square-o"
                                               title="操作"></i></a>
                                    @else
                                        <a class="btn btn-xs btn-primary"
                                           href="{{route('admin.balance.cash.show',['id'=>$item->id])}}">
                                            <i data-toggle="tooltip" data-placement="top"
                                               class="fa fa-pencil-square-o"
                                               title="操作"></i></a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="clearfix"></div>
                    <div class="pull-right">
                        {!! $cash->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('before-scripts-end')

@stop



