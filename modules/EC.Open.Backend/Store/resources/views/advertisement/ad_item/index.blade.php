@extends('cms::default.layout')
@section('breadcrumbs')

    <h2>推广信息列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li><a href="{!!route('ad.index')!!}">推广位列表</a></li>
        <li class="active">推广信息列表</li>
    </ol>
@endsection


@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}

@stop

@section('content')

    @if(Session::has('message'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

            <a href="{{ route('aditem.create',['ad_id'=>request('ad_id')])}}" class="btn btn-primary margin-bottom">添加推广</a>

            <a class="btn btn-primary margin-bottom" id="promote-goods-btn" data-toggle="modal"
               data-target="#goods_modal" data-backdrop="static" data-keyboard="false"
               data-url="{{route('admin.ad.promote.goods')}}">
                选择商品
            </a>

            <div class="box box-primary">

                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <!--tr-th start-->
                        <tr>
                            <th>推广名称</th>
                            <th>图片</th>
                            <th>链接</th>
                            <th>排序(越小越靠前)</th>
                            <th>操作</th>
                        </tr>
                        <!--tr-th end-->
                        @if(count($ad_items)>0)
                            @foreach ($ad_items as $item)
                                <tr>
                                    <td>{{$item->name}}</td>

                                    <td>
                                        @if(!empty($item->image))
                                        <img src="{{$item->image}}" style="max-width: 80px;max-height: 80px;"/>
                                          @else
                                            无
                                         @endif
                                    </td>

                                    <td>{{$item->link}}</td>
                                    <td>{{$item->sort}}</td>
                                    <td>
                                        <a
                                                class="btn btn-xs btn-primary"
                                                href="{{route('aditem.edit',['id'=>$item->id,'ad_id'=>$item->ad_id])}}">
                                            <i data-toggle="tooltip" data-placement="top"
                                               class="fa fa-pencil-square-o"
                                               title="编辑"></i></a>
                                        <a data-method="delete" class="btn btn-xs btn-danger"
                                           href="{{route('aditem.destroy',['id'=>$item->id])}}">
                                            <i data-toggle="tooltip" data-placement="top"
                                               class="fa fa-trash"
                                               title="删除"></i></a>
                                        <a>
                                            <i class="fa switch @if($item->status) fa-toggle-on @else fa-toggle-off @endif" title="切换状态" value = {{$item->status}} >
                                                <input type="hidden" value={{$item->id}}>
                                            </i>
                                        </a>

                                       @if(!empty($item->meta))
                                               <?php
                                                     $meta=json_decode($item->meta,true);
                                                ?>
                                                @if(isset($meta['child'])&&count($meta['child'])>0)
                                               <span class="label label-info pull-right">有子推广</span>
                                              <br>
                                                @endif
                                                 @if(isset($meta['goods'])&&count($meta['goods'])>0)
                                                 <span class="label label-info pull-right">关联商品</span>
                                                 @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
            </div>

        </div>

        <div id="goods_modal" class="modal inmodal fade" data-ad_id="{{request('ad_id')}}" ></div>
    </div>
@endsection

@section('before-scripts-end')
    <script src="//cdn.bootcss.com/vue/2.1.6/vue.js"></script>
    <script>
        $('.switch').on('click', function(){
            var value = $(this).attr('value');
            var modelId = $(this).children('input').attr('value');
            value = parseInt(value);
            modelId = parseInt(modelId);
            value = value ? 0 : 1;
            var that = $(this);
            $.post("{{route('admin.aditem.toggleStatus')}}",
                    {
                        status: value,
                        aid: modelId
                    },
                    function(res){
                        if(res.status){
                            that.toggleClass("fa-toggle-off , fa-toggle-on");
                            that.attr('value', value);
                        }
                    });

        })
    </script>
@stop





