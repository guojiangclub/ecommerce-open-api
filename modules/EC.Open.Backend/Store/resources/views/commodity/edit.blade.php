{!! Html::style(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
{!! Html::script(env("APP_URL").'/vendor/libs/md5.js') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/jquery.el/spec/base.css') !!}
<style type="text/css">
    .wrapper-content, .tab-pane {
        animation-fill-mode: none
    }

    .filelist {
        clear: both;
        width: 100%;
        float: left;
        padding-bottom: 20px;
    }

    .filelist li {
        list-style: none;
        text-align: center;
        margin-right: 12px;
        float: left;
        width: 103px;
        height: 103px
    }

    .filelist li img {
        width: 100px;
        height: 100px
    }

    .filelist .current {
        border: 3px #f60 solid;
    }

    #sku-builder .color-block {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 1px solid #cccccc;
        border-radius: 2px;
        margin-right: 3px;
    }

    #sku-table input[type=file] {
        display: inline-block;
        margin-top: 10px;
    }

    #sku-table th {
        min-width: 90px
    }

    #sku-table th.sku-th {
        min-width: 170px
    }

    #sku-table input {
        width: 100%
    }

    .spec_img_th {
        display: block !important;
    }

    .spec_img_th_active {
        display: none;
    }

    .extend_image > img {
        width: 110px;
        height: 110px;
        cursor: pointer
    }
    .app-actions {
        position: fixed;
        bottom: 0;
        right: 25px;
        left: 226px;
        width: auto;
        padding: 20px 0;
        background: #ffc;
        margin: 50px 0;
        z-index: 999;
        text-align: center;
    }
</style>

