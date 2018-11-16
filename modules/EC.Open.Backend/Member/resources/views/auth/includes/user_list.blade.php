<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>邮箱</th>
        <th>会员已确认</th>
        <th>角色</th>
        <th class="visible-lg">创建时间</th>
        <th class="visible-lg">更新时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{!! $user->id !!}</td>
            <td>{!! $user->name !!}</td>
            <td>{!! link_to("mailto:".$user->email, $user->email) !!}</td>
            <td>{!! $user->confirmed_label !!}</td>
            <td>
                @if ($user->roles()->count() > 0)
                    @foreach ($user->roles as $role)
                        {!! $role->name !!}<br/>
                    @endforeach
                @else
                    None
            @endif
            <td class="visible-lg">{!! $user->created_at->diffForHumans() !!}</td>
            <td class="visible-lg">{!! $user->updated_at->diffForHumans() !!}</td>
            <td>{!! $user->action_buttons !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>