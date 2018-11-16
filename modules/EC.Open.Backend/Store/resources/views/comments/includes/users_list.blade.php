
    <select class="form-control" name="user_id">
        @foreach($users as $k=>$item)
            @if($k==0)
                <option value="0">——————请选择评论用户——————</option>
            @endif
            <option value="{{$item->id}}">{{$item->nick_name}}  {{$item->mobile}}</option>
        @endforeach
    </select>