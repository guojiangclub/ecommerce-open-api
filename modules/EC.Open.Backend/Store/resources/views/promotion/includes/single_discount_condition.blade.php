@foreach($data as $key => $val)
    <tr class="showData">
        <td><input type="text" name="_conditionValue[]" class="form-control" value="{{$val->name}}"></td>
        <td>
            <div class="i-checks"><label> <input type="radio" name="type[{{$key}}][]"
                                                 class="type" {{isset($val->value['type_cash'])?'checked':''}}> 销售价
                    <input type="text" name="type_cash[]" class="form-control money"
                           value="{{isset($val->value['type_cash'])?$val->value['type_cash']:''}}"> 元 </label></div>
            <div class="i-checks"><label> <input type="radio" name="type[{{$key}}][]"
                                                 class="type" {{isset($val->value['type_discount'])?'checked':''}}> 打
                    <input type="text" name="type_discount[]" class="form-control money"
                           value="{{isset($val->value['type_discount'])?$val->value['type_discount']:''}}"> 折 </label>
            </div>
        </td>
        <td>
            <input type="hidden" value="{{$val->name}}">
            <a href="javascript:;" onclick="deltr(this)">删除</a>
        </td>
    </tr>

@endforeach