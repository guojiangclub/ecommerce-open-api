@extends('backend-distribution::layouts.bootstrap_modal')

@section('modal_class')
    modal-md
@stop
@section('title')
    批量编辑限购商品
@stop

{!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}

@section('body')
    <div class="row">
        <form class="form-horizontal" method="post" id="base-form"
              action="{{route('admin.store.goods.limit.saveBatchGoods')}}">
            {{csrf_field()}}
            <input type="hidden" name="ids" value="{{$ids}}">
            <input type="hidden" name="type" value="{{$type}}">
            <input type="hidden" name="value" value="{{$value}}">
            <input type="hidden" name="status" value="{{$status}}">
            <div class="col-md-10">
                <div class="form-group">
                    <label class="col-sm-3 control-label">限购：</label>
                    <div class="col-sm-9">
                        <label class="control-label">
                            <input type="radio" value="1" name="activity" checked>
                            参与
                            &nbsp;&nbsp;
                            <input type="radio" value="0" name="activity">
                            不参与
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">限购数量：</label>
                    <div class="col-sm-9">
                        <label class="control-label">
                            <input type="text" name="quantity" value="" required>
                        </label>
                    </div>
                </div>
                <div class="form-group" id="two-inputs">
                    <label class="col-sm-3 control-label">限购有效期：</label>
                    <div class="col-sm-9">
                        <div class="input-group date form_datetime" id="start_at">
                            <span class="input-group-addon" style="cursor: pointer"><i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
                                    <input type="text" name="starts_at" class="form-control inline" id="date-range200" size="20" value="" placeholder="点击选择时间" readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                        <div id="date-range12-container"></div>
                    </div>

                    <div class="col-sm-9 col-sm-offset-3" style="margin-top: 10px">
                        <div class="input-group date form_datetime" id="end_at">
                            <span class="input-group-addon" style="cursor: pointer"><i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>

                            <input type="text" name="ends_at" class="form-control inline" id="date-range201" value="" placeholder="" readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

{!! Html::script(env("APP_URL").'/vendor/libs/jquery.form.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/moment.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/jquery.daterangepicker.js') !!}

@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>
    <button type="submit" class="btn btn-primary" data-toggle="form-submit" data-target="#base-form">提交</button>

    <script>
        $(document).ready(function () {
            $('#two-inputs').dateRangePicker({
                separator: ' to ',
                time: {
                    enabled: true
                },
                language: 'cn',
                format: 'YYYY-MM-DD HH:mm',
                inline: true,
                container: '#date-range12-container',
                startDate: '{{\Carbon\Carbon::now()}}',
                showShortcuts: false,
                getValue: function () {
                    if ($('#date-range200').val() && $('#date-range201').val())
                        return $('#date-range200').val() + ' to ' + $('#date-range201').val();
                    else
                        return '';
                },
                setValue: function (s, s1, s2) {
                    $('#date-range200').val(s1);
                    $('#date-range201').val(s2);
                }
            });
        });

        $(function () {
            $('#base-form').find("input").iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });
        });

        function swal_msg(msg) {
            swal({
                title: msg,
                text: "",
                type: "warning"
            });

        }

        $('#base-form').ajaxForm({
            beforeSubmit: function () {
                if ($('input[name="activity"]:checked').val() == 1) {
                    if (!$('input[name="quantity"]').val() || parseInt($('input[name="quantity"]').val()) <= 0) {
                        swal_msg('请填写商品限购数量');
                        return false;
                    }

                    var num = $('input[name="quantity"]').val();
                    if(!$.isNumeric(num)){
                        swal_msg('商品限购数量只能为数字且大于0');
                        return;
                    }

                    if (!$('input[name="starts_at"]').val()) {
                        swal_msg('请选择限购时间');
                        return false;
                    }

                    if (!$('input[name="ends_at"]').val()) {
                        swal_msg('请选择限购时间');
                        return false;
                    }
                }
            },
            success: function (result) {
                if (result.status) {
                    swal({
                        title: "设置成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location.reload();
                    });
                }

            }
        });
    </script>
@stop