@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    正在同步价格区间，请勿进行任何操作
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="row">
        <div class="progress progress-striped active">
            <div style="width: 5%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="75" role="progressbar"
                 class="progress-bar progress-bar-danger">
                <span class="sr-only">40% Complete (success)</span>
            </div>
        </div>
        <div id="down"></div>
    </div>

@stop
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}


@section('footer')

    {{--<button type="button" class="btn btn-link" data-dismiss="modal">关闭</button>--}}
    <script>


        var postUrl='{!! $url !!}';

        function _get(url) {
            $.post(url,{_token:_token}, function (result) {
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
                        location = '{{route('admin.promotion.singleDiscount.index')}}';
                    });

                }
            });
        }

        $(function () {
            _get(postUrl);
        });


    </script>
@stop






