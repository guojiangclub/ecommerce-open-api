<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>员工号</th>
        <th>员工姓名</th>
        <th>邮箱</th>
        <th>电话号码 </th>
        <th>入职时间</th>
        <th>LocationType</th>
        <th>状态</th>

        <th class="visible-lg">激活时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($staff as $value)
        <tr>
            <td>{!! $value->id !!}</td>
            <td>{!! $value->staff_id !!}</td>
            <td>{!! $value->name !!}</td>
            <td>{!! link_to("mailto:".$value->email, $value->email) !!}</td>
            <td>{!! $value->mobile !!}</td>
            <td>{!! $value->hiredate_at !!}</td>
            <td>{!! $value->locationType !!}</td>
            <td>@if($value->active_status==1)在职@else 离职 @endif</td>
            <td class="visible-lg"> @if($value->activate_at!=''){!! $value->activate_at !!}@else <span style="color: #9ea6b9;">暂未激活</span>@endif</td>
            <td>
                <a target="_blank"
                   class="btn btn-xs btn-primary"
                   href="{{route('admin.staff.edit',['id'=>$value->id])}}">
                    <i data-toggle="tooltip" data-placement="top"
                       class="fa fa-pencil-square-o"
                       title="编辑"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>