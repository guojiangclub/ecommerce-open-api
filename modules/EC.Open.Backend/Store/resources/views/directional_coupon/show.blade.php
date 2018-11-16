{{--@extends('store-backend::dashboard')--}}

{{--@section('breadcrumbs')--}}
    {{--<h2>定向发券明细表</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href=""><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li><a href="{{route('admin.promotion.directional.coupon.index')}}">定向发券活动</a></li>--}}
        {{--<li class="active">定向发券明细表</li>--}}
    {{--</ol>--}}
{{--@endsection--}}


{{--@section('content')--}}

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

                <div class="box-body table-responsive">

                    <form action=""  class="pull-right" >
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="mobile" value="{{request('mobile')}}"   placeholder="请输入用户手机号"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                            <button  id="check" type="submit" class="btn btn-primary">查找</button></span>

                            </div>
                        </div>
                    </form>

                    <table class="table table-hover table-bordered">
                        <tbody>
                        <!--tr-th start-->
                        <tr>
                            <th>时间</th>
                            <th>优惠券码</th>
                            <th>领取用户</th>
                            <th>手机</th>
                            <th>是否使用</th>
                            <th>使用时间</th>
                            {{--<th>操作</th>--}}
                        </tr>
                        <!--tr-th end-->
                        @if($coupons->count()>0)
                        @foreach ($coupons as $coupon)
                            <tr>
                                <td>{{$coupon->created_at}}</td>
                                <td>{{$coupon->coupon->code}}</td>
                                <td>{{$coupon->user->name}}</td>
                                <td>{{$coupon->user->mobile}}</td>
                                <td>
                                    @if(empty($coupon->coupon->used_at))
                                        <label class="label label-danger">No</label>
                                    @else
                                        <label class="label label-success">Yes</label>
                                    @endif
                                </td>
                                <td>{{$coupon->coupon->used_at}}</td>

                        @endforeach
                     @endif
                        </tbody>
                    </table>

                </div><!-- /.box-body -->

                <div class="pull-left">
                    &nbsp;&nbsp;共&nbsp;{!! $coupons->total() !!} 条记录
                </div>

                <div class="pull-right">
                    {!! $coupons->render() !!}
                </div>


            <div class="box-footer clearfix">
            </div>
        </div>

    </div>
{{--@endsection--}}

