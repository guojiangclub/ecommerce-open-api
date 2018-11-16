{{--@extends('store-backend::dashboard')

@section ('title','套餐列表')

@section('breadcrumbs')
    <h2>套餐列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active"><a href="{{route('admin.promotion.suit.index')}}">套餐列表</a></li>
    </ol>
@endsection


@section('content')--}}

    @if(Session::has('message'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif


    <div class="tabs-container">

        <ul class="nav nav-tabs">
            <li  class="{{ Active::query('status','') }}"><a no-pjax href="{{route('admin.promotion.suit.index')}}"> 全部套餐
                    <span class="badge"></span></a></li>
            <li  class="{{ Active::query('status','nstart') }}"><a no-pjax href="{{route('admin.promotion.suit.index',['status'=>'nstart'])}}"> 未开始
                    <span class="badge"></span></a></li>
            <li  class="{{ Active::query('status','ing') }}"><a no-pjax href="{{route('admin.promotion.suit.index',['status'=>'ing'])}}"> 进行中
                    <span class="badge"></span></a></li>
            <li  class="{{ Active::query('status','end') }}"><a no-pjax href="{{route('admin.promotion.suit.index',['status'=>'end'])}}"> 已结束
                    <span class="badge"></span></a></li>
        </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">

                {!! Form::open( [ 'route' => ['admin.promotion.suit.index'], 'method' => 'get', 'id' => 'discount-form','class'=>'form-horizontal'] ) !!}
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a no-pjax class="btn btn-primary " href="{{ route('admin.promotion.suit.create') }}">创建套餐</a>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="title" value="{{request('title')}}"   placeholder="套餐名称"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                        </div>

                    </div>

                    {!! Form::close() !!}

                    <div class="hr-line-dashed"></div>

                    <div class="table-responsive">

                            <table class="table table-hover table-striped">
                                <tbody>
                                <!--tr-th start-->
                                <tr>
                                    <th>ID</th>
                                    <th>套餐名称</th>
                                    <th>原总价</th>
                                    <th>套餐组现总价</th>
                                    <th>优惠金额</th>
                                    <th>有效期</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                <!--tr-th end-->
                                    @if(count($suits)>0)
                                     @foreach($suits as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->title}}</td>
                                        <td>
                                            {{$item->origin_total}}
                                        </td>
                                        <td>
                                            {{$item->total}}
                                        </td>
                                        <td>
                                            {{number_format($item->origin_total-$item->total,2,".","")}}
                                        </td>
                                        <td>{{$item->starts_at}}至<br>{{$item->ends_at}} </td>
                                        <td>{{$item->created_at}}</td>
                                        <td>
                                            <a no-pjax
                                                    class="btn btn-xs btn-primary"
                                                    href="{{route('admin.promotion.suit.edit',['id'=>$item->id])}}">
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-pencil-square-o"
                                                   title="编辑"></i></a>

                                            <a  no-pjax href="{{route('admin.promotion.suit.ShowItem',['id'=>$item->id])}}"
                                                class="btn btn-xs btn-success">
                                                <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="查看套餐商品"></i>
                                            </a>

                                            <span  class="btn btn-xs btn-danger del-suit" data-url="{{route('admin.promotion.suit.delete',['id'=>$item->id])}}">
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

                                @if(count($suits)>0)
                                <tfoot>
                                <tr>
                                    <td colspan="6" class="footable-visible">
                                        {!! $suits->render() !!}
                                    </td>
                                </tr>
                                </tfoot>
                                @endif
                            </table>

                             @if(count($suits)==0)
                             <div>
                                &nbsp;&nbsp;&nbsp;当前无数据
                             </div>
                             @endif
                    </div>
                    </div>
            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade"></div>


{{--@endsection

@section('after-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
    <script>
        $('.copyBtn').zclip({
            path: "{{url('assets/backend/libs/jquery.zclip/ZeroClipboard.swf')}}",
            copy: function(){
                return $(this).prev().val();
            }
        });
    </script>

    <script>
        $(function () {
            $('.del-suit').on('click', function () {
                var obj = $(this);
                swal({
                    title: "确定删除该套餐吗？",
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
                            swal("该套餐下存在商品,不能删除!", "", "warning");
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
            $.post("{{route('admin.suit.toggle.suit.status')}}",
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










