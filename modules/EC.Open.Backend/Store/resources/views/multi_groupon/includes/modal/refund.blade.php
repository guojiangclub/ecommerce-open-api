@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    退款操作
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

        var get_data_url = '{{route('admin.promotion.multiGroupon.getRefundItemsPaginate',['groupon_id'=>$groupon_id])}}';


        function _get(url) {
            $.get(url, function (result) {
                if (result.data.status == 'goon') {
                    var current = result.data.page;
                    var total = result.data.totalPage;
                    var process = (current / total).toFixed(2);
                    /*console.log(current);
                    console.log(total);*/
                    console.log(2);
                    $('.progress-bar').css('width', process * 100 + '%');
                    $('#down').html('退款操作中，请勿进行其他操作');
                    setTimeout(function () {
                        console.log(111);
                        _get(result.data.url);
                    }, 8000);

                } else {
                    $('.progress-bar').css('width', '100%');
                    $('#down').html('退款成功');
                    setTimeout(function () {
                        swal({
                            title: "退款成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location = '{{route('admin.promotion.multiGroupon.index')}}';
                        });
                    }, 200);
                }
            });
        }

        $(function () {
            _get(get_data_url);
        });


    </script>
@stop






