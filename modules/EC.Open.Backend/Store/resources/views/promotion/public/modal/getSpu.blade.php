@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    @if($action == 'view')
        查看已选择商品
    @elseif($action == 'view_exclude')
        查看已排除商品
    @else
        选择商品
    @endif
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="row">
        <div class="col-md-12 clearfix">
            <div class="col-sm-3">
                <select class="form-control search_select" name="price">
                    <option value="sell_price" {{!empty(request('price')=='sell_price')?'selected ':''}}>销售价
                    </option>
                    <option value="market_price" {{!empty(request('price')=='market_price')?'selected ':''}}>吊牌价
                    </option>
                </select>
            </div>
            <div class="col-sm-9">
                <div class="col-sm-5">
                    <input type="text" name="price_begin" class="form-control col-sm-5"
                           value="{{!empty(request('price_begin'))?request('price_begin'):''}}">
                </div>

                <span class="col-sm-1" style="text-align: center">-</span>

                <div class="col-sm-5">
                    <input type="text" name="price_end" class="form-control col-sm-5"
                           value="{{!empty(request('price_end'))?request('price_end'):''}}">
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="hr-line-dashed"></div>

        <div class="col-md-12 clearfix">
            <div class="col-sm-3" style="text-align: right;">库存：</div>
            <div class="col-sm-9">
                <div class="col-sm-5"><input type="text" name="store_begin" class="form-control search_input"
                                             value="{{!empty(request('store_begin'))?request('store_begin'):''}}">
                </div>
                <span class="col-sm-1" style="text-align: center">-</span>
                <div class="col-sm-5"><input type="text" name="store_end" class="form-control search_input"
                                             value="{{!empty(request('store_end'))?request('store_end'):''}}">
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="hr-line-dashed"></div>

        <div class="col-md-12 clearfix">
            <div class="col-sm-3">
                <select class="form-control" name="field">
                    <option value="name" {{!empty(request('field')=='name')?'selected ':''}}>商品名称</option>
                    <option value="goods_no" {{!empty(request('field')=='goods_no')?'selected ':''}}>商品编码</option>
                    <option value="sku" {{!empty(request('field')=='sku')?'selected ':''}}>SKU编码</option>
                    <option value="category" {{!empty(request('field')=='category')?'selected ':''}}>商品分类</option>
                </select>
            </div>

            <div class="col-sm-7">
                <input type="text" name="value" placeholder="Search"
                       value="{{!empty(request('value'))?request('value'):''}}" class=" form-control">
            </div>
            <div class="col-sm-2">
                <button type="button" id="send" class="ladda-button btn btn-primary">搜索
                </button>

            </div>
        </div>


        <div class="clearfix"></div>
        <div class="hr-line-dashed "></div>

        <div class="panel-body">
            <h3 class="header">请选择商品：</h3>
            <div class="table-responsive" id="goodsList">
                <table class="table table-hover table-striped">
                    <thead>
                    <!--tr-th start-->
                    <tr>
                        <th>商品名称</th>
                        <th>销售价</th>
                        <th>库存</th>
                        <th>操作</th>
                    </tr>
                    <!--tr-th end-->
                    </thead>

                    <tbody class="page-goods-list">

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
                <img src="{#img#}" alt="" style="width: 30px; height: 30px"> &nbsp;
                {#name#}
            </td>
            <td>
                {#sell_price#}
            </td>
            <td>
                {#store_nums#}
            </td>
            <td>
                <button onclick="changeSelect(this, '{{$action}}')" class="btn btn-circle {#class#}"
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
    <input type="hidden" id="temp_selected_spu">
    <input type="hidden" id="temp_exclude_spu">

    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="button" onclick="sendIds('{{$action}}');" class="ladda-button btn btn-primary"> 确定
    </button>

    @include('store-backend::promotion.public.modal.script')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/common.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/jquery.http.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/page/jquery.pages.js') !!}
    <script>
        var action = '{{$action}}';
        var paraDiscount = {_token: _token};

        function getList() {

            var postUrl = '{{route('admin.promotion.getSpuData')}}';

            if (action == 'exclude' || action == 'view_exclude') {
                var selected_spu = $('#exclude_spu').val();
            } else {
                var selected_spu = $('#selected_spu').val();
            }

            $('.pages').pages({
                page: 1,
                url: postUrl,
                get: $.http.post.bind($.http),
                body: {
                    _token: _token,
                    action: action,
                    ids: paraDiscount.ids,
                    price: $("select[name=price] option:selected").val(),
                    price_begin: $("input[name=price_begin]").val(),
                    price_end: $("input[name=price_end]").val(),
                    store_begin: $("input[name=store_begin]").val(),
                    store_end: $("input[name=store_end]").val(),
                    field: $("select[name=field] option:selected").val(),
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
                $('.page-goods-list').html(html);
            });
        }

        $(document).ready(function () {

            if (action == 'exclude' || action == 'view_exclude') {
                $('#temp_exclude_spu').val($('#exclude_spu').val());
                paraDiscount.ids = $('#temp_exclude_spu').val();
            } else {
                $('#temp_selected_spu').val($('#selected_spu').val());
                paraDiscount.ids = $('#temp_selected_spu').val();

            }

            getList();
        });

        $('#send').on('click', function () {
            getList();
        });
    </script>
@stop






