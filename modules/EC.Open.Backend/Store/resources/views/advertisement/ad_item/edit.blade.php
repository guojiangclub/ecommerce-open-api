@extends ('cms::default.layout')
@section ('title','推广管理')

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}
@stop

@section ('breadcrumbs')
    <h2>添加推广</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="">{!! link_to_route('ad.index', '推广位列表') !!}</li>
        <li class="active">添加推广</li>
    </ol>
@stop

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('aditem.store')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
            <input type="hidden" name="ad_id" value="{{$ad_id}}">
            <input type="hidden" name="id" value="{{$aditem_list->id}}">

            <div class="form-group">
                {!! Form::label('name','推广名称：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="name" placeholder="" value="{{$aditem_list->name}}">
                </div>
            </div>


            @if(!empty($aditem_list->meta))
                <?php
                $meta = json_decode($aditem_list->meta, true);
                ?>
                @if(isset($meta['goods'])&&count($meta['goods'])>0)
                    @if(isset($meta['goods']['name']))
                        <div class="form-group">
                            {!! Form::label('name','关联商品：', ['class' => 'col-lg-2 control-label']) !!}
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="" disabled placeholder=""
                                       value="{{$meta['goods']['name']}}">
                            </div>
                        </div>
                    @endif
                @endif
            @endif

            <div class="form-group">
                {!! Form::label('name','推广展示图片：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="hidden" name="image" value="{{$aditem_list->image}}"/>
                    <img class="banner-image" style="max-height: 186px;" src="{{$aditem_list->image}}">
                    <div id="filePicker">选择图片</div>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('name','链接地址：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="link" placeholder="" value="{{$aditem_list->link}}">
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','排序：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="sort" placeholder="" value="{{$aditem_list->sort}}">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-lg-2 control-label">添加子推广：</label>

                <div class="col-sm-2 {{$aditem_list->type=='goods'?'hidden':''}}">
                    <a href="{{route('admin.ad.child.create',['ad_id'=>request('ad_id'),'pid'=>$aditem_list->id])}}"
                       class="btn btn-primary pull-left">添加子推广</a>
                </div>
                <div class="col-sm-2 {{$aditem_list->type=='image'?'hidden':''}}">
                    <a class="btn btn-primary margin-bottom" id="promote-goods-btn" data-toggle="modal"
                       data-target="#goods_modal" data-backdrop="static" data-keyboard="false"
                       data-url="{{route('admin.ad.promote.goods',['pid'=>$aditem_list->id])}}">
                        选择商品
                    </a>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8 controls">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>
            {!! Form::close() !!}


            @if(count($child)>0)
                <div class="box-body table-responsive clearfix">
                    <div class="pull-left col-lg-2">

                    </div>
                    <div class="pull-left col-lg-9">
                        <table class="table table-hover table-bordered ">
                            <tbody>
                            <!--tr-th start-->
                            <tr>
                                <th>子推广名称</th>
                                <th>图片</th>
                                <th>链接</th>
                                <th>排序</th>
                                <th>操作子推广</th>
                            </tr>
                            <!--tr-th end-->
                            @if(count($child)>0)
                                @foreach ($child as $item)
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
                                                <i class="fa switch @if($item->status) fa-toggle-on @else fa-toggle-off @endif"
                                                   title="切换状态" value= {{$item->status}} >
                                                    <input type="hidden" value={{$item->id}}>
                                                </i>
                                            </a>


                                            @if(!empty($item->meta))
                                                <?php
                                                $meta = json_decode($item->meta, true);
                                                ?>
                                                @if(isset($meta['child'])&&count($meta['child'])>0)
                                                    <span class="label label-info pull-right">有子推广</span>
                                                    <br>
                                                @endif
                                                @if(isset($meta['goods'])&&count($meta['goods'])>0)
                                                    <span class="label label-primary pull-right">关联商品</span>
                                                @endif
                                            @endif

                                        </td>
                                    </tr>

                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif


                        <!-- /.tab-content -->
        </div>


        <div id="goods_modal" class="modal inmodal fade" data-ad_id="{{request('ad_id')}}"
             data-pid="{{$aditem_list->id}}"></div>

    </div>
@endsection

@section('before-scripts-end')
    <script src="//cdn.bootcss.com/vue/2.1.6/vue.js"></script>
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
    @include('store-backend::advertisement.ad_item.script')
    <script>
        $('.switch').on('click', function () {
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
                    function (res) {
                        if (res.status) {
                            that.toggleClass("fa-toggle-off , fa-toggle-on");
                            that.attr('value', value);
                        }
                    });

        })
    </script>
@stop