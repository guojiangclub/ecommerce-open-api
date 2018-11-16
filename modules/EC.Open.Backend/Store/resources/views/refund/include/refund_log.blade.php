<div class="table-responsive">
    <table class="table table-bordered table-stripped">
        <thead>
        <tr>
            <th>
                时间
            </th>
            <th>
                操作人
            </th>
            <th>
                动作
            </th>
            <th>
                备注
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($refund->refundLog as $item)
        <tr>
            <td>
                {{$item->created_at}}
            </td>
            <td>
                {{$item->operator_text}}
            </td>
            <td>
                {{$item->ActionText}}
            </td>
            <td>
                {{$item->note}}<br>{{$item->remark}}
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>