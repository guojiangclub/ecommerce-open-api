
@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    选择商品
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="row">

        {!! Form::open( [ 'route' => ['admin.promotion.bundle.getSpuData'], 'method' => 'POST', 'id' => 'search_spu_from','class'=>'form-horizontal'] ) !!}
        <div class="col-md-12">
            <div class="form-group">
                <label for="exampleInputEmail1" class="col-sm-3 control-label"> 产品标题：</label>
                <div class="col-sm-7">
                    <input type="text" name="title" value="" class="form-control" placeholder="">
                </div>
                <div class="col-sm-2">
                    <button type="submit"  id="send" class="ladda-button btn btn-primary" data-style="slide-right"
                            data-toggle="form-submit" data-target="#search_spu_from">搜索
                    </button>
                </div>

            </div>
        </div>
        {!! Form::close() !!}

        <div class="clearfix"></div>
        <div class="hr-line-dashed "></div>

        <div class="panel-body">
            <h3 class="header">请选择商品：</h3>
            <div class="table-responsive" id="goodsList">

            </div><!-- /.box-body -->
            <div id="kkpager"></div>
        </div>
    </div>
@stop
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/pager/js/kkpager.min.js') !!}

@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <script>

        function getParameter(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]);
            return null;
        }

        $(document).ready(function () {
            var data = {
                ids: $('#selected_spu').val(),
                _token:_token
            };
            $.post('{{route('admin.promotion.bundle.getSpuData')}}', data, function (ret) {
                $('#goodsList').html(ret);
            });

            //搜索
            $('#search_spu_from').ajaxForm({
                success: function (result) {
                    $('#goodsList').html(result);
                }
            });

            //分页
            var totalPage = "{{$goods->lastPage()}}";
            var totalRecords = "1";
            var pageNo = getParameter('page');
            if (!pageNo) {
                pageNo = 1;
            }

            kkpager.generPageHtml({
                pno: pageNo,
                //总页码
                total: totalPage,
                //总数据条数
                totalRecords: totalRecords,
                mode: 'click',
                //点击页码的函数，这里发送ajax请求后台
                click:function(n){
                    $.post("{{route('admin.promotion.bundle.getSpuData')}}?page="+n, {_token:"{{csrf_token()}}"}, function(data){
                        $("#goodsList").html("");
                        $("#goodsList").html(data);
                    });
                    this.selectPage(n); //手动条用selectPage进行页码选中切换
                }
            });
        });
    </script>
@stop






