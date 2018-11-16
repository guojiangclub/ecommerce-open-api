@extends('backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop

@section('title')
    导入商品
@stop

@section('body')
    <div class="row">
        <div class="progress progress-striped active" id="progress-box">
            <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="75" role="progressbar"
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
    <script>
        function _get(url) {
            $.get(url, function (result) {
                if (result.data.status == 'goon') {
                    var current = result.data.current_page;
                    var total = result.data.total;
                    var process = (current / total).toFixed(2);
                    $('.progress-bar').css('width', (process * 100 - 2) + '%');
                    _get(result.data.url);

                } else {
                    $('.progress-bar').css('width', '98%');
                    setTimeout(function () {
                        swal({
                            title: "导入成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location = '{{route('admin.goods.index')}}';
                        });
                    }, 200);
                }
            });
        }

        $(function () {
            var  calculateUrl = '{!! $calculateUrl !!}';
            $.get(calculateUrl, function (result) {
                if (result.data.status == 'goon') {
                    var importUrl = result.data.url;
                    _get(importUrl);
                }
            });
        });

    </script>
@stop






