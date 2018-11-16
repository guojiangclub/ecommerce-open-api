<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>登录名</th>
        <th>昵称</th>
        <th>手机</th>
        <th>金额</th>
        <th>备注</th>
        <th>创建日期</th>
    </tr>
    </thead>
    <tbody class="page-point-list">
            @if(count($balances)>0)
                @foreach($balances as $item)
                <tr>
                        <td>{{isset($item->user->name)?$item->user->name:''}}</td>
                        <td>{{isset($item->user->nick_name)?$item->user->nick_name:''}}</td>
                        <td>{{isset($item->user->mobile)?$item->user->mobile:''}}</td>
                        <td>{{$item->value/100}}</td>
                        <td>{{$item->note}}</td>
                        <td>{{$item->created_at}}</td>
                </tr>
                @endforeach
           @endif

    </tbody>
</table>