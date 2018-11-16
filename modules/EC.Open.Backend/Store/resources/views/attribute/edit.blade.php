
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

            {!! Form::open( [ 'url' => [route('admin.goods.attribute.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}

            <div class="form-group">
                {!! Form::label('name','参数名称：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <input type="text" class="form-control" name="name" value="{{$attribute->name}}" required>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('type','操作方式：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <select class="form-control" disabled>
                        <option value="2" {{$attribute->type == 2 ? 'selected':''}}>输入框</option>
                        <option value="1" {{$attribute->type == 1 ? 'selected':''}}>下拉框</option>
                    </select>
                    <br>
                    <div id="value_box_1">
                        @if($attribute->type == 1)
                            <button onclick="addValue(1)" type="button" class="btn btn-w-m btn-primary">添加参数值</button>
                            <div id="value_1">
                                @foreach($attribute->values as $key => $item)
                                    <div class="form-group">
                                        <div class="col-sm-10">
                                            <input type="hidden" value="{{$item->id}}" name="_attr_value_id[{{$key}}]">
                                            <input class="form-control" value="{{$item->name}}" name="_attr_value[value][{{$key}}]" type="text">
                                        </div>
                                        <div class="col-sm-2"><label class="control-label">
                                                <a href="javascript:;" onclick="delValue(this)"
                                                   data-url="{{route('admin.goods.model.deleteAttrValue',['id' =>$item->id])}}"
                                                   class="btn btn-xs btn-primary"><i class="fa fa-trash"
                                                                                     data-toggle="tooltip"
                                                                                     data-placement="top"
                                                                                     data-original-title="删除"></i></a></label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('is_search','是否作为筛选项：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <input type="checkbox" name="is_search" value="1" {{$attribute->is_search ? 'checked':''}}>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('is_chart','是否作为图表显示：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <input type="checkbox" name="is_chart" value="1" {{$attribute->is_chart ? 'checked':''}}>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8 controls">
                    <input type="hidden" name="id" value="{{$attribute->id}}">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

{{--@endsection

@section('before-scripts-end')--}}
    {!! Html::script('vendor/libs/jquery.form.min.js') !!}
    @include('store-backend::attribute.script')
{{--@stop--}}
