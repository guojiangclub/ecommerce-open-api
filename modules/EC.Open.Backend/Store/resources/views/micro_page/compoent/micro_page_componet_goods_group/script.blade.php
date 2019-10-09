<script>
    var compoent_html = ["  <li class=\"advert_li clearfix\" >",
        "                                <div class=\"del\">",
        "                                    <i class=\"fa fa-remove\"></i>",
        "                                </div>",
        "                                 <div class=\"box_img\" style='display: none'>",
        "                                    <div class=\"img-upload img-upload-init\">",
        "                                           <div class=\"box_img\">",
        "                                            <img width=\"88\" height=\"88\" src='{#img#}'>",
        "                                            <div class=\"replace_img\">",
        "                                            <span>更换图片</span>",
        "                                            </div>",
        "                                        </div>",
        "                                    </div>",
        "                                    <div class=\"upload\">",
        "                                    </div>",
        "                                </div>",
        "                                <div class=\'box_input_group\'>",
        "                                <div class=\'box_input\'>",
        "                                     <label class=\"text-right\">分组名称:</label>",
        "                                     <div>{#title#}</div>",
        "                                </div>",
        "                                <div class=\'box_input\'>",
        "                                     <label class=\"text-right\">商品数量:</label>",
        "                                     <div>{#num#}</div>",
        "                                </div>",
        "                                <div class=\'box_input\'>",
        "                                     <label class=\"text-right\">" +
        "<a class='edit'   style='cursor: pointer'  href='javascript:;'  data-type='{#type#}'   data-ids='{#ids#}' data-name='{#name#}' data-index='{#index#}' >编辑</a>" +
        "<input class='form-control' name='goods_ids' type='hidden' value='{#goods_ids#}'>" +
        "</label>",
        "                                </div>",
        "                               </div>",
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

    function addGoodsGroup() {

        index_init();

        var url = "{{route('admin.setting.micro.page.compoent.model.goods',['type'=>'micro_page_componet_goods_group','index'=>0])}}"

        var new_url = url ;

        $('#promote-goods-btn').data('url', new_url)

        $("#promote-goods-btn").trigger("click");

    }
</script>

<script>

    window.box_index = null;

    $(function () {
        var length = $('.advert_li').length;
        $('.advert_li').each(function (val, index) {
            var num = $(this).attr('index');
            uploadImg('.upload-' + num, num, 'edit');
        })

    })

    function uploadImg(pick, index, action = 'create') {
        console.log(1);
        $(function () {
            var uploader = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: true,
                swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                server: '{{route('upload.image',['_token'=>csrf_token()])}}',
                pick: pick,
                fileVal: 'upload_image',
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });
            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function (file, response) {
                console.log(pick);
                addImg(pick, response.url);
                $(pick).find('.img-upload').removeClass('img-upload-init')
            });
        });
    }

    function addImg(pick, url,action = 'create') {

        console.log(pick);

        var img = [" <img width=\"88\" height=\"88\" src=\"" + url +
        "\" alt=\"\">",
            "                                     <div class=\"replace_img\">",
            "                                         <span >更换图片</span>",
            "                                     </div>"].join("");
        $(pick).find('.img-upload').html(img);

        if (action == 'edit') {
            $(pick).find('.img-upload').addClass('img-upload-end');
        }

        $(pick).find('i').remove();
    }


    function upload(pick) {
        var uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
            server: '{{route('upload.image',['_token'=>csrf_token()])}}',
            pick: pick,
            fileVal: 'upload_image',
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });
    }

    //edit
    $('body .advert').on('click','.edit',function () {

        index_init();

        var that=$(this);

        var type=that.data('type');

        if(type=='micro_page_componet_goods_group'){

            var goods_ids=that.data('ids');

            var title=that.data('name');

            var index=that.attr('data-index');

            var url = "{{route('admin.setting.micro.page.compoent.model.goods',
        ['type'=>'micro_page_componet_goods_group'])}}"+'&goods_ids='+goods_ids+'&title='+title+'&index='+index;

            var new_url = url ;

            $('#promote-goods-btn').data('url', new_url)

            $("#promote-goods-btn").trigger("click");

        }else{

            var title=that.data('name');

            var index=that.attr('data-index');

            var input=that.data('input');

            var url = "{{route('admin.setting.micro.page.compoent.model.images',
        ['type'=>'micro_page_componet_goods_group_img'])}}"+'&title='+title+'&index='+index+'&input='+JSON.stringify(input);

            console.log(url);

            var new_url = url ;

            $('#img-btn').data('url', new_url)

            $("#img-btn").trigger("click");

        }


    })

    //检查数据
    function testData() {
        var num = $('.advert_li').length;

    }
    //保存
    function save(action){

        // if(status==1){
        //
        //     console.log(1);return;
        // }

        var data={};

        var name=$('#advert-name').val();

        if(name==''){
            swal("保存失败!", '请输入标题', "error");return;
        }

        $('#bar .advert_li').each(function (v, obj) {

            data[v]={'image':null,'type':null, 'sort':null}

            var obj=$(obj);

            // var img=obj.find('img').attr('src');
            // if(typeof(img)=='undefined'){
            //     swal("保存失败!", '请上传图片', "error");return;
            // }
            //data[v]['image']=img;

             var name=obj.find('.edit').attr('data-name');

             var type=obj.find('.edit').attr('data-type');

            if(type=='micro_page_componet_goods_group'){

                var ids=obj.find('input[name=goods_ids]').val();

                var ids_arr = ids.split(',');

                data[v]['type']='micro_page_componet_goods_group';

                data[v]['associate_id']=ids_arr;

                data[v]['associate_type']='goods';

                data[v]['sort']=v+1;

                data[v]['name']=name;

                data[v]['meta']=JSON.stringify({"count":ids_arr.length,'goods_ids':ids});

            }else{

                var input=obj.find('.edit').attr('data-input');

                var len=obj.find('.edit').attr('data-len');

                if(typeof(input)!='undefined'){

                    data[v]['type']='micro_page_componet_goods_group_img';

                    data[v]['input']=JSON.parse(input);

                    data[v]['sort']=v+1;

                    data[v]['name']=name;

                    data[v]['meta']=JSON.stringify({"count":len,"input":input});

                }

            }

        })

        var input={};

        input.input=data;

        input.advert_id="{{$advert_id}}";

        input.advert_name=name;

        if(JSON.stringify(data) == "{}"){

            swal("保存失败!", '请添加商品分组', "error");return;
        }


        if(action=='edit'){

            status=1;

            var href="{{route('admin.setting.micro.page.compoent.update',['_token'=>csrf_token()])}}"+'&type=micro_page_componet_goods_group';

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