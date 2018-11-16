{{--@extends ('member-backend::layout')--}}

{{--@section ('title','储值管理')--}}


{{--@section ('breadcrumbs')--}}
    {{--<h2>储值管理</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
    {{--</ol>--}}
{{--@stop--}}



{{--@section('content')--}}
    <div class="tabs-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 储值规则列表</a></li>
            <a href="{{route('admin.users.recharge.create')}}" class="btn btn-w-m btn-info pull-right">添加储值规则</a>
        </ul>

            <div class="tab-content">

            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open( [ 'route' => ['admin.users.recharge.index'], 'method' => 'get', 'id' => 'recordSearch-form','class'=>'form-horizontal'] ) !!}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group col-md-12">
                                <input type="text" name="name" value="{{request('name')}}" placeholder="储值规则名称"
                                       class=" form-control"> <span
                                        class="input-group-btn">

                        </span></div>
                        </div>
                        <div class="col-md-1">
                            <div class="input-group col-md-8">
                                <button type="submit" class="btn btn-primary">搜索</button>
                            </div>
                        </div>
                    </div>


                    {!! Form::close() !!}
                    <br>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>储值规则名称</th>
                            <th>前端显示(副标题)</th>
                            <th>实付金额(元)</th>
                            <th>到账金额(元)</th>
                            <th>赠送积分</th>
                            <th>赠送优惠券</th>
                            <th>排序（越小越靠前）</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(count($lists)>0)
                              @foreach($lists as $item)
                                  <tr>
                                      <td>{{$item->id}}</td>
                                      <td>{{$item->name}}</td>
                                      <td>{{$item->title}}</td>
                                      <td>{{number_format($item->payment_amount / 100, 2, ".", "")}}</td>
                                      <td>{{number_format($item->amount / 100, 2, ".", "")}}</td>
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
                                                  <a href="{{route('admin.promotion.coupon.edit',$citem->coupon->id)}}" target="_blank"> {{$citem->coupon->title}}</a>
                                                          @if(count($coupons)<=0)
                                                              (已过期)
                                                          @elseif(!in_array($citem->coupon->id,$coupons))
                                                              (已过期)
                                                          @endif
                                                  {{$citem->num}}张<br>
                                                  @endif
                                              @endforeach
                                            @endif
                                          @endif
                                      </td>
                                      <td>{{$item->sort}}</td>

                                      <td>
                                          <a
                                                  class="btn btn-xs btn-primary"
                                                  href="{{route('admin.users.recharge.edit',['id'=>$item->id])}}">
                                              <i data-toggle="tooltip" data-placement="top"
                                                 class="fa fa-pencil-square-o"
                                                 title="编辑"></i></a>
                                          <a
                                                  class="btn btn-xs btn-primary"
                                                  target="_blank"
                                                  href="{{route('admin.users.recharge.log.index',['id'=>$item->id])}}">
                                              <i data-toggle="tooltip" data-placement="top"
                                                 class="fa fa-eye"
                                                 title="充值记录"></i></a>

                                            <span  class="btn btn-xs btn-danger del-suit" data-url="{{route('admin.users.recharge.delete',['id'=>$item->id])}}">
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

                    @if(count($lists)>0)
                    <div class="pull-left">
                        &nbsp;&nbsp;共&nbsp;{!! $lists->total() !!} 条记录
                    </div>

                    <div class="pull-right">
                        {!! $lists->appends(request()->except('page'))->render() !!}
                    </div>
                    @else

                        &nbsp;&nbsp;&nbsp;当前无数据
                    @endif

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
{{--@stop--}}


{{--@section('after-scripts-end')--}}
    <script>
        $(function () {
            $('.del-suit').on('click', function () {
                var obj = $(this);
                var body = {
                    _token: _token
                };
                swal({
                    title: "确定删除该储值规则吗？",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    cancelButtonText: "取消",
                    closeOnConfirm: false,
                }, function () {
                    var url =obj.data('url');
                    $.post(url, body,function (ret) {
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
            $.post("{{route('admin.users.recharge.toggleStatus')}}",
                    {
                        status: value,
                        aid: modelId
                    },
                    function(res){
                        if(res.status){
                            that.toggleClass("fa-toggle-off , fa-toggle-on");
                            that.attr('value', value);
                            location.reload();
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