    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'url' => [route('brand.store')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
            <input type="hidden" name="id" value="">
            <div class="form-group">
                {!! Form::label('name','品牌名称：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <input type="text" class="form-control" name="name" placeholder="" required>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','品牌网址：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <input type="text" class="form-control" name="url" id="url" placeholder="" required>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','品牌LOGO：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <input type="hidden" name="logo" value=""/>
                    <img class="banner-image" src="">
                    <div id="filePicker">选择图片</div>

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','品牌描述：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <textarea class="form-control" name="description"></textarea>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','排序：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <input type="text" class="form-control" id="sort" name="sort" placeholder="">
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('name','是否显示：', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <div class="radio">
                        <label>
                            <input type="radio" name="is_show" id="is_show" value="1" checked="">
                            是
                        </label>
                        <label>
                            <input type="radio" name="is_show" id="is_show" value="0">
                            否
                        </label>
                    </div>
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
    {!! Html::script(env("APP_URL").'/vendor/libs/webuploader-0.1.5/webuploader.js') !!}
    @include('store-backend::brand.script')

