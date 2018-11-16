
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

        {!! Form::open( [ 'route' => ['admin.promotion.getSpuData'], 'method' => 'POST', 'id' => 'search_spu_from','class'=>'form-horizontal'] ) !!}
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

    <button type="button" onclick="sendIds();"  class="ladda-button btn btn-primary" > 确定
    </button>

    <script>

        function changeSelect(_self) {
            if($(_self).hasClass('select')){
                var btnVal= $(_self).data('id');
                var str = $('#selectPid').val() + ',' + btnVal;
                if (str.substr(0,1)==',') str=str.substr(1);

                $(_self).removeClass('select btn-info').addClass('btn-warning unselect').find('i').removeClass('fa-check').addClass('fa-times');

                $('#selectPid').val(str);
            }else{
                var btnVal= $(_self).data('id');
                var string = $('#selectPid').val();
                var ids = string.split(',');

                if($.inArray(btnVal, ids)) ids.splice($.inArray(btnVal,ids),1);

                var str = ids.join(',');

                $(_self).addClass('select btn-info').removeClass('btn-warning unselect').find('i').addClass('fa-check').removeClass('fa-times');

                $('#selectPid').val(str);

            }
        }

        function sendIds() {
            var string = $('#selectPid').val();
            $('#selected_spu').val(string);

            if(string){
                var count = string.split(',').length;
            }else{
                count = 0
            }

            $('.countSpu').html(count);


            $('#spu_modal').modal('hide');
        }

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
            $.post('{{route('admin.promotion.getSpuData')}}', data, function (ret) {
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
                    $.post("{{route('admin.promotion.getSpuData')}}?page="+n, {_token:"{{csrf_token()}}"}, function(data){
                        $("#goodsList").html("");
                        $("#goodsList").html(data);
                    });
                    this.selectPage(n); //手动条用selectPage进行页码选中切换
                }
            });
        });
    </script>
@stop






