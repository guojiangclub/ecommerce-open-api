<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;">
        @if($action=='edit')
            {!! Form::model($company_edit, ['route' => ['admin.shippingmethod.companyStore'], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST'
        ,'id'=>'base-form']) !!}
        @else
            {!! Form::open( [ 'url' => [route('admin.shippingmethod.companyStore')], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}
        @endif
        <input type="hidden" name="id" value="{{$company_edit->id}}"/>
        <div class="form-group">
            {!! Form::label('code', '物流公司代号', ['class' => 'col-lg-2 control-label']) !!}

            <div class="col-lg-10">
                {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
        </div><!--form control-->
        <div class="form-group">
            {!! Form::label('name', '物流公司全称', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
        </div><!--form control-->
        <div class="form-group">
            {!! Form::label('url', '物流公司网址', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                {!! Form::text('url', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
        </div><!--form control-->
        <div class="form-group">
            {!! Form::label('is_enabled', '状态', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                <input name="is_enabled" type="radio" value="1" {{$company_edit->is_enabled ? 'checked' : ''}} /> 启用
                <input name="is_enabled" type="radio" value="0" {{!$company_edit->is_enabled ? 'checked' : ''}} /> 禁用
            </div>
        </div><!--form control-->
        <div class="hr-line-dashed"></div>

        <div class="form-group">
            <label class="col-lg-2 control-label"></label>
            <div class="col-lg-10">
                <input type="submit" class="btn btn-success" value="保存"/>
                <a href="{{route('admin.shippingmethod.company')}}" class="btn btn-danger">取消</a>

            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>

@include('store-backend::shippingmethod.script')