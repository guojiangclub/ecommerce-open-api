{{--@extends ('member-backend::layout')--}}

{{--@section ('title','储值管理')--}}


{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--@stop--}}

{{--@section ('breadcrumbs')--}}
    {{--<h2>创建储值规则</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
    {{--</ol>--}}
{{--@stop--}}



{{--@section('content')--}}
    <div class="tabs-container">
        @if (session()->has('flash_notification.message'))
            <div class="alert alert-{{ session('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!! session('flash_notification.message') !!}
            </div>
        @endif

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 创建储值规则</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                            {!! Form::open( [ 'url' => [route('admin.users.recharge.store')], 'method' => 'POST', 'id' => 'create-suit-form','class'=>'form-horizontal'] ) !!}
                            <div class="form-group">
                                <label class="col-sm-2 control-label">*储值规则名称:</label>
                                <div class="col-sm-8">
                                    <input type="hidden" class="form-control" name="type" value="gift_recharge"/>
                                    <input type="text" class="form-control" name="name" placeholder="" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">副标题(前端显示):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="title" placeholder=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">*实付金额(元):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control num" name="payment_amount" placeholder="" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">*到账金额(元):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control num" name="amount" placeholder="" required="required"/>
                                </div>
                            </div>

                            {{--<div class="form-group">--}}
                                {{--<label class="col-sm-2 control-label">*生效时间：</label>--}}
                                {{--<div class="col-sm-3">--}}
                                    {{--<div class="input-group date form_datetime">--}}
                                        {{--<span class="input-group-addon" style="cursor: pointer">--}}
                                            {{--<i class="fa fa-calendar"></i>&nbsp;&nbsp;开始</span>--}}
                                        {{--<input type="text" class="form-control inline" name="starts_at" value="{{date("Y-m-d H:i:s",time())}}"--}}
                                               {{--placeholder="点击选择开始时间" readonly>--}}
                                        {{--<span class="add-on"><i class="icon-th"></i></span>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="col-sm-3">--}}
                                    {{--<div class="input-group date form_datetime">--}}
                                        {{--<span class="input-group-addon" style="cursor: pointer">--}}
                                            {{--<i class="fa fa-calendar"></i>&nbsp;&nbsp;截止</span>--}}
                                        {{--<input type="text" class="form-control" name="ends_at"--}}
                                               {{--value="{{date("Y-m-d H:i:s",time()+60*60*24*30)}}" placeholder="点击选择结束时间" readonly>--}}
                                        {{--<span class="add-on"><i class="icon-th"></i></span>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group">
                                <label class="col-sm-2 control-label">赠送优惠券：</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline i-checks"><input name="open_coupon" type="radio"
                                                                                   value="1" >
                                        是</label>
                                    <label class="checkbox-inline i-checks"><input name="open_coupon" type="radio"
                                                                                   value="0" checked>否</label>
                                </div>
                            </div>


                        <div class="form-group" style="display: none">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="coupon_title" name="coupon_title" placeholder="请输入进行中的优惠券名称搜索" />
                            </div>
                            <div class="col-sm-2">
                               <a href="javascript:;" class="btn btn-w-m btn-info" id="search" >搜索</a>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <select class="form-control" name="coupon" id="select_coupon" >
                                    <option id="option_coupon" value="">请选择优惠券</option>
                                    {{--<option value="3">户外</option>--}}
                                    {{--<option value="4">骑行</option>--}}
                                    {{--<option value="3">户外</option>--}}
                                    {{--<option value="4">骑行</option>--}}
                                </select>
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
                                <input type="text" class="form-control num" name="point" placeholder="输入赠送积分数目" value="0" />
                            </div>
                        </div>



                        <div class="form-group">
                                <label class="col-sm-2 control-label">*开启状态：</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                                   value="1" >
                                        是</label>
                                    <label class="checkbox-inline i-checks"><input name="status" type="radio"
                                                                                   value="0" checked> 否</label>
                                </div>
                         </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">*排序：</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control num" name="sort" placeholder="" value="1"  required="required" />
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
{{--@stop--}}

{{--@section('after-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    <script>
        var coupon_api="{{route('admin.users.recharge.api.coupon',['status'=>'ing'])}}"
    </script>
    <script>
//        $(function () {
//            $('#add').click(function () {
//                var id=$(this).data('id');
//                $('#select_coupon_box_'+ id).show();
//                if($(this).data('id')<4){
//                    $(this).data('id',id+1)
//                }else{
//                    console.log('no');
//                }
//                console.log(id);
//            })
//
//            $('.del').click(function () {
//                var id=$(this).data('id');
//                $('#select_coupon_box_'+ id).hide();
//                if(id==1){
//                    $('#add').data('id',id);
//                }else{
//                    $('#add').data('id',id-1);
//                }
//            })
//
//        })

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
            $('#select_coupon').html('');
            $.get(url,function (res) {
                if(res.status){
                    var data=res.data;
                    var html="<option value=''>"+"请选择优惠券"+"</option>";
                    if(data.length>0){
                          $.each(data,function (k,v) {
                            html+="<option value="+v.id+">"+v.title+"</option>";
                        })
                    }
                    $('#select_coupon').html(html);
                }
            })
        });

        $('input[name=point]').change(function () {
        var num=$('input[name=point]').val();
        var reg = /^\d+(?=\.{0,1}\d+$|$)/;
            if(!reg.test(num)) {
                $('input[name=point]').val(0);
            }
        })

        $('input[name=payment_amount]').change(function () {
            var num=$('input[name=payment_amount]').val();
            var reg = /^\d+(?=\.{0,1}\d+$|$)/;
            if(!reg.test(num)) {
                $('input[name=payment_amount]').val('');
            }
        })

        $('input[name=amount]').change(function () {
            var num=$('input[name=amount]').val();
            var reg = /^\d+(?=\.{0,1}\d+$|$)/;
            if(!reg.test(num)) {
                $('input[name=amount]').val('');
            }
        })

        $('input[name=sort]').change(function () {
            var num=$('input[name=sort]').val();
            var reg = /^\d+(?=\.{0,1}\d+$|$)/;
            if(!reg.test(num)) {
                $('input[name=sort]').val('');
            }
        })


         $('#search').trigger("click");

        $('#create-suit-form').ajaxForm({
            beforeSubmit:function (data) {
                var input = [];
                $.each(data, function (k, v) {
                    if (v.name !== "lenght") {
                        input[v.name] = v.value;
                    }
                })

                if (input['payment_amount'] <= 0) {
                    swal({
                        title: "保存失败",
                        text: "实付金额必须大于0",
                        type: "error"
                    });
                    return false;
                }

                if (input['amount'] <= 0) {
                    swal({
                        title: "保存失败",
                        text: "到账金额必须大于0",
                        type: "error"
                    });
                    return false;
                }

                if (input['payment_amount'] - input['amount']>0) {
                    swal({
                        title: "保存失败",
                        text: "到账金额不能小于实付金额",
                        type: "error"
                    });
                    return false;
                }


                if (input['payment_amount'] < input['amount'] || input['open_coupon']==1|| input['open_point']==1) {
                    if (input['title'] == '') {
                        console.log( input['open_point']);
                        swal({
                            title: "保存失败",
                            text: "请输入副标题(前端显示):",
                            type: "error"
                        });
                        return false;
                    }
                }

                if(input['point'] <0 ){
                    swal({ title: "保存失败", text: "积分的数目非负数",type: "error"  });
                    return false;
                }
                if(input['sort'] <=0){
                    swal({ title: "保存失败", text: "排序非负数非0",type: "error"  });
                    return false;
                }

                if (input['open_coupon']==1) {
                    console.log(input['coupon']);
                    if(input['coupon'] == "" || typeof(input['coupon'])=="undefined"){
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
                    swal("保存失败!", result.error, "error")
                }else{
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function() {
                        location = '{{route('admin.users.recharge.index')}}';
                    });
                }

            }
        });
    </script>
{{--@stop--}}