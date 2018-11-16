@extends('store-backend::layouts.bootstrap_modal')
@section('modal_class')
    modal-lg
@stop

@section('title')
      筛选用户
@stop

@section('body')
    <div class="row">
        <div class="col-md-12 clearfix">

            <div class="col-sm-7">
                <input type="text" name="value" placeholder="Search"
                       value="{{!empty(request('value'))?request('value'):''}}" class=" form-control">
            </div>            <div class="col-sm-2">


                <button type="button"  id="go-search" class="ladda-button btn btn-primary" >搜索
                </button>

                <input type="hidden" id="temp_select_users">
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="hr-line-dashed "></div>

        <div class="panel-body">
            <h3 class="header">请选择用户：</h3>
            <div class="table-responsive" id="goodsList">
                <table class="table table-hover table-striped">
                    <thead>
                    <!--tr-th start-->
                    <tr>
                        <th>用户名</th>
                        <th>手机</th>
                        <th>昵称</th>
                        <th>操作</th>
                    </tr>
                    <!--tr-th end-->
                    </thead>

                    <tbody class="page-users-list">

                    </tbody>
                </table>
            </div><!-- /.box-body -->

            <div class="pages">

            </div>
        </div>
    </div>


    <script type="text/html" id="page-temp">
        <tr>
            <td>
                {#name#}
            </td>
            <td>
                {#mobile#}
            </td>
            <td>
                {#nick_name#}
            </td>
            <td>
                <button onclick="changeSelect(this)" class="btn btn-circle {#class#}"
                        type="button" data-id="{#id#}"><i class="fa fa-{#icon#}"></i>
                </button>
            </td>
        </tr>
    </script>
@stop
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}


@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="button" onclick="sendIds();"  class="ladda-button btn btn-primary" > 确定
    </button>

    @include('store-backend::promotion.coupon.includes.script')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/common.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/jquery.http.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/page/jquery.pages.js') !!}
    <script>
        var postUrl = '{{route('admin.promotion.coupon.getUsers')}}';
        var paraDiscount = {_token: _token};

        function getList() {
            $('.pages').pages({
                page: 1,
                url: postUrl,
                get: $.http.post.bind($.http),
                body: {
                    _token: _token,
                    ids: paraDiscount.ids,
                    value: $("input[name=value]").val()
                },
                marks: {
                    total: 'data.last_page',
                    index: 'data.current_page',
                    data: 'data'
                }
            }, function (data) {
                var html = '';
                var ids = data.ids;

                data.data.forEach(function (item) {
                    if (!~ids.indexOf(String(item.id))) {
                        item.class = 'btn-warning unselect';
                        item.icon = 'times';

                    } else {
                        item.class = 'btn-info select';
                        item.icon = 'check';
                    }

                    html += $.convertTemplate('#page-temp', item, '');
                });
                $('.page-users-list').html(html);
            });
        }

        $(document).ready(function () {
//            getList();
            $('#temp_select_users').val($('#select_users').val());
            paraDiscount.ids = $('#temp_select_users').val();
        });

        $('#go-search').on('click',function () {
            getList();
        });
    </script>
@stop

















