    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('admin.goods.model.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}


            <div class="form-group">
                {!! Form::label('name','模型名称：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="name" placeholder="" required>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('spec','关联规格：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9" id="spec_box">
                    @foreach( $spec as $item)
                        <input type="checkbox" name="spec_ids[]"
                               value="{{$item->id}}"> {{$item->display_name}} &nbsp;&nbsp;
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('spec','关联商品参数：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    @foreach( $attributes as $item)
                        <input type="checkbox" name="attr_ids[]" value="{{$item->id}}"> {{$item->name}} &nbsp;&nbsp;
                    @endforeach
                </div>
            </div>


            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8 controls">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>

            {!! Form::close() !!}
                    <!-- /.tab-content -->
        </div>
    </div>


    {!! Html::script(env("APP_URL").'/vendor/libs/jquery.form.min.js') !!}
    @include('store-backend::model.script')
