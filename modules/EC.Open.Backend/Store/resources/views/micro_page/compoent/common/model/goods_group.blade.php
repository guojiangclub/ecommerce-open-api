@extends('store-backend::micro_page.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    商品分组
    {{--{{request('index')}},{{request('goods_id')}}--}}
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop

@section('body')
    {{--{{request('index')}}{{request('goods_ids')}}--}}
    <br>
    <br>

    <div class="row">

        <div class="col-md-8" style="min-height: 100px;max-height:285px;margin-left:180px;margin-bottom:40px;background-color: #FFFFFF;max-height:305px;
    overflow: auto;">

            <div class="col-md-10" style="margin-left:40px;margin-top: 10px;">

                <input type="hidden" name="index" value="{{request('index')}}" class="form-control" placeholder=""
                       style="margin-bottom: 10px;margin-top: 10px;">

                <input type="text" name="group_name" value="{{request('title')}}" class="form-control" placeholder="分组名称"
                       style="margin-bottom: 10px;margin-top: 10px;">

                <p>*拖动可排序</p>
            </div>

            <div class="col-md-12">
                <ul id="bar2">

                    @if($goods_items)
                        @foreach($goods_items as $item)
                            @if(!empty($item))
                            <li id="goods{{$item->id}}"  class="goods-li" style="height:40px;" data-goods_id="{{$item->id}}">
                                <p class="btn btn-xs btn-danger minus" style="cursor: pointer">
                                    <i data-toggle="tooltip" data-placement="top" class="fa fa-minus" title=""></i></p>
                                <img width="30" height="30" src="{{$item->img}}" alt="">
                                <span class="price">¥{{$item->sell_price}}</span>
                                    <span class="name">{{$item->name}}</span>
                            </li>
                            @endif
                         @endforeach
                    @endif

                </ul>

            </div>


        </div>

    </div>

    <div class="row">

        <form class="form-horizontal"
              action="{{route('admin.setting.micro.page.compoent.getGoodsData',['limit'=>5,'type'=>'micro_page_componet_goods_group'])}}"
              method="get" id="search_spu_from">

            <div class="col-md-12">
                <div class="form-group">

                    <div class="col-sm-6">

                    </div>

                    <div class="col-sm-4">
                        <input type="text" name="title" value="" class="form-control" placeholder="商品名称">
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" id="send" class="ladda-button btn btn-primary" data-style="slide-right"
                                data-toggle="form-submit" data-target="#search_spu_from">搜索
                        </button>
                    </div>
                </div>

                @if($goods_)
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-6">
                            <a target="_blank" href="{{route('admin.goods.edit',$goods_->id)}}"><img
                                        src="{{$goods_->img}}" width="40" height="40"></a>
                            <a target="_blank" href="{{route('admin.goods.edit',$goods_->id)}}">{{$goods_->name}}</a>
                        </div>
                    </div>
                @else
                    @if(request('goods_id'))
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="col-sm-3 control-label"></label>
                            <div class="col-sm-6">
                                <span class="label label-default">商品ID：{{request('goods_id')}};不存在或已下架</span>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

        </form>


        <div class="panel-body">
            <div class="col-sm-2">

            </div>
            <div class="table-responsive col-sm-9" id="goodsList" data-goods_id="{{request('goods_id')}}">

            </div>


            <!-- /.box-body -->
            <div class="col-sm-1">

            </div><!-- /.box-body -->
            <div id="kkpager" style="margin-left: 180px;"></div>
        </div>
    </div>
@stop
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}


