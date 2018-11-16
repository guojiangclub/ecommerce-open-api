<table class="table table-hover table-striped">
    <tbody>

    <tr>
        <th>描述</th>
        <th>积分值</th>
    </tr>
    @foreach($orderPoint as $item)
        <tr>

            <td>{{$item->note}}</td>
            <td>{{$item->value}}</td>
        </tr>
    @endforeach

    @foreach($orderConsumePoint as $item)
        <tr>
            <td>{{$item->note}}</td>
            <td>{{$item->value}}</td>
        </tr>
    @endforeach

    </tbody>
</table>