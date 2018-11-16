{{--@extends('store-backend::dashboard')

@section ('title','售后设置')

@section('breadcrumbs')
    <h2>商城设置</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i>首页</a></li>
        <li class="active">售后设置</li>
    </ol>
@endsection

@section('content')--}}
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <form method="post" action="{{route('admin.setting.saveRefundSettings')}}" class="form-horizontal"
                  id="setting_site_form">
                {{csrf_field()}}

                <div class="form-group">
                    <label class="col-sm-2 control-label">确认收货后的N天内可以申请售后：</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control valid-num"
                               value="{{settings('order_can_refund_day')?settings('order_can_refund_day'):7}}"
                               name="order_can_refund_day" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">N天自动同意售后申请
                        <i class="fa fa-question-circle"
                           data-toggle="tooltip" data-placement="top"
                           data-original-title="管理员N天未处理售后自动同意售后申请"></i>：</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control valid-num"
                               value="{{settings('refund_auto_processing')?settings('refund_auto_processing'):5}}"
                               name="refund_auto_processing" placeholder="">
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">退换货理由：</label>

                    <div class="col-sm-10">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>key（请输入不同的值）</th>
                                <th>理由</th>
                                <th>是否启用</th>
                            </tr>
                            </thead>

                            <tbody id="refundBody">
                            @if($reason = settings('order_refund_reason'))
                                @foreach ($reason as $key => $value)

                                    <tr class="refundList">
                                        <td>
                                            <input readonly type="text" name="order_refund_reason[{{$key}}][key]"
                                                   class="form-control" value="{{$value['key']}}">
                                        </td>
                                        <td>
                                            <input type="text" name="order_refund_reason[{{$key}}][value]"
                                                   class="form-control"
                                                   value="{{$value['value']}}">
                                        </td>
                                        <td>
                                            <input type="radio" value="1"
                                                   name="order_refund_reason[{{$key}}][is_enabled]" {{$value['is_enabled'] == 1 ? 'checked': ''}}>
                                            是
                                            &nbsp;&nbsp;
                                            <input type="radio" value="0"
                                                   name="order_refund_reason[{{$key}}][is_enabled]" {{$value['is_enabled'] == 0 ? 'checked': ''}}>
                                            否
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3">
                                    <button type="button" id="add-refund" class="btn btn-w-m btn-info">添加理由</button>
                                </td>
                            </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">用户退货说明：</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                               value="{{settings('refund_note')}}"
                               name="refund_note" placeholder="">
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


    <script id="refund-template" type="text/x-template">
        <tr class="refundList">
            <td>
                <input type="text" name="order_refund_reason[{NUM}][key]" class="form-control" placeholder="key">
            </td>
            <td>
                <input type="text" name="order_refund_reason[{NUM}][value]" class="form-control" placeholder="理由">
            </td>
            <td>
                <input type="radio" value="1" name="order_refund_reason[{NUM}][is_enabled]" checked> 是 &nbsp;&nbsp;
                <input type="radio" value="0" name="order_refund_reason[{NUM}][is_enabled]"> 否 &nbsp;&nbsp;
                <a href="javascript:;" onclick="delete_reason($(this));"
                   class="btn btn-xs btn-danger operator">
                    <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" data-original-title="删除"></i>
                </a>
            </td>
        </tr>
    </script>
{{--@endsection

@section('after-scripts-end')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}

    <script>
        $('.valid-num').bind('input propertychange', function (e) {
            var value = $(e.target).val()
            if (!/^[-]?[0-9]*\.?[0-9]+(eE?[0-9]+)?$/.test(value)) {
                value = value.replace(/[^\d.].*$/, '');
                $(e.target).val(value);
            } else if (value.indexOf('-') != -1) {
                $(e.target).val('');
            }
        });


        $(function () {
            var refund_html = $('#refund-template').html();
            $('#add-refund').click(function () {
                var num = $('.refundList').length;
                $('#refundBody').append(refund_html.replace(/{NUM}/g, num));

                $('#refundBody').find("input[type='radio']").iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                    increaseArea: '20%'
                });
            });


            $('#setting_site_form').ajaxForm({
                success: function (result) {
                    if (result.status) {
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location.reload();
                        });
                    } else {
                        swal(result.message, '', 'error');
                    }

                }
            });
        });

        function delete_reason(_self) {
            _self.parent().parent().remove();
        }

    </script>
{{--@stop--}}