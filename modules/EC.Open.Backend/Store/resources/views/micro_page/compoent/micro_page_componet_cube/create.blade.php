<style>
    .layout img{
        cursor:pointer;
    }

    .advert_li {
        display: flex;
        min-height: 120px;
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

    <a style="display: none"  class="btn btn-primary margin-bottom" id="promote-goods-btn" data-toggle="modal"
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
                               value=""/>

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">前端显示标题:</label>

                    <div class="col-sm-6">

                        <input type="text" class="form-control taginput" name="advert-title" id="advert-title" placeholder=""
                               value=""/>

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">前端是否显示标题:</label>

                    <div class="col-sm-6" >

                        是<input type="radio" name="is_show_title" id="" value="1">

                        否<input type="radio" name="is_show_title" id="" value="0"  checked>

                    </div>

                </div>

            </div>


            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*模式:</label>

                    <div class="col-sm-6">
                            <select class="form-control" id="pattern">
                                <option value=1>1图模式</option>
                                <option value=2>2图模式</option>
                                <option value=3>3图模式</option>
                                <option value=4>4图模式</option>
                            </select>

                    </div>

                </div>

            </div>


            <div class="panel-body layout" id="layout_1" >

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*布局:</label>

                    <div class="col-sm-10">

                        <img src="{{env("APP_URL").'/assets/backend/images/cube-new/1/1-1.png'}}"

                             data-select="{{env("APP_URL").'/assets/backend/images/cube-new/1/1-2.png'}}"

                             data-src="{{env("APP_URL").'/assets/backend/images/cube-new/1/1-1.png'}}"

                             data-type=""

                             alt="">

                    </div>

                </div>

            </div>


            <div class="panel-body layout" id="layout_2" style="display: none">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*布局:</label>

                    <div class="col-sm-10">

                        <img src="{{env("APP_URL").'/assets/backend/images/cube-new/2/2-1.png'}}"

                             data-select="{{env("APP_URL").'/assets/backend/images/cube-new/2/2-4.png'}}"

                             data-src="{{env("APP_URL").'/assets/backend/images/cube-new/2/2-1.png'}}"

                             data-type="2_1"

                             alt="">

                        <img src="{{env("APP_URL").'/assets/backend/images/cube-new/2/2-2.png'}}"

                             data-select="{{env("APP_URL").'/assets/backend/images/cube-new/2/2-5.png'}}"

                             data-src="{{env("APP_URL").'/assets/backend/images/cube-new/2/2-2.png'}}"

                             data-type="2_2"

                             alt="">

                        <img src="{{env("APP_URL").'/assets/backend/images/cube-new/2/2-3.png'}}"

                             data-select="{{env("APP_URL").'/assets/backend/images/cube-new/2/2-6.png'}}"

                             data-src="{{env("APP_URL").'/assets/backend/images/cube-new/2/2-3.png'}}"

                             data-type="2_3"

                             alt="">

                    </div>

                </div>

            </div>

            <div class="panel-body layout" id="layout_3" style="display: none">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*布局:</label>

                    <div class="col-sm-10">


                        <img src="{{env("APP_URL").'/assets/backend/images/cube-new/3/3-3.png'}}"

                             data-select="{{env("APP_URL").'/assets/backend/images/cube-new/3/3-4.png'}}"

                             data-src="{{env("APP_URL").'/assets/backend/images/cube-new/3/3-3.png'}}"

                             data-type="3_1"

                             alt="">


                        <img src="{{env("APP_URL").'/assets/backend/images/cube-new/3/3-1.png'}}"

                             data-select="{{env("APP_URL").'/assets/backend/images/cube-new/3/3-5.png'}}"

                             data-src="{{env("APP_URL").'/assets/backend/images/cube-new/3/3-1.png'}}"

                             data-type="3_2"

                             alt="">

                        <img src="{{env("APP_URL").'/assets/backend/images/cube-new/3/3-2.png'}}"

                             data-select="{{env("APP_URL").'/assets/backend/images/cube-new/3/3-6.png'}}"

                             data-src="{{env("APP_URL").'/assets/backend/images/cube-new/3/3-2.png'}}"

                             data-type="3_3"

                             alt="">


                    </div>

                </div>

            </div>

            <div class="panel-body layout" id="layout_4" style="display: none">

                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">*布局:</label>

                    <div class="col-sm-6">

                        <img src="{{env("APP_URL").'/assets/backend/images/cube-new/4/4-1.png'}}"

                             data-select="{{env("APP_URL").'/assets/backend/images/cube-new/4/4-4.png'}}"

                             data-src="{{env("APP_URL").'/assets/backend/images/cube-new/4/4-1.png'}}"

                             data-type="4_1"

                             alt="">

                        <img src="{{env("APP_URL").'/assets/backend/images/cube-new/4/4-2.png'}}"

                             data-select="{{env("APP_URL").'/assets/backend/images/cube-new/4/4-3.png'}}"

                             data-src="{{env("APP_URL").'/assets/backend/images/cube-new/4/4-2.png'}}"

                             data-type="4_2"

                             alt="">

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="form-group advert">
                    <label class="col-sm-2 control-label text-right">*图片:</label>

                    <div class="col-sm-8 col-lg-5 advert-box">
                        <ul id="bar" style="margin-left: -50px;">

                        </ul>


                        <ul>
                            {{--<li class="advert_b_li">--}}
                                {{--<a class="fa fa-plus">--}}
                                    {{--<span onclick="add('bar',compoent_slide_html)">  添加一个{{$header}}</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <p style="margin-left: -35px;">*拖动可更改图片位置</p>
                        </ul>

                    </div>

                </div>

            </div>


            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <div class="panel-body">

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button id="save" class="btn btn-primary"  is_show_title="0" type=""  onclick="save('create')">保存</button>
                    </div>
                </div>

            </div>



        </div>
    </div>

    <div id="goods_modal" class="modal inmodal fade"></div>

    <script src="https://cdn.bootcss.com/Sortable/1.6.0/Sortable.min.js"></script>

    @include('store-backend::micro_page.compoent.micro_page_componet_cube.script')


    <script>
        add('bar',compoent_slide_html)
    </script>


    <script>

        $(function () {

            $('#pattern').change(function () {

                var pattern=$('#pattern').val();

                $('.layout').hide();

                $('#layout_'+pattern).show();

                $('#bar').empty();

                init();

                for (var i=0;i<pattern;i++){

                    add('bar',compoent_slide_html)
                }
            })

        })
    </script>





