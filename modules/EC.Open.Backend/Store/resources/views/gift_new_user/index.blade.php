{{--@extends('store-backend::dashboard')

@section ('title','新人进店礼')


@section ('breadcrumbs')
    <h2>新人进店礼</h2>
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
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 新人进店礼列表</a></li>
            <a no-pjax href="{{route('admin.promotion.gift.new.user.create')}}" class="btn btn-w-m btn-info pull-right">添加新人进店礼</a>
        </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>活动名称</th>
                            <th>前端显示(标题)</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>赠送积分</th>
                            <th>赠送优惠券</th>
                            <th>活动状态</th>
                            <th>操作</th>
                            <th>启动</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($lists)>0)
                            @foreach($lists as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->starts_at}}</td>
                                    <td>{{$item->ends_at}}</td>
                                    <td>
                                        @if($item->open_point&&$item->point>0)
                                            赠积分:{{$item->point}}
                                        @else
                                            否
                                        @endif
                                    </td>
                                    <td>
                                        {{$item->open_coupon?'':'否'}}
                                        @if($item->open_coupon)
                                            @if(isset($item->gift)&&count($item->gift)>0)
                                                @foreach($item->gift as $ck=>$citem)
                                                    @if(isset($citem->coupon->id))
                                                        <a href="{{route('admin.promotion.coupon.edit',$citem->coupon->id)}}" target="_blank">
                                                            {{$citem->coupon->title}}
                                                            @if(count($coupons)<=0)
                                                                (已过期)
                                                            @elseif(!in_array($citem->coupon->id,$coupons))
                                                                (已过期)
                                                            @endif
                                                        </a>
                                                        {{$citem->num}}张<br>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{$item->status_text_new_user}}</td>

                                    <td>
                                        <a
                                                class="btn btn-xs btn-primary"
                                                href="{{route('admin.promotion.gift.new.user.edit',['id'=>$item->id])}}">
                                            <i data-toggle="tooltip" data-placement="top"
                                               class="fa fa-pencil-square-o"
                                               title="编辑"></i></a>

                                            <span  class="btn btn-xs btn-danger del-suit" data-url="{{route('admin.promotion.gift.new.user.delete',['id'=>$item->id])}}">
                                                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="删除"></i>
                                            </span>

                                    </td>
                                    <td>
                                        <a>
                                            <i class="fa switch @if($item->status) fa-toggle-on @else fa-toggle-off @endif" title="切换状态" value = {{$item->status}}  data-date="{{$item->starts_at}}"   >
                                                <input type="hidden" value={{$item->id}}>
                                            </i>
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    @if(count($lists)>0)
                        <div class="pull-lift">
                            {!! $lists->render() !!}
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
                    title: "确定删除该新人进店礼吗？",
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

        $('.switch').on('click', function(){
            var value = $(this).attr('value');
            var modelId = $(this).children('input').attr('value');
            value = parseInt(value);
            modelId = parseInt(modelId);
            value = value ? 0 : 1;
            var that = $(this);
            var date=$(this).data('date');

            console.log($(this));
            $.post("{{route('admin.promotion.gift.new.user.toggleStatus')}}",
                    {
                        status: value,
                        aid: modelId,
                        date:date
                    },
                    function(res){
                        if(res.status){
                            that.toggleClass("fa-toggle-off , fa-toggle-on");
                            that.attr('value', value);
                            location.reload();
                        }else{
                            swal({
                                title: "操作失败",
                                text:res.message,
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