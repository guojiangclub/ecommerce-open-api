<table class="table table-bordered table-striped dataTable" >
{{--@foreach($model->attribute as $item)--}}
    @foreach($attribute as $item)
    <tr>
        <th>{{$item->name}}</th>
        <td>
            @if($item->type == 1)
                <select class="form-control" name="attr_id_{{$item->id}}">
                    <option value="0">请选择</option>
                    @foreach($item->values  as $val)
                        <option value="{{$val->id}}">{{$val->name}}</option>
                    @endforeach
                </select>
            @else
                <input type="text" name="attr_id_{{$item->id}}" class="form-control"/>
            @endif
        </td>
    </tr>
@endforeach
</table>
