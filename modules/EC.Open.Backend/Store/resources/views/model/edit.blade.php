    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('admin.goods.model.store')], 'method' => 'POST', 'id' => 'base-form','class'=>'form-horizontal'] ) !!}
            <input type="hidden" name="id" id="id" value="{{$model->id}}">
            <div class="form-group">
                {!! Form::label('name','模型名称：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    <input type="text" class="form-control" name="name" value="{{$model->name}}" placeholder=""
                           required>
                </div>
            </div>

            <div class="form-group specCheckbox">
                {!! Form::label('spec','关联规格：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9" id="spec_box">
                    @foreach( $spec as $item)
                        <input type="checkbox" name="spec_ids[]" value="{{$item->id}}" {{$goodsCount>0?'disabled':''}}
                        data-url="{{route('admin.goods.model.checkSpec',['id' =>$item->id,'model_id'=>$model->id])}}"
                                {{in_array($item->id, $model->spec_ids) ? 'checked':''}}> {{$item->display_name}} &nbsp;
                        &nbsp;
                    @endforeach
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('spec','关联商品参数：', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-9">
                    @foreach( $attributes as $item)

                        <input type="checkbox" name="attr_ids[]" value="{{$item->id}}"
                                {{in_array($item->id, $attrIds) ? 'checked':''}}> {{$item->name}} &nbsp;&nbsp;
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


    {!! Html::script(env("APP_URL").'/vendor/libs/jquery.form.min.js') !!}
    @include('store-backend::model.script')