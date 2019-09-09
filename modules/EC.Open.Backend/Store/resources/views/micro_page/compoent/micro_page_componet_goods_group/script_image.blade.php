<script>
    var compoent_2_html = ["  <li class=\"advert_li advert_li_img clearfix\" >",
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
        "                                     <label class=\"text-right\">图片数量:</label>",
        "                                     <div>{#num#}</div>",
        "                                </div>",
        "                                <div class=\'box_input\'>",
        "                                     <label class=\"text-right\">" +
        "<a class='edit edit-img'   style='cursor: pointer'  href='javascript:;'  data-input='{#input#}'  data-type='{#type#}'  data-name='{#name#}' data-index='{#index#}' data-len='{#len#}' >编辑</a>" +
        "<input class='form-control' name='input' type='hidden' value='{#data#}'>" +
        "</label>",
        "                                </div>",
        "                               </div>",
        "                            </li>"].join("");




    function addGoodsGroupImage() {

        index_init();

        var url = "{{route('admin.setting.micro.page.compoent.model.images',['type'=>'micro_page_componet_goods_group_img','index'=>0])}}"

        var new_url = url ;

        $('#img-btn').data('url', new_url)

        $("#img-btn").trigger("click");

    }


    {{--$('body .advert').on('click','.edit-img',function () {--}}

        {{--index_init();--}}

        {{--var that=$(this);--}}

        {{--var title=that.data('name');--}}

        {{--var index=that.attr('data-index');--}}

        {{--var input=that.data('input');--}}

        {{--var url = "{{route('admin.setting.micro.page.compoent.model.images',--}}
        {{--['type'=>'micro_page_componet_goods_group_img'])}}"+'&title='+title+'&index='+index+'&input='+JSON.stringify(input);--}}

        {{--var new_url = url ;--}}

        {{--$('#img-btn').data('url', new_url)--}}

        {{--$("#img-btn").trigger("click");--}}


    {{--})--}}


</script>

