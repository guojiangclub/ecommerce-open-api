
    <select class="form-control" name="goods_id">
        @foreach($goods as $k=>$item)
            @if($k==0)
                <option value="0">——————请选择要评论的商品——————</option>
            @endif
            <option value="{{$item->id}}">{{$item->name}}</option>
        @endforeach
    </select>