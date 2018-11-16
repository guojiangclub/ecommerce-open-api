
<table class="table table-hover table-striped">
    <tbody>

    <tr>
        <th>商品名称</th>
        <th>评价分数</th>
        <th>评价内容</th>
        <th>评价图片</th>
    </tr>
        @foreach($order->comments as $item)
            <tr>
                <td>
                    <img width="50" height="50"  src="{{$item->orderItem->item_info['image']}}" >&nbsp;&nbsp;&nbsp;&nbsp;
                    {{$item->orderItem->item_name}}</td>

                <td>{{$item->point}} 星</td>
                <td>{{$item->contents}}</td>
                <td>{!! $item->CommentPic !!}</td>
            </tr>
        @endforeach

    </tbody>
</table>