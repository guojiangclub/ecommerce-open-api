{{--@extends ('store-backend::layouts.default')
@section ('title','图片管理')

@section ('breadcrumbs')
    <h2>图片管理</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active"><a href="{{route('admin.image.index')}}">图片管理</a></li>
    </ol>
@stop
@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/file-manage/css/image_index.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/file-manage/bootstrap-treeview/bootstrap-treeview.min.css') !!}
    <style type="text/css">
        #tree .list-group-item{display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;}
        #tree .list-group-item a{-webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
    </style>
{{--@stop
@section('content')--}}
    <div class="ibox float-e-margins" id="fans">
        <div class="ibox-content" style="display: block;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="el-image-head">
                        <h3>{{$category->name}}</h3>
                        <label class="i-checks">
                            <input type="checkbox" class="check-all"><i></i> 全选</label>

                        <a class="el-image-operate-all grey el-image-operate-category" data-id="{{$category->id}}"
                           data-target="#category_modal" data-backdrop="static" data-keyboard="false"
                           data-link="{{route('admin.image.editImageCategoryBatch')}}">修改分组</a>

                        <a class="el-image-operate-all grey el-delete-image">删除</a>

                        <a data-toggle="modal"
                           data-target="#category_modal" data-backdrop="static" data-keyboard="false"
                           data-url="{{route('admin.image.upload',['category_id'=>$category->id])}}" class="btn btn-primary margin-bottom pull-right">添加图片</a>
                    </div>
                </div>

                <div class="col-sm-9">
                    @foreach($categorySub as $value)
                        <div class="el-image-cate-box">
                            <a href="{{route('admin.image.index',['category_id'=>$value->id])}}"><img
                                        src="/assets/backend/file-manage/img/column.png">
                            <p>{{$value->name}}</p>
                            </a>
                        </div>
                    @endforeach

                    @foreach($imgList as $item)
                        <div class="file-box">
                            <div class="file el-image-box">
                                <span class="corner"></span>

                                <div class="image">
                                    <a href="{{$item->url}}" target="_blank" title="点击查看大图">
                                    <img alt="image" class="img-responsive" src="{{$item->url}}">
                                    </a>
                                </div>
                                <div class="file-name">
                                    <div class="el-image-title">
                                        <label class="i-checks">
                                            <input type="checkbox" class="el-check-img" value="{{$item->id}}"><i></i> {{$item->name}}</label>
                                    </div>

                                    <a data-toggle="modal"
                                       data-target="#category_modal" data-backdrop="static" data-keyboard="false"
                                       data-url="{{route('admin.image.edit_name',['id'=>$item->id])}}">改名</a>

                                    <a class="copyBtn" data-url="{{$item->remote_url}}">链接</a>

                                    <a data-toggle="modal"
                                       data-target="#category_modal" data-backdrop="static" data-keyboard="false"
                                       data-url="{{route('admin.image.editImageCategory',['id'=>$item->id])}}">分组</a>

                                    <a data-id="{{$item->id}}" class="el-delete-image">删除</a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-sm-3" id="tree">

                </div>

                <div class="col-sm-12">
                    <div class="pull-right">
                        {!! $imgList->appends(request()->except('page'))->render() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="category_modal" class="modal inmodal fade"></div>

    <!-- /.row -->
{{--@stop

@section('after-scripts-end')--}}
    <style type="text/css">
        .node-tree{color:#2bc0be;}
    </style>
    {!! Html::script(env("APP_URL").'/assets/backend/file-manage/bootstrap-treeview/bootstrap-treeview.min.js') !!}
    @include('file-manage::image.script')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}

    <script>
        $(function () {
            /*copy*/
            $('.copyBtn').zclip({
                path: "{{url('assets/backend/libs/jquery.zclip/ZeroClipboard.swf')}}",
                copy: function () {
                    return $(this).data('url');
                }
            });


            $('#tree').treeview({
                data: {!! json_encode($categories) !!},
                color: "#428bca",
                showTags: !0,
                enableLinks: true,
                collapseAll: {
                    silent: true
                }
            });
        });
    </script>
{{--@stop--}}