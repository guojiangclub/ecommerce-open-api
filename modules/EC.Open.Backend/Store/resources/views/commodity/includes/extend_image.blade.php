<div class="form-group">
    <label class="col-sm-2 control-label">商品简介：</label>
    <div class="col-sm-10">
        @if(isset($goods_info))
            <textarea class="form-control" rows="4" name="extend_description">{!! $goods_info->extend_description !!}</textarea>
        @else
            <textarea class="form-control" rows="4" name="extend_description"></textarea>
        @endif
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">商品图片：</label>
    <div class="col-sm-10">
        <div class="col-sm-3">
            <p class="text-center">模特图（大图）建议尺寸：1374*1080</p>
            <p class="extend_image text-center">
                @if(isset($goods_info) AND $goods_info->extend_image AND $goods_info->extend_image['big'])
                    <img title="点击上传图片" class="upload_extend" src="{{$goods_info->extend_image['big']}}">
                    <input type="hidden" name="extend_image[big]" value="{{$goods_info->extend_image['big']}}">
                @else
                    <img title="点击上传图片" class="upload_extend" src="/assets/backend/images/no-image.jpg">
                    <input type="hidden" name="extend_image[big]">
                @endif
            </p>
            {{--<div class="text-center">
                <div class="btn btn-xs btn-primary upload_extend">选择图片</div>
            </div>--}}
        </div>

        <div class="col-sm-3">
            <p class="text-center">产品图1（上） 建议尺寸：360*400</p>
            <p class="extend_image text-center">
                @if(isset($goods_info) AND $goods_info->extend_image AND $goods_info->extend_image['top'])
                    <img class="upload_extend" src="{{$goods_info->extend_image['top']}}">
                    <input type="hidden" name="extend_image[top]" value="{{$goods_info->extend_image['top']}}">
                @else
                    <img class="upload_extend" src="/assets/backend/images/no-image.jpg">
                    <input type="hidden" name="extend_image[top]">
                @endif
            </p>

        </div>

        <div class="col-sm-3">
            <p class="text-center">产品图2（中） 建议尺寸：360*400</p>
            <p class="extend_image text-center">
                @if(isset($goods_info) AND $goods_info->extend_image AND $goods_info->extend_image['middle'])
                    <img class="upload_extend" src="{{$goods_info->extend_image['middle']}}">
                    <input type="hidden" name="extend_image[middle]" value="{{$goods_info->extend_image['middle']}}">
                @else
                    <img class="upload_extend" src="/assets/backend/images/no-image.jpg">
                    <input type="hidden" name="extend_image[middle]">
                @endif
            </p>

        </div>

        <div class="col-sm-3">
            <p class="text-center">产品图3（下） 建议尺寸：360*400</p>
            <p class="extend_image text-center">
                @if(isset($goods_info) AND $goods_info->extend_image AND $goods_info->extend_image['bottom'])
                    <img class="upload_extend" src="{{$goods_info->extend_image['bottom']}}">
                    <input type="hidden" name="extend_image[bottom]" value="{{$goods_info->extend_image['bottom']}}">
                @else
                    <img class="upload_extend" src="/assets/backend/images/no-image.jpg">
                    <input type="hidden" name="extend_image[bottom]">
                @endif
            </p>
        </div>
    </div>
</div>