@section('footer')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    @include('store-backend::micro_page.compoent.common.kkpager')
    <button type="button" class="btn btn-link" data-dismiss="modal" onclick="cancel();">取消</button>
    <button type="button" onclick="sendIds();" class="ladda-button btn btn-primary"> 确定
        <script>

            function plus(name, img, price, id) {

                var href="{{route('admin.goods.edit',['id'=>'#'])}}";

                href = href.replace('#',id);

                var html = [" <li style=\"height:40px;\" id='goods"+id+"'"+
                " class='goods-li' data-goods_id=" + id + "  >",
                    "                        <p class=\"btn btn-xs btn-danger minus\  style=\"cursor: pointer\">",
                    "                            <i data-toggle=\"tooltip\" data-placement=\"top\"",
                    "                               class=\"fa fa-minus\"",
                    "                               title=\"\"></i></p>",
                    "                        <img width=\"30\" height=\"30\" src=" + img + " alt=\"\">",
                    "                        <span class=\"price\">¥" + price + "</span>",
                    "                            <span class=\"name\">" + name + "</span>",
                    "                    </li>"].join("");


                if($('#goods'+id).length){

                    swal("添加失败", '该商品已经添加', "error");return;
                }

                $('#bar2').append(html);

            }

            //删除
            $("body #bar2").on("click", "p", function () {
                var remove = $(this).parent();
                remove.remove();
            });

            function sendIds() {
                var group_name = $('input[name=group_name]').val();
                var index = $('input[name=index]').val();
                if (!group_name) {
                    swal("保存失败!", '分组名称不能为空', "error");return;
                }

                var ids='';

                $('body #bar2 li').each(function (v, obj) {
                    var obj = $(obj);
                    var id = obj.attr('data-goods_id');
                    if(ids.length>0){
                        ids+=','+id;
                    }else{
                        ids+=id;
                    }
                })

                if(!ids){
                    swal("保存失败!", '请添加商品', "error");return;
                }

                compoent_componet_html = compoent_html.replace('{#num#}', $('body #bar2 li').length);

                compoent_componet_html = compoent_componet_html.replace('{#title#}', group_name);

                compoent_componet_html = compoent_componet_html.replace('{#name#}', group_name);

                compoent_componet_html = compoent_componet_html.replace('{#ids#}', ids);

                compoent_componet_html = compoent_componet_html.replace('{#goods_ids#}', ids);

                compoent_componet_html = compoent_componet_html.replace('{#type#}','micro_page_componet_goods_group');

                if(index>0){
                    compoent_componet_html = compoent_componet_html.replace('{#index#}', index);
                    var obj=$('#bar .advert_li_'+index);
                    obj.after(compoent_componet_html);
                    obj.remove()
                }else{
                    compoent_componet_html = compoent_componet_html.replace('{#index#}', 0);
                     $('#bar').append(compoent_componet_html);
                }
                index_init();
                $('#goods_modal').modal('hide');

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
                    _token: _token
                };
                $.get('{{route('admin.setting.micro.page.compoent.getGoodsData',['goods_id'=>request('goods_id'),'limit'=>5])}}' + '&type=micro_page_componet_goods_group', data, function (ret) {
                    $('#goodsList').html(ret);
                });

                //搜索
                $('#search_spu_from').ajaxForm({
                        success: function (result) {
                            $('#goodsList').html(result);
                            var totalPage=$('#app').attr('data-totalPage');
                            var pageNo = getParameter('page');
                            if (!pageNo) {
                                pageNo = 1;
                            }
                            kkpager.generPageHtml({
                                pno: pageNo,
                                //总页码
                                total: totalPage,
                                //总数据条数1
                                totalRecords: 1,
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
                                    $.get("{{route('admin.setting.micro.page.compoent.getGoodsData')}}?page="+n+ '&type=micro_page_componet_goods_group',data, function(data){
                                        $("#goodsList").html("");
                                        $("#goodsList").html(data);
                                        kkpager.selectPage(n,totalPage);
                                    });

                                }
                            });

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
                    click: function (n) {
                        var title = $('input[name=title]').val();
                        var data = {
                            _token: "{{csrf_token()}}",
                        };
                        if (title) {
                            data.title = title;
                        }
                        $.get("{{route('admin.setting.micro.page.compoent.getGoodsData')}}?page=" + n + '&limit=5' + '&type=micro_page_componet_goods_group', data, function (data) {
                            $("#goodsList").html("");
                            $("#goodsList").html(data);
                        });
                        this.selectPage(n); //手动条用selectPage进行页码选中切换
                    }
                });
            });


        </script>

        <script>
            (function () {
                'use strict';

                var byId = function (id) {
                        return document.getElementById(id);
                    },

                    loadScripts = function (desc, callback) {
                        var deps = [], key, idx = 0;

                        for (key in desc) {
                            deps.push(key);
                        }

                        (function _next() {
                            var pid,
                                name = deps[idx],
                                script = document.createElement('script');

                            script.type = 'text/javascript';
                            script.src = desc[deps[idx]];

                            pid = setInterval(function () {
                                if (window[name]) {
                                    clearTimeout(pid);

                                    deps[idx++] = window[name];

                                    if (deps[idx]) {
                                        _next();
                                    } else {
                                        callback.apply(null, deps);
                                    }
                                }
                            }, 30);

                            document.getElementsByTagName('head')[0].appendChild(script);
                        })()
                    },

                    console = window.console;

                if (!console.log) {
                    console.log = function () {
                        alert([].join.apply(arguments, ' '));
                    };
                }

                Sortable.create(byId('bar2'), {
                    group: "words",
                    animation: 150,
                    onAdd: function (evt) {
                        console.log('onAdd.bar2:', evt.item);
                    },
                    onUpdate: function (evt) {
                        console.log('onUpdate.bar2:', evt.item);
                    },
                    onRemove: function (evt) {
                        console.log('onRemove.bar2:', evt.item);
                    },
                    onStart: function (evt) {
                        $('body .module-box-son').addClass('box-select-hide');
                        console.log('onStart.foo:', evt.item);
                    },
                    onEnd: function (evt) {
                        $('body .module-box-son').addClass('box-select-hide');
                        console.log('onEnd.foo:', evt.item);
                    }
                });

            })();


        </script>

@endsection










