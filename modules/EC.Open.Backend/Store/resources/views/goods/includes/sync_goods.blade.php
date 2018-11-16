@extends('backend-distribution::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    同步商品
@stop

{!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}

@section('body')
    <div class="row">
        <form class="form-horizontal">
            <div class="col-md-10">
                <div class="form-group">
                    <label class="col-sm-3 control-label">限购：</label>
                    <div class="col-sm-9">
                        <label class="control-label">
                            <input type="radio" value="1" name="activity">
                            参与
                            &nbsp;&nbsp;
                            <input type="radio" value="0" name="activity" checked>
                            不参与
                        </label>
                    </div>
                    <div class="form-group" id="quantity_input" style="display: none">
                        <label class="col-sm-3 control-label">限购数量：</label>
                        <div class="col-sm-9">
                            <label class="control-label">
                                <input type="text" name="quantity" value="" required>
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="two-inputs" style="display: none">
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
                <div class="alert alert-danger">
                    以上设置只对第一次同步商品生效
                </div>
            </div>
        </form>
    </div>

    <div class="row" id="progress" style="display: none;">
        <div class="progress progress-striped active">
            <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="75" role="progressbar"
                 class="progress-bar progress-bar-danger">
                <span class="sr-only">40% Complete (success)</span>
            </div>
        </div>
        <div id="message"></div>
    </div>
@stop

{!! Html::script(env("APP_URL").'/vendor/libs/jquery.form.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/moment.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/jquery.daterangepicker.js') !!}

@section('footer')
    <button type="button" class="btn btn-primary" id="sync-goods">开始同步</button>

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


        var syncUrl = '{{route('admin.store.goods.limit.postSyncGoods')}}';
        function _get(url) {
            $.post(url, {
                activity: $('input[name="activity"]:checked').val(),
                quantity: $('input[name="quantity"]').val(),
                starts_at: $('input[name="starts_at"]').val(),
                ends_at: $('input[name="ends_at"]').val(),
                _token: _token
            }, function (result) {
                if (result.data.status == 'goon') {
                    var current = result.data.current_page;
                    var total = result.data.total;
                    var process = (current / total).toFixed(2);
                    $('.progress-bar').css('width', (process * 100 - 2) + '%');
                    _get(result.data.url);

                } else {
                    $('.progress-bar').css('width', '100%');
                    swal({
                        title: "同步成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = '{{route('admin.store.goods.limit', ['status'=>'ACTIVITY'])}}';
                    });
                }
            });
        }

        function swal_msg(msg) {
            swal({
                title: msg,
                text: "",
                type: "warning"
            });

        }

        $(document).ready(function(){
            $('[name="activity"]').change(function(){
                if($(this).val()==1){
                    $('#quantity_input').show();
                    $('#two-inputs').show();
                } else {
                    $('#quantity_input').hide();
                    $('#two-inputs').hide();
                }
            });
        });

        $(function () {
            $('#sync-goods').on('click', function () {
                if ($('input[name="activity"]:checked').val() == 1) {
                    if (!$('input[name="quantity"]').val() || parseInt($('input[name="quantity"]').val()) <= 0) {
                        swal_msg('请填写商品限购数量');
                        return;
                    }

                    var num = $('input[name="quantity"]').val();
                    if(!$.isNumeric(num)){
                        swal_msg('商品限购数量只能为数字且大于0');
                        return;
                    }

                    if (!$('input[name="starts_at"]').val()) {
                        swal_msg('请选择限购时间');
                        return;
                    }

                    if (!$('input[name="ends_at"]').val()) {
                        swal_msg('请选择限购时间');
                        return;
                    }
                }

                $('#progress').show();
                $('input[name="activity"]').attr("disabled", true);
                $(this).text('正在同步...');
                $(this).attr("disabled", true);

                _get(syncUrl);
            });
        });
    </script>
@stop






