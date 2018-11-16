{{--@extends('store-backend::dashboard')

@section ('title','新人进店礼')


@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--@stop

@section ('breadcrumbs')
    <h2>编辑新人进店礼</h2>
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
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 编辑新人进店礼（{{$gift_new_user->name}}）</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                        {!! Form::open( [ 'url' => [route('admin.promotion.gift.new.user.api.update',['id'=>$gift_new_user->id])], 'method' => 'POST', 'id' => 'create-suit-form','class'=>'form-horizontal'] ) !!}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">*新人进店礼名称:</label>
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" name="type" value="gift_new_user"/>
                                <input type="text" class="form-control" name="name" placeholder="" required="required"  value="{{$gift_new_user->name}}"    />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">*新人进店礼标题(前端显示):</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="title" placeholder=""  required="required" value="{{$gift_new_user->title}}"  />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">*活动有效时间：</label>
                            <div class="col-sm-3">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>
                                    <input type="text" class="form-control inline" name="starts_at" value="{{$gift_new_user->starts_at}}"
                                           placeholder="点击选择开始时间" readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>
                                    <input type="text" class="form-control" name="ends_at"
                                           value="{{$gift_new_user->ends_at}}" placeholder="点击选择结束时间" readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group" style="display:none;">
                            <label class="col-sm-2 control-label">赠送优惠券：</label>
                            <div class="col-sm-10">
                                <label class="checkbox-inline i-checks"><input name="open_coupon" type="radio"
                                                                               value="1"
                                                                               @if($gift_new_user->open_coupon==1)
                                                                               checked
                                                                               @endif

                                    >
                                    是</label>
                                <label class="checkbox-inline i-checks"><input name="open_coupon" type="radio"
                                                                               value="0"

                                                                               @if($gift_new_user->open_coupon==0)
                                                                               checked
                                            @endif
                                    >否</label>
                            </div>
                        </div>

                        <div class="form-group" style="display: none">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="coupon_title" name="coupon_title" placeholder="请输入进行中的优惠券名称搜索"  />
                            </div>
                            <div class="col-sm-2">
                                <a href="javascript:;" class="btn btn-w-m btn-info" id="search" >搜索</a>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-sm-2 control-label">*赠送优惠券：</label>
                            <div class="col-sm-4">
                                <select class="form-control select_coupon" name="coupon[]" >
                                    <option id="option_coupon" value="">请选择优惠券</option>
                                </select>
                            </div>
                        </div>



                        <div class="form-group select_coupon_box" id="select_coupon_box_1">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <select class="form-control select_coupon" name="coupon[]" >
                                    <option value="">选择优惠券</option>

                                </select>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>

                        <div class="form-group select_coupon_box" id="select_coupon_box_2" >
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <select class="form-control  select_coupon" name="coupon[]" id="select_coupon" >
                                    <option value="">选择优惠券</option>
                                </select>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>

                        <div class="form-group select_coupon_box" id="select_coupon_box_3" >
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <select class="form-control  select_coupon" name="coupon[]" id="select_coupon" >
                                    <option value="">选择优惠券</option>
                                </select>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>


                        <div class="form-group select_coupon_box" id="select_coupon_box_4">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <select class="form-control  select_coupon" name="coupon[]" id="select_coupon" >
                                    <option value="">选择优惠券</option>
                                </select>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>


                        <div class="form-group" style="display: none">
                            <label class="col-sm-2 control-label">赠送积分：</label>
                            <div class="col-sm-10">
                                <label class="checkbox-inline i-checks"><input name="open_point" type="radio"
                                                                               value="1" checked>
                                    是</label>
                                <label class="checkbox-inline i-checks"><input name="open_point" type="radio"
                                                                               value="0" > 否</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">赠送积分：</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control num" name="point" placeholder="输入赠送积分数目" value="{{$gift_new_user->point}}" />
                            </div>
                        </div>



                        <div class="form-group" style="display: none">
                            <label class="col-sm-2 control-label">*开启状态：</label>
                            <div class="col-sm-10">
                                <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                               value="1"
                                                                               @if($gift_new_user->status==1)
                                                                                checked
                                                                                @endif

                                    >
                                    是</label>
                                <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                               value="0"
                                                                               @if($gift_new_user->status==0)
                                                                               checked
                                            @endif
                                    > 否</label>
                            </div>
                        </div>



                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">保存</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
{{--@stop

@section('after-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
{{--    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    <script>
        $('.form_datetime').datetimepicker({
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            minuteStep: 1
        });
    </script>
    <script>
        var coupons = JSON.parse("{{$coupon}}");
        var coupon_api="{{route('admin.promotion.gift.new.user.api.coupon',['status'=>'ing'])}}"
    </script>
    <script>
        $('.num').bind('input propertychange', function (e) {
            var value = $(e.target).val()
            if (!/^[-]?[0-9]*\.?[0-9]+(eE?[0-9]+)?$/.test(value)) {
                value = value.replace(/[^\d.].*$/, '');
                $(e.target).val(value);
            }
        })


        $('#search').click(function () {
            var url=coupon_api;
            var coupon_title=$('#coupon_title').val();
            if(coupon_title!=='') url=url+"&"+'title='+coupon_title;
            $('.select_coupon').html('');
            $.get(url,function (res) {
                if(res.status){
                    var data=res.data;
                    var html="<option value=''>"+"请选择优惠券"+"</option>";
                    if(data.length>0){
                        $.each(data,function (k,v) {
                             html+="<option value="+v.id+"  >"+v.title+"</option>";
                        })
                    }else{
                        var html="<option value=''>"+"无进行中的优惠券"+"</option>";
                    }

                    $('.select_coupon').html(html);

                    $(function () {
                        console.log(coupons);
                        $('.select_coupon').each(function (i,e) {
                          $(e).val(coupons[i]);
                        })
                    })
                }
            })
        });
        $('#search').trigger("click");


        $('#create-suit-form').ajaxForm({
            beforeSubmit:function (data) {
                var input = [];
                $.each(data, function (k, v) {
                    if (v.name !== "lenght") {
                        input[v.name] = v.value;
                    }
                })
                if (input['starts_at']>=input['ends_at']) {
                    swal({ title: "保存失败", text: "开始时间必须小于结束时间",type: "error"  });
                    return false;
                }
                if (input['open_coupon']==1){
                    var arr = [];
                    $('.select_coupon').each(function (i,e) {
                        if ($(e).val() != '') {
                            arr.push($(e).val())
                        }
                    })
                    if(arr.length<=0){
                        swal({
                            title: "保存失败",
                            text: "请选择优惠券",
                            type: "error"
                        });
                        return false;
                    }
                }

            },

            success: function (result) {
                if(!result.status)
                {
                    swal("保存失败!", result.message, "error")
                }else{
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location = '{{route('admin.promotion.gift.new.user.index')}}';
                    });
                }

            }
        });
    </script>
{{--@stop--}}