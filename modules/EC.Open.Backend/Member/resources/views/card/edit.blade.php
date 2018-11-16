{{--@extends ('member-backend::layout')--}}

{{--@section ('title','会员卡管理')--}}

{{--@section ('breadcrumbs')--}}

    {{--<h2>编辑会员卡</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li >{!! link_to_route('admin.card.index', '会员卡管理') !!}</li>--}}
        {{--<li class="active"><a href="#">编辑会员卡</a> </li>--}}
    {{--</ol>--}}

{{--@stop--}}
{{--@section('content')--}}


    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
                {!! Form::open( [ 'url' => [route('admin.card.update', ['id' => $card->id])], 'method' => 'POST','id' => 'base-form','class'=>'form-horizontal'] ) !!}


            <div class="form-group">
                {!! Form::label('type', 'UID', ['class' => 'col-lg-2 control-label']) !!}

                <div class="col-lg-10">
                   {{$card->user_id}}
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('type', 'VIP_CODE_IN_TTPOS', ['class' => 'col-lg-2 control-label']) !!}

                <div class="col-lg-10">
                    @if($codes = $card->codes)
                        @foreach($codes as $val)
                            {{$val->vip_code_in_ttpos}},
                        @endforeach
                    @endif
                </div>
            </div><!--form control-->

            <div class="form-group">
                {!! Form::label('type', '卡号', ['class' => 'col-lg-2 control-label']) !!}

                <div class="col-lg-10">
                    {!! Form::text('number', $card->number, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->
            <div class="form-group">
                {!! Form::label('name', '姓名', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('name', $card->name, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->
            <div class="form-group">
                {!! Form::label('mobile', '手机号码', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('mobile', $card->mobile, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->
            <div class="form-group">
                {!! Form::label('sort', '生日', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('birthday',$card->birthday, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div><!--form control-->
            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <input type="submit" class="btn btn-success" value="保存"/>
                    <a href="{{route('admin.card.index')}}" class="btn btn-danger">取消</a>

                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
{{--@endsection--}}

{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    <script>

        $('#base-form').ajaxForm({
            success: function (result) {

                if(result.status)
                {
                    swal({
                        title: "编辑成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location = '{{route('admin.card.index')}}';
                    });
                } else{
                  swal('编辑失败','','error');
                }
            }
        });
    </script>


{{--@stop--}}