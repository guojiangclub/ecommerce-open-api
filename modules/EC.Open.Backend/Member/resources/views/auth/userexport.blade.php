@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    导出会员信息
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}

@stop



@section('body')
    <div class="row">
        <div class="col-md-12" id="two-inputs">
            <label for="exampleInputEmail1" class="col-sm-2 control-label">会员注册时间：</label>
            <div class="col-sm-5">
                <div class="input-group date form_datetime">
                        <span class="input-group-addon" style="cursor: pointer">
                            <i class="fa fa-calendar"></i></span>

                    <input type="text" class="form-control" name="stime" id="stime" size="20" value=""
                           placeholder="点击选择时间" readonly>

                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <div id="date-range12-container"></div>
            </div>
            <div class="col-sm-5">
                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                    <input type="text" class="form-control" name="etime" id="etime" size="20" value=""
                           placeholder="" readonly>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
            </div>

        </div>

        </form>
    </div>
@stop

{!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/moment.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/jquery.daterangepicker.js') !!}

@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="button" class="btn btn-primary" data-toggle="modal-filter"
            data-target="#download_modal" data-backdrop="static" data-keyboard="false"
            id="export-user"
            data-url="{{route('admin.export.index',['toggle'=>'export-user'])}}"
            data-type="{{$type}}"
    >导出
    </button>

    <script type="text/javascript">

        $('#two-inputs').dateRangePicker(
                {
                    separator: ' to ',
                    time: {
                        enabled: true
                    },
                    showShortcuts: false,
                    language: 'cn',
                    format: 'YYYY-MM-DD HH:mm',
                    inline: true,
                    container: '#date-range12-container',
                    getValue: function () {
                        if ($('#stime').val() && $('#etime').val())
                            return $('#stime').val() + ' to ' + $('#etime').val();
                        else
                            return '';
                    },
                    setValue: function (s, s1, s2) {
                        $('#stime').val(s1);
                        $('#etime').val(s2);
                    }
                });

        $(document).on('click.modal.data-api', '[data-toggle="modal-filter"]', function (e) {
            var $this = $(this),
                    href = $this.attr('href'),
                    modalUrl = $(this).data('url');

            var url = '{{route('admin.users.getExportData')}}';
            var type = $(this).data('type');


            stime = $('#stime').val();
            etime = $('#etime').val();

            url = url + '?stime=' + stime + '&etime=' + etime + '&type=' + type;

            $(this).data('link', url);

            if (modalUrl) {
                var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
                $target.modal('show');
                $target.html('').load(modalUrl, function () {

                });
            }
        });
    </script>
@stop