<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="active"><a data-id="1" class="app-action" aria-expanded="true">款式信息（SPU）</a></li>
        <li class=""><a data-id="2" class="app-action" data-toggle="tab" aria-expanded="false">规格信息（SKU）</a></li>
        <li class="editor_li"><a data-id="3" class="app-action" data-type="ue"
                                 aria-expanded="false">详细描述(Mobile)</a></li>
        <li class="editor_li"><a data-id="4" class="app-action" data-type="uepc" aria-expanded="false">详细描述(PC)</a>
        </li>

        <li class=""><a data-id="5" class="app-action" aria-expanded="false">橱窗图</a></li>
        <li class=""><a data-id="6" class="app-action" aria-expanded="false">SEO设置</a></li>
    </ul>
    {!! Form::open( [ 'url' => [route('admin.goods.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="panel-body">
                <input type="hidden" name="id" value="{{$goods_info->id}}">
                <input type="hidden" name="img" value="{{$goods_info->img}}">
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品名称：</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" placeholder=""
                               value="{{$goods_info->name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品品牌：</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="brand_id" id="brand_id">
                            <option value="">请选择</option>
                            @foreach($brands as $item)
                                <?php
                                $brand_select = $goods_info->brand_id == $item->id ? 'selected' : '';
                                ?>
                                <option {{$brand_select}} value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">所属分类：</label>
                    <div class="col-sm-10" id="category-box">
                        @include('store-backend::commodity.includes.category-item')
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">商品数量：</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="store_nums" placeholder=""
                               value="{{$goods_info->store_nums}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">商品编号：</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="goods_no" placeholder=""
                               value="{{$goods_info->goods_no}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">市场价：</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="market_price" placeholder=""
                               value="{{$goods_info->market_price}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">销售价：</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="sell_price" placeholder=""
                               value="{{$goods_info->sell_price}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">是否上架：</label>
                    <div class="col-sm-10">
                        <?php $del_sel = $goods_info->is_del == 0 ? 'checked' : '';
                        $is_del = $goods_info->is_del == 2 ? 'checked' : '';
                        ?>
                        <input name="is_del" id="radio_online" type="radio" value="0" {{$del_sel}} /> 是
                        <input name="is_del" type="radio" value="2" {{$is_del}} /> 否
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">是否推荐：</label>
                    <div class="col-sm-10">
                        <?php $commend = $goods_info->is_commend == 1 ? 'checked' : '';
                        $uncommend = $goods_info->is_commend == 0 ? 'checked' : '';
                        ?>
                        <input name="is_commend" type="radio" value="1" {{$commend}}/> 是
                        <input name="is_commend" type="radio" value="0" {{$uncommend}}/> 否
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">商品模型：</label>
                    <div class="col-sm-10">
                        <?php $onchange = $goods_info->model_id == 0 ? 'onchange=create_attr(this.value)' : 'disabled'; ?>
                        <input type="hidden" value="{{$goods_info->model_id}}" name="model_id">
                        <select class="form-control" {{$onchange}} id="model_id">
                            <option value="0">请选择产品模型</option>
                            @foreach($models as $item)
                                <?php $model_sel = $goods_info->model_id == $item['id'] ? 'selected' : ''; ?>
                                <option {{$model_sel}} value="{{$item['id']}}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                        <label>可以加入商品参数，比如：型号，年代，款式...</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">商品参数：</label>
                    <div class="col-sm-10">
                        <table class="table table-bordered table-striped dataTable" id="propert_table">

                            @if(isset($currAttribute))
                                <tbody>
                                @foreach($attrArray as $item)
                                    <?php
                                    $attrValue = '';
                                    $attrValueID = 0;
                                    foreach ($currAttribute as $value) {
                                        if ($value['attribute_id'] == $item->id) {
                                            $attrValue = $value['attribute_value'];
                                            $attrValueID = $value['attribute_value_id'];
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <th>{{$item->name}}</th>
                                        <td>
                                            @if($item->type == 1)
                                                <select class="form-control" name="attr_id_{{$item->id}}">
                                                    <option value="0">请选择</option>
                                                    @foreach($item->values  as $val)
                                                        <option value="{{$val->id}}" {{$attrValueID == $val->id?'selected':''}}>{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" name="attr_id_{{$item->id}}"
                                                       value="{{$attrValue}}" class="form-control"/>

                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">产品标签：</label>
                    <div class="col-sm-10">
                        {!! Form::text('tags', $goods_info['tags'], ['class' => 'form-control form-inputTagator','id'=>'inputGoodsTags', 'placeholder' => '']) !!}
                        <label>输入产品标签名称，按回车添加</label>
                    </div>

                </div>

            </div>

            <div class="app-actions">
                <a data-id="2" data-action="next" class="btn btn-success app-action">下一步»</a>
                <input type="submit" class="btn btn-success app-action-save" data-toggle="form-submit" data-target="#base-form"
                       value="保存">
            </div>
        </div><!-- /.tab-pane -->


        <div class="tab-pane" id="tab_2">
            <div class="panel-body">
                @include('store-backend::commodity.includes.spec')
            </div>

            <div class="app-actions">
                <a data-id="1" data-action="next" class="btn btn-success app-action-prev">«上一步</a>
                <a data-id="3" data-action="next" class="btn btn-success app-action">下一步»</a>
                <input type="submit" class="btn btn-success app-action-save" data-toggle="form-submit" data-target="#base-form"
                       value="保存">
            </div>
        </div>
        <!-- /.tab-pane -->

        <div class="tab-pane" id="tab_3">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-1 control-label">内容同步设置：</label>
                    <div class="col-sm-10">
                        <input name="sync" type="radio" value="0" {{$goods_info->sync == 0?'checked':''}} /> 不同步
                        <input name="sync" type="radio" value="1" {{$goods_info->sync == 1?'checked':''}} /> 同步至PC端
                    </div>
                </div>
                <div id="upload_container" class="btn btn-primary" style="margin-bottom: 10px;">插入相册图片</div>
                <script id="container" name="content"
                        type="text/plain"> {!!$goods_info->content!!}</script>
            </div>
            <div class="app-actions">
                <a data-id="2" data-action="next" class="btn btn-success app-action-prev">«上一步</a>
                <a data-id="4" data-action="next" class="btn btn-success app-action">下一步»</a>
                <input type="submit" class="btn btn-success app-action-save" data-toggle="form-submit" data-target="#base-form"
                       value="保存">
            </div>
        </div><!-- /.tab-pane -->

        <div class="tab-pane" id="tab_4">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-1 control-label">内容同步设置：</label>
                    <div class="col-sm-10">
                        <input name="sync" type="radio" value="2" {{$goods_info->sync == 2?'checked':''}} /> 同步至移动端
                    </div>
                </div>
                <div id="upload_containerpc" class="btn btn-primary" style="margin-bottom: 10px;">插入相册图片</div>
                <script id="containerpc" name="contentpc"
                        type="text/plain"> {!!$goods_info->contentpc!!}</script>
            </div>

            <div class="app-actions">
                <a data-id="3" data-action="next" class="btn btn-success app-action-prev">«上一步</a>
                <a data-id="5" data-action="next" class="btn btn-success app-action">下一步»</a>
                <input type="submit" class="btn btn-success app-action-save" data-toggle="form-submit" data-target="#base-form"
                       value="保存">
            </div>
        </div><!-- /.tab-pane -->

        @include('store-backend::commodity.includes.album')

        <div class="tab-pane" id="tab_6">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">SEO关键词：</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="keywords" value="{{$goods_info->keywords}}"
                               placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">SEO描述：</label>
                    <div class="col-sm-10">
                            <textarea class="form-control" name="description"
                                      placeholder="">{{$goods_info->description}}</textarea>

                    </div>
                </div>
            </div>

            <div class="app-actions">
                <a data-id="6" data-action="next" class="btn btn-success app-action-prev">«上一步</a>
                <a data-id="8" data-action="next" class="btn btn-success app-action">下一步»</a>
                <input type="submit" class="btn btn-success app-action-save" data-toggle="form-submit" data-target="#base-form"
                       value="保存">
            </div>
        </div><!-- /.tab-pane -->

        <div class="popup">
            <div class="sortContainer">
                <h2 class="sortTitle">销售属性排序</h2>
                <p>销售属性支持排序，您可以直接通过拖拽调节顺序</p>
                <div class="sortMain">
                    <ul id="sort">

                    </ul>
                </div>

                <div class="btn-box">
                    <button class="btn btn-cancle sortCancle pull-right">取消</button>
                    <button class="btn btn-success sortOK pull-right">确认</button>
                </div>
            </div>
        </div>

        @include('UEditor::head')

        {!! Html::script(env("APP_URL").'/vendor/libs/jquery.form.min.js') !!}
        {!! Html::script(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/webuploader.js') !!}
        {!! Html::script(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.js') !!}

        <script>
            var postImgUrl = '{{route('upload.image',['_token'=>csrf_token()])}}';
            // 初始化Web Uploader
            $(document).ready(function () {
                var uploader2 = WebUploader.create({
                    auto: true,
                    swf: '{{url(env("APP_URL").'/assets/backend/libs/webuploader-0.1.5/Uploader.swf')}}',
                    server: '{{route('file.upload',['strategy'=>'video','_token'=>csrf_token()])}}',
                    pick: '#videoPicker',
                    fileVal: 'file',
                    accept: {
                        title: 'Video',
                        extensions: 'mp4,wmv,avi,mpg,rmvb,m4v'
                    }
                });

                // 文件上传成功，给item添加成功class, 用样式标记上传成功。
                uploader2.on('uploadSuccess', function (file, response) {
                    $('input[name="extra[video][url]"]').val(response.url);
                    $('#videoOriginName').text(response.original_name);
                });

                //删除图片
                $('.cancel').on('click', function () {
                    var parent = $(this).parent();
                    parent.remove();
                });

            });

            $(function () {
                $('#inputGoodsTags').tagator({
                    autocomplete: ['标签提示1', '标签提示2', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth']
                });

                $('#filePicker').children().eq(0).css({'width': '90px', 'height': '30px'});
            });

        </script>

@include('store-backend::commodity.script')
