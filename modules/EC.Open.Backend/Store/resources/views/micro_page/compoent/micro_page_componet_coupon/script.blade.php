<script>
    var compoent_componet_html_1=["<li class=\"advert_li clearfix\" draggable=\"false\" style=\"\" data-content_id={#id#}>",
        "                                <div class=\"coupon\">",
        "                                    <div class=\"del\"><i class=\"fa fa-remove\"></i></div>",
        "                                    <div class=\"left-item\">",
        "                                        <div class=\"money-1\">",
        "                                            <span style=\"font-size: 20px;\">¥</span>",
        "                                            <span class=\"money-value\">{#value#}</span>",
        "                                        </div>",
        "                                        <div class=\"money-2\" style=\"display: none\">",
        "                                            <span class=\"money-value\">9</span>",
        "                                            <span style=\"font-size: 20px;\">折</span>",
        "                                        </div>",
        "                                    </div>",
        "                                    <div class=\"right-item\">",
        "                                        <div class=\"title\">{#title#}</div>",
        "                                        <br>",
        "                                        <div class=\"time pull-right\">{#time#}</div>",
        "                                    </div>",
        "                                </div>",
        "                            </li>"].join("");


    var compoent_componet_html_2=["<li class=\"advert_li clearfix\" draggable=\"false\" style=\"\" data-content_id={#id#}>",
        "                                <div class=\"coupon\">",
        "                                    <div class=\"del\"><i class=\"fa fa-remove\"></i></div>",
        "                                    <div class=\"left-item\">",
        "                                        <div class=\"money-1\" style=\"display: none\">",
        "                                            <span style=\"font-size: 20px;\">¥</span>",
        "                                            <span id=\"id-money-value\"  class=\"money-value\"></span>",
        "                                        </div>",
        "                                        <div class=\"money-2\">",
        "                                            <span class=\"money-value\">{#value#}</span>",
        "                                            <span style=\"font-size: 20px;\">折</span>",
        "                                        </div>",
        "                                    </div>",
        "                                    <div class=\"right-item\">",
        "                                        <div class=\"title\">{#title#}</div>",
        "                                        <br>",
        "                                        <div class=\"time pull-right\">{#time#}</div>",
        "                                    </div>",
        "                                </div>",
        "                            </li>"].join("");

    function index_init(){
        $('.advert_li').each(function (v,obj) {
            var old_index=$(obj).attr('index');
            $(obj).removeClass('advert_li_'+old_index);
            $(obj).attr('index',v+1);
            $(obj).addClass('advert_li_'+(v+1));
            $(obj).find('.edit').attr('data-index',(v+1))
        })
    }
    //删除
    $("body .advert").on("click", ".del", function () {
        var remove = $(this).parents('.advert_li');
        remove.remove();
        index_init();
    });

    function addCoupon() {

        var url = "{{route('admin.setting.micro.page.compoent.model.coupons')}}"

        var new_url = url ;

        $('#promote-goods-btn').data('url', new_url)

        $("#promote-goods-btn").trigger("click");
    }

</script>


<script>
    //保存
    function save(action){

        var data={};

        if(status==1){
            console.log(1);return;
        }

        var name=$('#advert-name').val();

        if(name==''){
            swal("保存失败!", '请输入标题', "error");return;
        }

        if($('#bar li').length<=0){

            swal("保存失败!", '请选择优惠券', "error");return;
        }


        $('#bar li').each(function (v, obj) {

            data[v]={'type':'coupon','link':null, 'sort':null}

            var obj=$(obj);

            data[v]['sort']=v+1;

            var id=obj.attr('data-content_id');

            data[v]['associate_id']=id;

            data[v]['associate_type']='discount';

        })

        var input={};

        input.input=data;

        input.advert_name=name;

        input.type='micro_page_componet_coupon';

        if(action=='edit'){

            status=1;

            input.advert_id="{{$advert_id}}";

            var href="{{route('admin.setting.micro.page.compoent.update',['_token'=>csrf_token()])}}";

            $.post(href,input,function (result) {

                if (!result.status) {
                    swal("保存失败!", result.message, "error")
                } else {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = "{{route('admin.setting.micro.page.compoent.index',$type)}}";
                    });
                }

                status=0;

            })

        }

        if(action=='create'){

            status=1;

            input.type="{{$type}}";

            var href="{{route('admin.setting.micro.page.compoent.store',['_token'=>csrf_token()])}}";

            $.post(href,input,function (result) {

                if (!result.status) {
                    swal("保存失败!", result.message, "error")
                } else {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location = "{{route('admin.setting.micro.page.compoent.index',$type)}}";
                    });
                }

                status=0;

            })

        }



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
                index_init();
            }
        });

    })();


</script>