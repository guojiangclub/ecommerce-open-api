{{--@extends('store-backend::dashboard')

@section ('title','定向发券活动')


@section ('breadcrumbs')
    <h2>定向发券活动</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
    </ol>
@stop



@section('content')--}}
    <div class="tabs-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif

        <ul class="nav nav-tabs">
            <li
                    @if(request('status')==null)
                    class="active"
                    @endif
            ><a no-pjax href="{{route('admin.promotion.directional.coupon.index')}}" aria-expanded="true"> 定向发券活动列表</a></li>
            <li
                    @if(request('status')!=null)
                    class="active"
                    @endif
            ><a no-pjax
                        href="{{route('admin.promotion.directional.coupon.index',['status'=>0])}}" aria-expanded="true"> 已删除定向发券活动</a></li>
            <a no-pjax href="{{route('admin.promotion.directional.coupon.create')}}" class="btn btn-w-m btn-info pull-right">添加定向发券活动</a>
        </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">

                    <form action="" >
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="name" value="{{request('name')}}"   placeholder="活动名称"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                            <button  id="check" type="submit" class="btn btn-primary">查找</button></span>
                                <input type="hidden" name="status" value="{{request('status')}}">
                            </div>
                        </div>
                    </form>

                    <br>
                    <br>
                    <br>

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>活动名称</th>
                            <th>优惠券</th>
                            <th>目标人群</th>
                            <th>投放人数</th>
                            <th>发券进度</th>
                            <th>成功发送</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($lists)>0)
                            @foreach($lists as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        @if(isset($item->coupon)&&$item->coupon->count()>0)

                                            @if(isset($item->coupon->id))
                                                <a href="{{route('admin.promotion.coupon.edit',$item->coupon->id)}}" target="_blank">
                                                    {{$item->coupon->title}}
                                                    @if(count($coupons)<=0)
                                                    (已过期)
                                                    @elseif(!in_array($item->coupon->id,$coupons))
                                                    (已过期)
                                                    @endif
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->directional_type=='mobile')
                                            注册手机用户
                                        @elseif($item->directional_type=='custom'
                                         And empty($item->n_day_buy)
                                          And empty($item->n_day_no_buy)
                                           And empty($item->buy_num_above)
                                            And empty($item->buy_num_below)
                                             And empty($item->buy_price_above)
                                              And empty($item->buy_price_below)
                                               And empty($item->group_id)
                                         )
                                            注册用户群发
                                          @else
                                            自定义
                                        @endif
                                    </td>

                                    <td>{{$item->number}}</td>

                                    <td>
                                        @if(count($item->receive)==0)
                                          0
                                        @else
                                            {{round(count($item->receive)/$item->number,4)*100}}%
                                        @endif
                                    </td>
                                    <td>
                                        {{count($item->receive)}}
                                    </td>
                                    <td>{{$item->created_at}}</td>

                                    <td>

                                        <a  target="_blank"
                                                class="btn btn-xs btn-primary"
                                                href="{{route('admin.promotion.directional.edit',['id'=>$item->id])}}">
                                            <i data-toggle="tooltip" data-placement="top"
                                               class="fa fa-pencil-square-o"
                                               title="查看"></i></a>


                                        <a
                                                class="btn btn-xs btn-primary"
                                                target="_blank"
                                                href="{{route('admin.promotion.directional.log',$item->id)}}">
                                            <i data-toggle="tooltip" data-placement="top"
                                               class="fa fa-eye"
                                               title="发送记录"></i></a>


                                        @if(request('status')==null)
                                            <span  class="btn btn-xs btn-danger del-suit" data-url="{{route('admin.promotion.directional.delete',['id'=>$item->id])}}">
                                                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="删除"></i>
                                            </span>
                                        @endif


                                    </td>

                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    @if(count($lists)>0)
                        <div class="pull-left">
                            &nbsp;&nbsp;共&nbsp;{!! $lists->total() !!} 条记录
                        </div>
                        <div class="pull-right">
                            {!! $lists->appends(request()->except('page'))->render() !!}
                        </div>
                    @endif

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
{{--@stop


@section('after-scripts-end')--}}
    <script>
        $(function () {
            $('.del-suit').on('click', function () {
                var obj = $(this);
                swal({
                    title: "确定删除该活动吗？",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    cancelButtonText: "取消",
                    closeOnConfirm: false,
                }, function () {
                    var url =obj.data('url');
                    $.post(url, function (ret) {
                        if(!ret.status){
                            swal("删除失败!", "", "warning");
                        }else{
                            swal({
                                title: "删除成功",
                                text: "",
                                type: "success",
                                confirmButtonText: "确定"
                            },function () {
                                location.reload();
                            });
                        }
                    });
                });

            });
        });

    </script>

{{--@endsection--}}