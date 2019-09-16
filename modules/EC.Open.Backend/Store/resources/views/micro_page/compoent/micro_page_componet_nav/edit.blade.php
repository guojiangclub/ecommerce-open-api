<style>

    .advert_li {
        display: flex;
        min-height: 170px;
        background-color: #ffffff;
        margin-left: 10px;
        margin-top: 15px;
        border: 1px #FFDBDBDB dashed;
        position: relative;
    }

    .box_img{
        position: relative;
        height: 90px;
        width: 90px;
        background-color: #FFF3F3F3;
        margin-left: 15px;
        margin-top: 13px;
        border: 1px #FFDBDBDB dashed;
    }

    .box_input{
        display: flex;
        margin-left: 20px;
        /*height: 90px;*/
        /*min-width: 260px;*/
        margin-top: 13px;
    }

    .box_input_name{
        display: flex;
        margin-left: 20px;
        /*height: 90px;*/
        /*min-width: 260px;*/
        margin-top: 13px;
    }

    .box_input_name label{
        width:100px;
        text-align: center;
    }

    .box_input label{
        width:100px;
        text-align: center;
    }

    .advert_b_li {
        min-height: 120px;
        background-color: #ffffff;
        margin-left: -40px;
        margin-top: 20px;
        margin-bottom: 15px;
        border: 1px #FFDBDBDB dashed;
        text-align: center;
        line-height: 120px;
        list-style:none
    }
    .advert-box {
        background-color: #FFF3F3F3;
        min-height: 120px;
        margin-left: 12px;
        border: 1px #FFDBDBDB solid;
    }
    .add-img{
        font-size: 25px;
        line-height: 90px;
        margin-left: 30px;
        verflow: hidden;

    }
    .replace_img{
        position: absolute;
        bottom: 0;
        width:100%;
        background-color: black;
        background:rgba(46,45,45,.8);
        color: #FFFFFF;
        text-align: center;
    }
    .del{
        width: 30px;
        height: 30px;
        border-radius: 100%;
        background:rgba(46,45,45,.5);
        position: absolute;
        right:-8px;
        top:-8px;
        cursor: pointer;
    }
    .del i{
        color: #ffffff;
        line-height: 30px;
        margin-left: 10px;
        z-index: -100;
    }

    .upload label{
        width: 100px;
        height: 100px;
    }

    .webuploader-pick{
        margin-left: -8px;
        background: transparent;
        color: #FFDBDBDB;
    }
    .img-upload {
        position: relative;
        margin-left: -2px;
        margin-top: -5px;
    }

    .img-upload-init{
        margin-left: -18px;
        margin-top: -19px;
    }

    .img-upload-end{
        margin-left: -2px;
        margin-top: -5px;
    }

    .ibox-content{

        border-color:#FFFFFF;
    }

</style>

{!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}

@include('store-backend::micro_page.compoent.common.style')

