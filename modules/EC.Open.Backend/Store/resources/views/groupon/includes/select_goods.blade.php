@foreach($goods as $key=>$item)
    <tr data-key="{{$num+$key}}">
        <td>
            <input type="hidden" name="goods_name[{{$num+$key}}]" value="{{$item->name}}">
            <input type="hidden" name="item[{{$num+$key}}][goods_id]" value="{{$item->id}}">
            <img src="{{$item->img}}" width="50">
            销售价：{{$item->GoodsSectionSellPrice}}
            <br>
            <a href="{{route('admin.goods.edit',['id'=>$item->id])}}" target="_blank">
                {{$item->name}}
            </a>
        </td>
        <td><input type="text" class="form-control" name="item[{{$num+$key}}][groupon_price]"></td>
        <td><input type="text" class="form-control" name="item[{{$num+$key}}][limit]"></td>
        <td><input type="text" class="form-control" name="item[{{$num+$key}}][number]"></td>
        <td>
            <div class="input-group m-b rate_div">
                <input class="form-control" name="item[{{$num+$key}}][rate]" type="text">
                <span class="input-group-addon">%</span>
            </div>
        <td>
            <a>
                <i class="fa switch fa-toggle-on"
                   title="切换状态">
                    <input type="hidden" value="1" name="item[{{$num+$key}}][get_point]">
                </i>
            </a>

            {{--<input type="radio" value="1"--}}
            {{--name="item[{{$num+$key}}][get_point]" checked> 是--}}
            {{--&nbsp;&nbsp;--}}
            {{--<input type="radio" value="0"--}}
            {{--name="item[{{$num+$key}}][get_point]"> 否--}}
        </td>
        <td>
            <a>
                <i class="fa switch fa-toggle-on"
                   title="切换状态">
                    <input type="hidden" value="1" name="item[{{$num+$key}}][use_point]">
                </i>
            </a>

            {{--<input type="radio" value="1"--}}
            {{--name="item[{{$num+$key}}][use_point]" checked> 是--}}
            {{--&nbsp;&nbsp;--}}
            {{--<input type="radio" value="0"--}}
            {{--name="item[{{$num+$key}}][use_point]"> 否--}}
        </td>
        <td>
            <a>
                <i class="fa switch fa-toggle-on"
                   title="切换状态">
                    <input type="hidden" value="1" name="item[{{$num+$key}}][status]">
                </i>
            </a>

            {{--<input type="radio" value="1"--}}
            {{--name="item[{{$num+$key}}][status]" checked> 是--}}
            {{--&nbsp;&nbsp;--}}
            {{--<input type="radio" value="0"--}}
            {{--name="item[{{$num+$key}}][status]"> 否--}}
        </td>
        <td>
            <label class="block_banner img-plus">
                <input type="file" name="upload_image" accept="image/*">
            </label>
        </td>
        <td>
            <input type="text" value="0" class="form-control"
                   name="item[{{$num+$key}}][sell_num]">
            <small>当前销量：0</small>
        </td>
        <td>
            <input type="text" value="9" class="form-control"
                   name="item[{{$num+$key}}][sort]">
        </td>
        <td>
            <a class="btn btn-xs btn-danger" onclick="deleteSelect(this)" data-id="{{$item->id}}"
               href="javascript:;">
                <i data-toggle="tooltip" data-placement="top"
                   class="fa fa-trash"
                   title="删除"></i></a>
        </td>
    </tr>
@endforeach