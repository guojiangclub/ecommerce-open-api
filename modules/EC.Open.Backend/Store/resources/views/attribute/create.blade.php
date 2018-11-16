    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">

            {!! Form::open( [ 'url' => [route('admin.goods.attribute.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}

            <div class="form-group">
                {!! Form::label('name','参数名称：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <input type="text" class="form-control" name="name" placeholder="" required>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('type','操作方式：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <select onchange="changeValueBox(this, 1)" class="form-control" name="type">
                        <option value="2">输入框</option>
                        <option value="1">下拉框</option>
                    </select>
                    <br>
                    <div id="value_box_1">
                        <input type="hidden" value="" name="value">
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('is_search','是否作为筛选项：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                   <input value="1" type="checkbox" name="is_search">
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('is_chart','是否作为图表显示：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <input value="1" type="checkbox" name="is_chart">
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8 controls">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>


    {!! Html::script('vendor/libs/jquery.form.min.js') !!}
    @include('store-backend::attribute.script')