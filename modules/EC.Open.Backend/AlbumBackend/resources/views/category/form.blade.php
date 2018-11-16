<div class="form-group">
    {!! Form::label('name','分类名称：', ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name','上级分类：', ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-9">
        <div class="col-lg-9" style="padding-left: 0">
            <select class="form-control m-b " name="parent_id">
                <option value="0">-无-</option>
                @foreach($categories as $key => $val)
                    @if($category->id != $val->id AND $val->level<3 AND $val->id!==1)
                        <?php
                        if (isset($parent_id) AND !empty($parent_id)) {
                            $select = $parent_id == $val->id ? 'selected' : '';
                        } else {
                            $select = $category->parent_id == $val->id ? 'selected' : '';
                        }
                        ?>
                        <option {{$select}} value="{{$val->id}}">{{ $val->html }} @if($val->parent_id !=0 )
                                ﹂@endif {{ $val->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <label class="col-lg-3">顶级分类请选择无</label>
    </div>
</div>

<div class="form-group">
    {!! Form::label('sort','排序：', ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-9">
        {!! Form::text('sort', !is_null($category->sort)? $category->sort:99, ['class' => 'form-control', 'placeholder' => '0']) !!}
    </div>
</div>