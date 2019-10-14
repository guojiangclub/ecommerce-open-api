<script>
    var compoent_slide_html = ["  <li class=\"advert_li clearfix\">",
        // "                                <div class=\"del\">",
        // "                                    <i class=\"fa fa-remove\"></i>",
        // "                                </div>",
        "                                 <div class=\"box_img\">",
        "                                    <div class=\"img-upload\">",
        "                                        ",
        "                                    </div>",
        "                                    <div class=\"upload\">",
        "                                        <i class=\"fa fa-plus add-img\">",
        "                                        </i>",
        "                                    </div>",
        "                                </div>",
        "                                <div class=\'box_input_group\'>",
        "                                <div class=\'box_input\'>",
        "                                     <label class=\"text-right\">链接:</label>",
        "                                    <select class=\"form-control type-s\" name=\"type\">",
        "                                        <option value=0>——请选择链接类型——</option>",
        "                                        <option value=\"store_detail\" data-page=\"/pages/store/detail/detail?id=\">商品详情页</option>",
        "                                        <option value=\"store_list\" data-page=\"/pages/store/list/list?c_id=\">商品分类页</option>",
        // "                                        <option value=\"store_seckill\" data-page=\"/pages/store/seckill/seckill\">秒杀列表页</option>",
        // "                                        <option value=\"store_groups\" data-page=\"/pages/store/groups/groups\">拼团列表页</option>",
        // "                                        <option value=\"store_callList\" data-page=\"/pages/store/callList/callList\">集CALL列表页</option>",
        // "                                        <option value=\"store_mealList\" data-page=\"/pages/store/mealList/mealList\">套餐列表页</option>",
        "                                        <option value=\"other_micro\" data-page=\"/pages/index/microPages/microPages?id=\">微页面</option>",
        "                                        <option value=\"other_links\" data-page=\"/pages/other/links/links?url=\">公众号文章</option>",
        "                                        <option value=\"other\" data-page='other'>自定义</option>",
        "                                    </select>",

        "                                </div>",
        "                                     <div class=\'box_input link-input\' style=\"display: none\">",
        "                                           <label class=\"link-type text-right\"></label>",
        "                                           <input type=\"text\" class=\"form-control inputLink  \" value=\"\" name=\"link\"  data-index=\"\"   data-type=\"\" data-page=\"\"  disabled  placeholder=\"\"/>",
        "                                     </div>",
        "                               </div>",
        "                            </li>"].join("");
</script>



