{{--@extends('store-backend::dashboard')

@section ('title','定向发券活动')


@section('after-styles-end')--}}
{!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
<style>
    #alert-box {
        position: fixed;
        top: 0;
        bottom: 0;
        width: 100%;
        background-color: rgba(0, 0, 0, .4);
        z-index: 9999 !important;
    }

    #alert-box i {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #ffffff;
    }
</style>
{{--@stop

@section ('breadcrumbs')
    <h2>定向发券活动</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
    </ol>
@stop



@section('content')--}}
<div class="tabs-container">
    @if (session()->has('flash_notification.message'))
        <div class="alert alert-{{ session('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {!! session('flash_notification.message') !!}
        </div>
    @endif

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 定向发券活动</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                <div class="row">
                    {!! Form::open( [ 'url' => [route('admin.promotion.directional.searchUser')], 'method' => 'POST', 'id' => 'create-directional-coupon-form','class'=>'form-horizontal'] ) !!}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">*活动名称:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{$gift->name}}" name="name" placeholder=""/>
                        </div>
                    </div>


                    @if($gift->directional_type=='mobile')
                        <div class="form-group" style="">
                            <label class="col-sm-2 control-label">*人群：</label>
                            <div class="col-sm-10">
                                <label class="checkbox-inline i-checks"><input id="for_phone" name="directional_type"
                                                                               type="radio"
                                                                               value="mobile"
                                                                               @if($gift->directional_type=='mobile')
                                                                               checked
                                            @endif

                                    >
                                    注册手机号</label>
                                <br>
                                <br>

                                <div id="box_1"

                                >
                                    <div class="col-sm-6">
                                        <textarea class="form-control" rows="8"
                                                  name="mobile">{{$gift->mobile}}</textarea>
                                    </div>
                                    <div class="col-sm-4">
                                        一次最多支持200个手机号，多个手机号用#分隔.
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif



                    @if($gift->directional_type=='custom')


                        @if($gift->directional_type=='custom'
                         And empty($gift->n_day_buy)
                          And empty($gift->n_day_no_buy)
                           And empty($gift->buy_num_above)
                            And empty($gift->buy_num_below)
                             And empty($gift->buy_price_above)
                              And empty($gift->buy_price_below)
                               And empty($gift->group_id)
                         )
                            <div class="form-group" style="">
                                <label class="col-sm-2 control-label">*人群：</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline i-checks"><input id="custom_radio"
                                                                                   name="directional_type" type="radio"
                                                                                   value="custom"
                                                                                   @if($gift->directional_type=='custom')
                                                                                   checked
                                                @endif
                                        >
                                        注册用户群发</label>
                                    <br>
                                    <br>

                                </div>
                            </div>

                        @else
                            <div class="form-group" style="">
                                <label class="col-sm-2 control-label">*人群：</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline i-checks"><input id="custom_radio"
                                                                                   name="directional_type" type="radio"
                                                                                   value="custom"
                                                                                   @if($gift->directional_type=='custom')
                                                                                   checked
                                                @endif
                                        >
                                        自定义</label>
                                    <br>
                                    <br>
                                    <div id="box_2"
                                    >
                                        @if(count($groups)>0)
                                            <div class="col-sm-4">
                                                <select class="form-control" name="">
                                                    <option value="">会员等级</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="group_id">
                                                    <option value="">请选择会员等级</option>
                                                    @foreach($groups as $item)
                                                        <option

                                                                @if($item->id==$gift->group_id)
                                                                selected
                                                                @endif
                                                                value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <br>
                                            <br>
                                            <br>
                                        @else
                                            <input type="hidden" name="group_id" value="">
                                        @endif


                                        <div class="col-sm-4">
                                            <select class="form-control" name="">
                                                <option value="">N天内有购买</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control num" name="n_day_buy"
                                                       placeholder="天数" value="{{$gift->n_day_buy}}"/>
                                            </div>

                                        </div>
                                        <br>
                                        <br>
                                        <br>


                                        <div class="col-sm-4">
                                            <select class="form-control" name="">
                                                <option value="">N天内有无购买</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control num" name="n_day_no_buy"
                                                       placeholder="天数" value="{{$gift->n_day_no_buy}}"/>
                                            </div>

                                        </div>
                                        <br>
                                        <br>
                                        <br>


                                        <div class="col-sm-4">
                                            <select class="form-control" name="">
                                                <option value="">购买订单价格大于等于</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control num" name="buy_price_above"
                                                       placeholder="元" value="{{$gift->buy_price_above}}"/>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <br>


                                        <div class="col-sm-4">
                                            <select class="form-control" name="">
                                                <option value="">购买订单价格小于等于</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control num" name="buy_price_below"
                                                       placeholder="元" value="{{$gift->buy_price_below}}"/>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <br>


                                        <div class="col-sm-4">
                                            <select class="form-control" name="">
                                                <option value="">累计购物订单次数大于等于</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control num" name="buy_num_above"
                                                   placeholder="次数" value="{{$gift->buy_num_above}}"/>
                                        </div>
                                        <br>
                                        <br>
                                        <br>


                                        <div class="col-sm-4">
                                            <select class="form-control" name="">
                                                <option value="">累计购物订单次数小于等于</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control num" name="buy_num_below"
                                                   placeholder="次数" value="{{$gift->buy_num_below}}"/>
                                        </div>
                                        <br>
                                        <br>
                                        <br>

                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="form-group">
                        <label class="col-sm-2 control-label">*赠送优惠券:</label>
                        <div class="col-sm-8">
                            <a href="{{route('admin.promotion.coupon.edit',$gift->coupon->id)}}" target="_blank">
                                {{ $gift->coupon->title }}
                            </a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*发送人数:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="number" value="{{$gift->number}}"
                                   placeholder=""/>
                        </div>
                    </div>

                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


{{--@stop--}}

<div id="alert-box" style="display: none">
    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
</div>

{{--@section('after-scripts-end')--}}
{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
{{--    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}

{{--@stop--}}