@if(count($integral)>0)
    <table class="table table-striped table-bordered table-hover table-responsive">
        <thead>
        <tr>
            <th>时间</th>
            <th>积分数扣减</th>
            <th>积分数增加</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
        @foreach($integral as $int_user)
            <tr>
                <td>{{$int_user->created_at}}</td>
                <td>{{$int_user->integral<0?$int_user->integral:'/'}}</td>
                <td>{{$int_user->integral>0?$int_user->integral:'/'}}</td>
                <td>{{$int_user->note}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pull-left">
        共&nbsp;{!! $integral->total() !!} 条记录
    </div>

@else
    <div>
        &nbsp;&nbsp;&nbsp;当前无数据
    </div>
@endif








