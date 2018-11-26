<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>昵称</th>
        <th>电话</th>
        <th>积分</th>
        <th class="visible-lg">注册时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{!! $user->id !!}</td>
            <td>{!! $user->nick_name !!}</td>
            <td>{!! $user->mobile !!}</td>
            <td>{!! $user->available_integral !!}</td>

            <td class="visible-lg">{!! $user->created_at !!}</td>

            <td>
                {!! $user->action_buttons !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>