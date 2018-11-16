{{--@extends('store-backend::dashboard')

@section ('title','发票设置')

@section('breadcrumbs')
    <h2>商城设置</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">发票设置</li>
    </ol>
@endsection

@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.saveInvoiceSettings')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}

                <div class="form-group">
                    <label class="col-sm-2 control-label">是否启用发票：</label>

                    <div class="col-sm-10">
                        <label class="control-label">
                            <input type="radio" value="1"
                                   name="invoice_status" {{settings('invoice_status') ? 'checked': ''}}> 是
                            &nbsp;&nbsp;
                            <input type="radio" value="0"
                                   name="invoice_status" {{!settings('invoice_status') ? 'checked': ''}}> 否
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">发票类型设置：</label>

                    <div class="col-sm-10">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>类型</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody id="typeBody">
                            @if($type = settings('invoice_type'))
                                @foreach ($type as $key => $value)

                                    <tr class="typeList">
                                        <td>
                                            <input readonly type="text" name="invoice_type[{{$key}}]"
                                                   class="form-control" value="{{$value}}">
                                        </td>
                                        <td>
                                            <a class="btn btn-xs btn-danger del-column" href="javascript:;"
                                               onclick="delColumn(this)">
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-trash"
                                                   title="删除"></i></a>

                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3">
                                    <button type="button" id="add-type" class="btn btn-w-m btn-info">添加类型</button>
                                </td>
                            </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">发票内容设置：</label>

                    <div class="col-sm-10">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>内容</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody id="contentBody">
                            @if($content = settings('invoice_content'))
                                @foreach ($content as $key => $value)

                                    <tr class="contentList">
                                        <td>
                                            <input readonly type="text" name="invoice_content[{{$key}}]"
                                                   class="form-control" value="{{$value}}">
                                        </td>
                                        <td>
                                            <a class="btn btn-xs btn-danger del-column" href="javascript:;"
                                               onclick="delColumn(this)">
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-trash"
                                                   title="删除"></i></a>

                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3">
                                    <button type="button" id="add-content" class="btn btn-w-m btn-info">添加内容</button>
                                </td>
                            </tr>

                            </tfoot>
                        </table>
                    </div>
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


    <script id="type-template" type="text/x-template">
        <tr class="typeList">
            <td>
                <input type="text" name="invoice_type[{NUM}]" class="form-control" placeholder="请输入类型名称">
            </td>
            <td>
                <a class="btn btn-xs btn-danger del-column" href="javascript:;" onclick="delColumn(this)">
                    <i data-toggle="tooltip" data-placement="top"
                       class="fa fa-trash"
                       title="删除"></i></a>
            </td>
        </tr>
    </script>

    <script id="content-template" type="text/x-template">
        <tr class="contentList">
            <td>
                <input type="text" name="invoice_content[{NUM}]" class="form-control" placeholder="请输入发票内容">
            </td>
            <td>
                <a class="btn btn-xs btn-danger del-column" href="javascript:;" onclick="delColumn(this)">
                    <i data-toggle="tooltip" data-placement="top"
                       class="fa fa-trash"
                       title="删除"></i></a>
            </td>
        </tr>
    </script>
{{--@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}

    <script>
        function delColumn(_self) {
            $(_self).parent().parent().remove();
        }

        $(function () {
            var type_html = $('#type-template').html();
            $('#add-type').click(function () {
                var num = $('.typeList').length;
                $('#typeBody').append(type_html.replace(/{NUM}/g, num));
            });

            var content_html = $('#content-template').html();
            $('#add-content').click(function () {
                var num = $('.contentList').length;
                $('#contentBody').append(content_html.replace(/{NUM}/g, num));
            });


            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location.reload();
                    });

                }
            });


        })
    </script>
{{--@stop--}}