<div class="ibox float-e-margins">

    <a style="display: none" class="btn btn-primary margin-bottom" id="promote-goods-btn" data-toggle="modal"
       data-target="#goods_modal" data-backdrop="static" data-keyboard="false"
       data-url="">
        选择
    </a>


    <div class="ibox-content">
        <div class="ibox-content">

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*标题:</label>

                    <div class="col-sm-6">

                        <input type="text" class="form-control taginput" name="name" id="advert-name" placeholder=""
                               value="{{$advert->name}}"/>

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group advert">
                    <label class="col-sm-2 control-label text-right">*图片:</label>

                    <div class="col-sm-8 col-lg-5 advert-box">
                        <ul id="bar" style="margin-left: -50px;">

                            @if($advertItems->count())
                                @foreach($advertItems as $key=> $item)
                                    <li class="advert_li clearfix advert_li_{{$key+1}}" index="{{$key+1}}">
                                        <div class="del">
                                            <i class="fa fa-remove"></i>
                                        </div>
                                        <div class="box_img upload-{{$key+1}}">
                                            <div class="img-upload img-upload-init">
                                                <div class="box_img">
                                                    <img width="88" height="88" src="{{$item->image}}" alt="">
                                                    <div class="replace_img">
                                                        <span>更换图片</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="upload">
                                            </div>
                                        </div>


                                        <div class="box_input_group">

                                            <div class='box_input_name'>

                                                <label class="link-type text-right">名称:</label>

                                                <input type="text" class="form-control inputName inputName-{{$key+1}}"  data-index="{{$key+1}}" name="name"  value="{{$item->name}}"  placeholder="前端显示名称"/>

                                            </div>

                                            <div class='box_input'>

                                                <label class="text-right">链接:</label>

                                                <select class="form-control type-s" name="type">
                                                    <option value=0>——请选择链接类型——</option>
                                                    <option value="store_detail" @if($item->type=='store_detail') selected  @endif    data-page="/pages/store/detail/detail?id=">商品详情页</option>
                                                    <option value="store_list"   @if($item->type=='store_list') selected  @endif  data-page="/pages/store/list/list?c_id=">商品分类页</option>
                                                    {{--<option value="store_seckill" @if($item->type=='store_seckill') selected  @endif data-page="/pages/store/seckill/seckill">秒杀列表页</option>--}}
                                                    {{--<option value="store_groups"  @if($item->type=='store_groups') selected  @endif  data-page="/pages/store/groups/groups">拼团列表页</option>--}}
                                                    {{--<option value="store_callList"  @if($item->type=='store_callList') selected  @endif  data-page="/pages/store/callList/callList">集CALL列表页</option>--}}
                                                    {{--<option value="store_mealList"  @if($item->type=='store_mealList') selected  @endif data-page="/pages/store/mealList/mealList">套餐列表页</option>--}}
                                                    <option value="other_micro" @if($item->type=='other_micro') selected  @endif data-page="/pages/index/microPages/microPages?id=">微页面</option>
                                                    <option value="other_links"  @if($item->type=='other_links') selected  @endif data-page="/pages/other/links/links?url=">公众号文章</option>
                                                    <option value="other"  @if($item->type=='other') selected  @endif data-page="other">自定义</option>
                                                </select>

                                            </div>


                                            <div class='box_input link-input'

                                                 @if(!in_array($item->type,['store_list','store_detail','other_micro','other_links','other']))

                                                 style="display: none"

                                                    @endif
                                            >

                                                <label class="link-type text-right"></label>

                                                <input type="text" class="form-control  inputLink inputLink-{{$key+1}}"  data-index="{{$key+1}}"

                                                       @if(in_array($item->type,['store_list','store_detail','other_micro']))

                                                       value="{{$item->associate_id}}" disabled

                                                       @elseif($item->type=='other_links')

                                                       value="{{$item->meta['link']}}"

                                                       @elseif($item->type=='other')

                                                       value="{{$item->link}}"

                                                       @else

                                                       @endif

                                                       data-page="{{config('ibrand.advert.type.'.$item->type.'.page')}}"

                                                       name="link"  data-type="{{$item->type}}" placeholder=""/>

                                            </div>



                                        </div>


                                    </li>
                                @endforeach
                            @endif

                        </ul>


                        <ul>
                            <li class="advert_b_li">
                                <a class="fa fa-plus">
                                    <span onclick="add('bar',compoent_slide_html)"> 添加一个背景图</span>
                                </a>
                            </li>
                            <p style="margin-left: -35px;">*拖动可更改图片位置</p>
                            <p style="margin-left: -35px;">*建议图片尺寸110*110像素</p>
                        </ul>

                    </div>

                </div>

            </div>


            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <div class="panel-body">

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="" onclick="save('edit')">保存</button>
                    </div>
                </div>

            </div>



        </div>
    </div>

    <div id="goods_modal" class="modal inmodal fade"></div>

    <script src="https://cdn.bootcss.com/Sortable/1.6.0/Sortable.min.js"></script>

@include('store-backend::micro_page.compoent.micro_page_componet_nav.script')









