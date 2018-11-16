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
<script>
    var getData={
    };
    var getDataChild={
    };
</script>

@section('body')
    <div class="row">

        {!! Form::open( [ 'route' => ['admin.ad.promote.goods.data'], 'method' => 'POST', 'id' => 'search_spu_from','class'=>'form-horizontal'] ) !!}
        <div class="col-md-12">
            <div class="form-group">
                <label for="exampleInputEmail1" class="col-sm-3 control-label"> 产品标题:</label>
                <div class="col-sm-7">
                    <input type="text" name="title" value="" class="form-control" placeholder="">
                </div>
                <div class="col-sm-2">
                    <button type="submit"  id="send" class="ladda-button btn btn-primary" data-style="slide-right"
                            data-toggle="form-submit" data-target="#search_spu_from">搜索
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1" class="col-sm-3 control-label">
                    {{--<input id="adcheckbox"  type="checkbox" checked>--}}
                    关联商品推广链接</label>
                <div id="showad">
                    <div class="col-sm-7">
                        <input type="text" name="key" class="form-control" placeholder="例如http://dev.dmpapi.com/store/detail/###,其中商品ID请用###替换" value="{{$link}}">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit"  id="setshowad" class="ladda-button btn btn-primary" data-style="slide-right"
                                data-toggle="form-submit" >设置
                        </button>
                    </div>
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
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>
    <button type="button" onclick="sendIds();"  class="ladda-button btn btn-primary" > 确定
    <script>
//        $('#adcheckbox').change(function () {
//            if($('#adcheckbox').is(':checked')){
//                $('#showad').show();
//            }else{
//                $('#showad').hide();
//            }
//        })

        $('#setshowad').on('click',function(){
            var value=$('input[name=key]').val();

            if(value==''){
                return false;
            }else{
                var Cts = value;
                if(Cts.indexOf("###") <= 0 ){
                    swal({
                        title: '格式不正确',
                        text: "",
                        type: "error"
                    });
                    return false;
                }
            }

            var data = {
                value: value,
                _token:_token
            }

            $.post('{{route('admin.ad.settings')}}',data,function(data){
                if(data){
                    swal({
                        title:'设置成功',
                        text: "",
                        type: "success"
                    });
                }
            })
        })




        function sendIds() {
            var url="{{route('admin.ad.promote.goods.add')}}";
            var data=[];
            var ad_id= $('#goods_modal').data('ad_id');
            var pid= $('#goods_modal').data('pid');

            var newdata = {
                ad_id:ad_id,
                _token:_token
            };

            if (typeof(pid) !== "undefined") {
                 newdata.pid=pid;
                for(var i in getDataChild){
                   data.push(getDataChild[i]);
                }
                newdata.ids=data;

            }else{
                for(var i in getData){
                    data.push(getData[i]);
                }
                newdata.ids=data;
            }



            $.post(url,newdata,function (res) {
                console.log(res);
                if(res.status){
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        $('#goods_modal').hide();
//                        location = '/admin/cms/aditem?ad_id='+res.ad_id;
                        location=document.URL;
                    });
                }
            })
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
            $.post('{{route('admin.ad.promote.goods.data')}}', data, function (ret) {
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
                    var title=$('input[name=title]').val();
                    var data={
                        _token:"{{csrf_token()}}",
                    };
                    if(title){
                        data.title=title;
                    }
                    $.post("{{route('admin.ad.promote.goods.data')}}?page="+n,data, function(data){
                        $("#goodsList").html("");
                        $("#goodsList").html(data);
                    });
                    this.selectPage(n); //手动条用selectPage进行页码选中切换
                }
            });
        });
    </script>
@stop






