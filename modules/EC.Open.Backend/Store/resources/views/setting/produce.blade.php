@extends('store-backend::dashboard')

@section ('title','商品生产状态设置')

@section('breadcrumbs')
    <h2>商品生产状态设置</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">商品生产状态设置</li>
    </ol>
@endsection

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {{--<div class="alert alert-danger">--}}
                {{----}}
            {{--</div>--}}
            <form method="post" action="{{route('admin.setting.saveProduce')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}
                <div class="form-group">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <th>状态值（key）</th>
                            <th>状态名称（value）</th>
                            <th>操作</th>
                        </tr>
                        <tbody id="produceBody">
                        <?php $i = 0; ?>
                        @foreach ($produce as $key => $value)
                            <?php $i++; ?>
                            <tr class="produceList">
                                <td>
                                    <input type="text" name="produce[{{$i}}][key]" class="form-control" value="{{$key}}">
                                </td>
                                <td>
                                    <input type="text" name="produce[{{$i}}][value]" class="form-control" value="{{$value}}">
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="deltr(this)" class="btn btn-xs btn-danger">
                                        <i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="form-group">
                    <button type="button" id="add-produce" class="btn btn-w-m btn-info">添加状态</button>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">保存设置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script id="produce-template" type="text/x-template">
        <tr class="produceList">
            <td>
                <input type="text" name="produce[{NUM}][key]" class="form-control" placeholder="状态值">
            </td>
            <td>
                <input type="text" name="produce[{NUM}][value]" class="form-control" placeholder="状态名称">
            </td>
            <td>
                <a href="javascript:;" onclick="deltr(this)" class="btn btn-xs btn-danger">
                    <i class="fa fa-times" data-toggle="tooltip" data-placement="top" title=""
                       data-original-title="删除"></i>
                </a>
            </td>
        </tr>
    </script>
@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}

    <script>
        $(function () {
            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal("保存成功!", "", "success")
                }
            });

            var produce_html = $('#produce-template').html();
            $('#add-produce').click(function() {
                var num = $('.produceList').length;

                $('#produceBody').append(produce_html.replace(/{NUM}/g, num+1));
            });


        });

        function deltr(_self){
            $(_self).parent().parent().remove();
        }
    </script>
@stop