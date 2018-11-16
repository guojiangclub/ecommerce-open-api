@extends('store-backend::dashboard')

@section('breadcrumbs')
    <h2>优惠券明细表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li ><a href="{!!route('admin.promotion.index')!!}">促销活动列表</a></li>
        <li class="active">优惠券明细表</li>
    </ol>
@endsection


@section('content')

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

        @if($coupons->count()>0)
            <div class="box-body table-responsive">
                <table class="table table-hover table-bordered">
                    <tbody>
                    <!--tr-th start-->
                    <tr>
                        <th>时间</th>
                        <th>优惠券码</th>
                        <th>领取用户</th>
                        <th>是否使用</th>
                        <th>使用时间</th>
                        {{--<th>操作</th>--}}
                    </tr>
                    <!--tr-th end-->
                    @foreach ($coupons as $coupon)
                        <tr>
                            <td>{{$coupon->created_at}}</td>
                            <td>{{$coupon->code}}</td>
                            <td>{!! ElementVip\Store\Backend\Model\User::find($coupon->user_id)->name !!}</td>
                            <td>
                                @if(empty($coupon->used_at))
                                    <label class="label label-danger">No</label>
                                @else
                                    <label class="label label-success">Yes</label>
                                @endif
                            </td>
                            <td>{{$coupon->used_at}}</td>

                    @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->

        <div class="pull-left">
            &nbsp;&nbsp;共&nbsp;{!! $coupons->total() !!} 条记录
        </div>

        <div class="pull-right">
            {!! $coupons->render() !!}
        </div>
        @else
            &nbsp;&nbsp;&nbsp;当前无数据
        @endif

        <div class="box-footer clearfix">
        </div>
    </div>

    </div>
@endsection

