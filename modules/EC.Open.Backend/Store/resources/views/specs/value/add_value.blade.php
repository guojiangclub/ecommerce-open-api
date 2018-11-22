@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    添加规格值
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="row">
        {!! Form::open( [ 'route' => ['admin.goods.spec.value.store'], 'method' => 'POST', 'id' => 'spec-value-form','class'=>'form-horizontal'] ) !!}

        <input type="hidden" name="spec_id" value="{{$spec_id}}">
        <div class="col-md-12 clearfix">
            <div class="form-group">
                <table class='border_table table table-bordered'>
                    <thead>
                    <tr>
                        <th>规格值</th>
                        @if($spec_id == 2)
                            <th>颜色值</th>
                        @endif
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id='spec_box'>
                    <tr class="td_c">
                        <td><input type="text" class="form-control" name="add_value[0][name]"></td>
                        @if($spec_id==2)
                            <td id="rgb_0">
                            </td>
                        @endif
                        <td><a href="javascript:;" class="btn btn-xs btn-primary operatorPhy">
                                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top"
                                   data-original-title="删除"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <button id="specAddButton" type="button" class="btn btn-w-m btn-primary">继续添加</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@stop



@section('footer')

    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
    {!! Html::script(env("APP_URL").'/vendor/libs/jscolor.js') !!}

    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="submit" class="ladda-button btn btn-primary" data-style="slide-right" data-toggle="form-submit"
            data-target="#spec-value-form">保存
    </button>

    @include('store-backend::specs.value.script')

@stop