<script>

    function init(){

        $('.layout img').each(function (v, obj) {

            var obj=$(obj);

            var src=obj.attr('data-src');
            obj.removeClass('select')
            obj.attr('src',src);

        })
    }

    $(function () {
        $('.layout img').click(function () {
            init();
            var that=$(this);
            if(that.data('type')==''){

                return;
            }
            var select=that.attr('data-select');
            var src=that.attr('data-src');
            if(that.hasClass('select')){
                that.attr('src',src);
                that.removeClass('select')
            }else{
                that.attr('src',select);
                that.addClass('select')
            }
        })
    })
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

    //删除
    $("body .advert").on("click", ".del", function () {
        var remove = $(this).parents('.advert_li');
        remove.remove();
    });

    $("body .advert").on("click", ".link-input", function () {

        var link_input_input = $(this).find('input');

        var index=link_input_input.data('index');

        var type_s=$($('.advert_li_'+index)).find('.box_input_group').find('.box_input').eq(0).find('.type-s option:selected');

        var index = link_input_input.data('index');

        var val = link_input_input.val();

        //选择商品
        if (type_s.val() == 'store_detail') {

            var url = "{{route('admin.setting.micro.page.compoent.model.goods')}}"

            var new_url = url + '?index=' + index + '&goods_id=' + val;

            $('#promote-goods-btn').data('url', new_url)

            $("#promote-goods-btn").trigger("click");

        }

        //选择分类
        if (type_s.val() == 'store_list') {


            var url = "{{route('admin.setting.micro.page.compoent.model.categorys')}}"

            var new_url = url + '?index=' + index + '&category_id=' + val;

            $('#promote-goods-btn').data('url', new_url)

            $("#promote-goods-btn").trigger("click");

        }

        //选择微页面
        if (type_s.val() == 'other_micro') {

            var url = "{{route('admin.setting.micro.page.compoent.model.pages')}}"

            var new_url = url + '?index=' + index + '&page_id=' + val;

            $('#promote-goods-btn').data('url', new_url)

            $("#promote-goods-btn").trigger("click");

        }


    });

    //选择链接类型
    $("body .advert").on("change", ".type-s", function () {
        var that = $(this);
        var val = that.find("option:selected").val();
        var page = that.find("option:selected").data('page');
        var link_input = that.parents('.advert_li').find('.box_input_group .link-input');
        var link_input_input = $($(link_input)).find('input');
        link_input_input.attr('data-type', val);
        link_input_input.attr('data-page', page);

        if (val == 'other_links') {
            link_input.show();
            link_input_input.val('');
            link_input_input.removeAttr("disabled");
            link_input_input.attr('placeholder', '请输入微信公众号文章链接');
        } else {
            link_input.hide();
            link_input_input.val('');
            link_input_input.attr("disabled");
            link_input_input.attr('placeholder', '');
        }

        if (val == 'other') {
            link_input.show();
            link_input_input.val('');
            link_input_input.removeAttr("disabled");
            link_input_input.attr('placeholder', '请输入自定义链接');
        }

        var index = link_input_input.data('index');

        // 选择商品
        if (val == 'store_detail') {

            var url = "{{route('admin.setting.micro.page.compoent.model.goods')}}"

            var new_url = url + '?index=' + index;

            $('#promote-goods-btn').data('url', new_url)

            $("#promote-goods-btn").trigger("click");
        }

        // 选择分类
        if(val == 'store_list'){

            var url = "{{route('admin.setting.micro.page.compoent.model.categorys')}}"

            var new_url = url + '?index=' + index;

            $('#promote-goods-btn').data('url', new_url)

            $("#promote-goods-btn").trigger("click");
        }

        //选择微页面
        if(val == 'other_micro'){

            var url = "{{route('admin.setting.micro.page.compoent.model.pages')}}"

            var new_url = url + '?index=' + index;

            $('#promote-goods-btn').data('url', new_url)

            $("#promote-goods-btn").trigger("click");
        }

    });


    //检查数据
    function testData() {
        var num = $('.advert_li').length;

    }

    //添加
    function add(id, str) {
        var div = $('#' + id);
        var li = div.append(str);
        var num = $('.advert_li').length;
        var ad = $('.advert_li').eq(num - 1).attr('index', num);
        $('.advert_li').eq(num - 1).addClass('advert_li_' + num,);
        $('.advert_li').eq(num - 1).find('.box_img').addClass('upload-' + num);
        $('.advert_li').eq(num - 1).find('.inputLink').addClass('inputLink-' + num);
        $('.advert_li').eq(num - 1).find('.inputLink').data('index', num);
        uploadImg('.upload-' + num, num);
    }

    $('input[name=is_show_title]').on('ifChecked', function(event){
        var is_show_title=$('#save').attr('is_show_title');
        if(is_show_title==1){
            $('#save').attr('is_show_title',1)
        }else{
            $('#save').attr('is_show_title',0)
        }
    });

    //保存
    function save(action){

        if(status==1){

            console.log(1);return;
        }

        var data={};

        var name=$('#advert-name').val();

        if(name==''){
            swal("保存失败!", '请输入标题', "error");return;
        }

         pattern=$('#pattern').val();

         layout=$('.layout .select');

        if(layout.length==0 && pattern>1){

            swal("保存失败!", '请选择布局', "error");return;
        }


        $('.advert_li').each(function (v, obj) {

            data[v]={'image':null,'type':null,'link':null, 'sort':null,}

            var obj=$(obj);

            var img=obj.find('img').attr('src');
            if(typeof(img)=='undefined'){
                swal("保存失败!", '请上传图片', "error");return;
            }
            data[v]['image']=img;

            var type=obj.find('.inputLink').attr('data-type');

            var page=obj.find('.inputLink').attr('data-page');

            if(type=='' || page==''){
                swal("保存失败!", '选择链接类型', "error");return;
            }
            var val=obj.find('.inputLink').val();

            data[v]['type']=type;

            data[v]['link']=page+val;

            data[v]['sort']=v+1;

            switch(type)
            {
                case 'store_detail':
                    data[v]['associate_id']=val;
                    data[v]['associate_type']='goods';
                    break;
                case 'store_list':
                    data[v]['associate_id']=val;
                    data[v]['associate_type']='category';
                    break;
                case 'other_micro':
                    data[v]['associate_id']=val;
                    data[v]['associate_type']='microPage';
                    break;
                case 'other_links':
                    data[v]['meta']=JSON.stringify({"link":val});
                    break;
                case 'other':
                    data[v]['link']=val;
                    break;
                default:

            }

            if(val==''){
                if(type=='store_detail' || type=='store_list' ||type=='other_links' ||type=='other_micro' || type=='other')
                    swal("保存失败!", '请完善数据', "error");return;
            }

        })


        var input={};

        input.input=data;

        input.advert_id="{{$advert_id}}";

        input.advert_name=name;

        input.advert_title=$('#advert-title').val();

        input.is_show_title=$('#save').attr('is_show_title');

        if(JSON.stringify(data) == "{}"){

            swal("保存失败!", '', "error");return;
        }

        if(typeof(layout.attr('data-type'))=='undefined'){

            input.type="{{$type}}";

        }else{

            input.type="{{$type}}"+'_'+layout.attr('data-type');
        }


        if(!check_input(input.input)) return;

        if(action=='edit'){

            status=1;

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


    $('#store').ajaxForm({

        beforeSubmit: function (data) {
            var input = [];
            $.each(data, function (k, v) {
                if (v.name !== "lenght") {
                    input[v.name] = v.value;
                }
            })

            if (input['name'] == '') {
                swal("保存失败!", '请输入名称', "error")
                return false;
            }

        },

        success: function (result) {
            if (!result.status) {
                swal("保存失败!", result.message, "error")
            } else {
                swal({
                    title: "保存成功！",
                    text: "",
                    type: "success"
                }, function () {
                    location = "";
                });
            }

        }
    });

    function check_input(input) {
        for(k in input){
            if(!input[k]['image']){
                return false;
            }
            if(!input[k]['type']){
                return false;
            }
            if(!input[k]['link']){
                return false;
            }
        }
        return true;
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