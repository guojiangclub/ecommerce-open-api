<style>
    .module-box {
        margin-top: 20px;
        margin-bottom: 20px;
        margin-left: -10px;
    }

    .module-box-z {
        margin-top: 20px;
        margin-bottom: 20px;
        margin-left: -10px;
    }

    .box-ranks {
        /*height: 50px;*/
        background-color: #FFFFFF;
        line-height: 50px;
        cursor: pointer
    }

    .box-select {
        background-color: #FFF3F3F3;
        line-height: 50px;
    }

    .box-select-hide {
        background-color: #FFF3F3F3;
        line-height: 50px;
        display: none;
    }

    .box-line {
        background-color: #FFF3F3F3;
        border: 1px #FFDBDBDB solid;
        margin-left: 20px;
    }

    .box-border {
        border: 1px #FFDBDBDB solid;
    }

    .box-1 {
        margin-left: -12px;
    }

    .box-2 {
        margin-left: 30px;
    }

    .box-select .row {

        margin-bottom: 20px;
    }

    .module-box-add {
        line-height: 35px;
        margin-bottom: 20px;
    }

    .remove-module-box {

        cursor: pointer;
    }
</style>


<script>


</script>


<div class="tabs-container">
    @if (session()->has('flash_notification.message'))
        <div class="alert alert-{{ session('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {!! session('flash_notification.message') !!}
        </div>
    @endif
    {{--<ul class="nav nav-tabs">--}}
    {{--<li><a  href="{{route('admin.setting.micro.page.index')}}">微页面</a></li>--}}
    {{--<li class="active"><a data-toggle="tab"  aria-expanded="true">微页面编辑</a></li>--}}
    {{--</ul>--}}

    <div class="tab-content">

        <div class="tab-pane active">

            <div class="panel-body" style="margin-bottom:20px;">

                <div class="col-sm-12" style="margin-bottom:10px;">

                    {{$page->name}}

                </div>

                <a class="col-sm-12" style="margin-bottom:20px;">
                    {{--详细使用说明--}}
                    {{--<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top">--}}
                    {{--</i>--}}
                </a>


                <div class="col-sm-12">

                    <div class="col-sm-5 box-1 box-line">

                        <div style="margin-top:20px;">

                            <h3>已有模块</h3>

                            <span style="font-size:14px;">拖放各个模块到您喜欢的顺序，点击右侧的箭可进行详细设置.</span>

                        </div>

                        <div id="bar" class="module-box col-sm-12">


                        </div>

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-5" style="margin-bottom: 50px;">
                                <button class="btn btn-primary" type="" onclick="save()">保存</button>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-5 box-2 box-line">

                        <div style="margin-top:20px;">

                            <h3>可用模块</h3>

                            <span style="font-size:14px;">点击添加模块，可多次添加模块。</span>

                        </div>

                        <div class="module-box-z col-sm-12">

                            <div class="col-sm-12">


                                @include('store-backend::micro_page.page.includes.module-box')

                            </div>

                        </div>


                    </div>

                    <div class="clearfix"></div>

                </div>

            </div>

        </div>
    </div>


</div>

<script src="https://cdn.bootcss.com/Sortable/1.6.0/Sortable.min.js"></script>

@include('store-backend::micro_page.page.script')

<script>

    @if($adverts->count())

            @foreach($adverts as $item)

                @if($item->advert_id>0)

                    @if($item->advert->type=='micro_page_componet_slide')

                       add('module-box',module_box_slide_html);

                       slide("{{$item->advert_id}}")

                    @elseif($item->advert->type=='micro_page_componet_coupon')

                      add('module-box',module_box_coupon_html);

                     slide("{{$item->advert_id}}")

                    @elseif($item->advert->type=='micro_page_componet_nav')

                    add('module-box',module_box_nav_html);

                    slide("{{$item->advert_id}}")

                    @elseif($item->advert->type=='micro_page_componet_seckill')

                    add('module-box',module_box_seckill_html);

                    slide("{{$item->advert_id}}")

                    @elseif($item->advert->type=='micro_page_componet_free_event')

                    add('module-box',module_box_free_event_html);

                    slide("{{$item->advert_id}}")

                    @elseif($item->advert->type=='micro_page_componet_groupon')

                    add('module-box',module_box_groupon_html);

                    slide("{{$item->advert_id}}")

                    @elseif($item->advert->type=='micro_page_componet_suit')

                    add('module-box',module_box_suit_html);

                    slide("{{$item->advert_id}}")

                    @elseif($item->advert->type=='micro_page_componet_category')

                    add('module-box',module_box_category_html);

                    slide("{{$item->advert_id}}")

                    @elseif($item->advert->type=='micro_page_componet_goods_group')

                    add('module-box',module_box_goods_group_html);

                    slide("{{$item->advert_id}}")

                    @elseif(stristr($item->advert->type,'componet_cube'))

                    add('module-box',module_box_cube_html);

                    slide("{{$item->advert_id}}")

                    @endif

                @endif


                 @if($item->advert_id==-1)
                    add('module-box',module_box_search_html);
                 @endif

            @endforeach

    @endif


    function slide(id){

        var li = $('.module-box').find('.module-box-slide').last();

        li.find('select').val(id);
     }

    //add('module-box',module_box_slide_html);

    $('body .module-box').on("click", ".box-ranks", function () {

        var that = $(this);

        var son = that.parent().find('.module-box-son');

        if (son.hasClass('box-select-hide')) {
            return son.removeClass('box-select-hide')
        }
        if (!son.hasClass('box-select-hide')) {
            return son.addClass('box-select-hide')
        }
    })


    function add(id, str) {
        var div = $('.' + id);
        div.append(str);
        var li = div.find('.module-box-slide').last();
        var option = li.find('option').length;
        if (option == 0) {
            li.remove();
            swal("无可选模块组件", '请先创建相关模块组件', "warning");
            return;
        }

        {{--@if($adverts->count()==0)--}}
        {{--li.find('option').eq(1).attr('selected', 'selected');--}}
        {{--@endif--}}

    }

    $("body .module-box").on("click", ".remove-module-box", function () {

        var module_box_remove = $(this).parents('.module-box-slide');
        module_box_remove.remove();

    });


    function save() {

        if (status == 1) {

            console.log(1);
            return;
        }

        var data = {};

        var micro_page_id = "{{$id}}"

        $('.module-box-slide').each(function (v, obj) {

            data[v] = {'sort': null, 'advert_id': null, 'micro_page_id': micro_page_id}

            var obj = $(obj);

            var advert_id = obj.find('.box-select select').val();

            data[v]['advert_id'] = advert_id;

            data[v]['sort'] = v + 1;

        })

        if (JSON.stringify(data) == "{}") {

            swal("保存失败!", '请添加模块组件', "error");
            return;
        }

        var postUrl = "{{route('admin.setting.micro.page.updateMicroPageAd',$id)}}"

        $.post(postUrl, data, function (result) {
            if (result.status) {
                swal({
                    title: "保存成功！",
                    text: "",
                    type: "success"
                }, function () {
                    stats = 0;
                    //location = '{{route('admin.setting.micro.page.index')}}';
                    location = '';
                });
            } else {
                stats = 0;
                swal({
                    title: '保存失败',
                    text: result.message,
                    type: "error"
                });
            }

        });

        console.log(data);
    }

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

        Sortable.create(byId('bar'), {
            group: "words",
            animation: 150,
            onAdd: function (evt) {
                console.log('onAdd.bar:', evt.item);
            },
            onUpdate: function (evt) {
                console.log('onUpdate.bar:', evt.item);
            },
            onRemove: function (evt) {
                console.log('onRemove.bar:', evt.item);
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



