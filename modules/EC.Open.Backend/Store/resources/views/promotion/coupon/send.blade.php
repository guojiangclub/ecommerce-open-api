@extends('store-backend::dashboard')

@section ('title','优惠卷管理')

@section('breadcrumbs')
    <h2>发送优惠券</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">{!! link_to_route('admin.promotion.coupon.index', '优惠券管理') !!}</li>
        <li class="active">发送优惠券</li>
    </ol>
@endsection

@section('content')

    <div class="tabs-container">
        <div class="tab-pane active div_1">
            <div class="panel-body">

                {!! Form::open( [ 'url' => [route('admin.promotion.coupon.sendAction',['id'=>$id])], 'method' => 'POST', 'id' => 'coupons-send-form','class'=>'form-horizontal'] ) !!}
                <input type="hidden" name="discount_id" value="{{$id}}">
                <div class="form-group">
                    {!! Form::label('name','优惠券名称：', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{$coupon->title}}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('name','剩余发行量（张）：', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{$coupon->usage_limit}}" disabled>
                    </div>
                </div>

                <div class="form-group groups">
                    <div class="form-group screen">
                        {!! Form::label('name','筛选指定用户：', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-9">
                            <button type="button" id="screenbutton" class="btn btn-info"
                                   data-toggle="modal"
                                    data-target="#modal" data-backdrop="static" data-keyboard="false"
                                    data-url="{{route('admin.promotion.coupon.sendCoupon.filterUser')}}">筛选用户</button>

                            <span id="selected_count"></span>
                            <input type="hidden" id="select_users" name="user_ids">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('name','已选择用户：', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-9">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <!--tr-th start-->
                                <tr>
                                    <th>昵称</th>
                                    <th>手机号码</th>
                                    <th>用户名</th>
                                    <th>操作</th>
                                </tr>
                                <!--tr-th end-->
                                </thead>

                                <tbody class="selected-users-list">

                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div>
                </div>


                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-lg-2 control-label"></label>
                    <div class="col-lg-10">
                        <input id="send" type="submit" class="ladda-button btn btn-success"
                               data-toggle="form-submit" data-target="#coupons-send-form" data-style="slide-right"
                               value="发送"/>

                        <a href="{{route('admin.promotion.coupon.index')}}" class="btn btn-danger">取消</a>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade"></div>

@endsection

@section('before-scripts-end')
    <script type="text/html" id="selected-user-temp">
        <tr>
            <td>
                {#name#}
            </td>
            <td>
                {#mobile#}
            </td>
            <td>
                {#nick_name#}
            </td>
            <td>
                <button onclick="delete_selected(this)" class="btn btn-xs btn-danger"
                        type="button" data-id="{#id#}">
                    <i data-toggle="tooltip" data-placement="top"
                       class="fa fa-trash"
                       title="删除"></i>
                </button>
            </td>
        </tr>
    </script>

    <script>
        $(document).ready(function () {

            $('#coupons-send-form').ajaxForm({
//                beforeSubmit: function () {
//                    $('#send').ladda().ladda('start');
//                },
                success: function (result) {
                    if(!result.status){
                        swal("发送失败!", result.message, "error")
                    }else{
                        swal({
                            title: "操作成功！",
                            text: "受影响用户" + result.data.count + '个',
                            type: "success"
                        }, function () {
                            location = '{{route('admin.promotion.coupon.index')}}';
                        });
                    }
                }
            });

        });

        function delete_selected(_self) {
            $(_self).parent().parent().remove();

            var select_uid=$('#select_users');
            var string = select_uid.val();
            var ids = string.split(',');
            var index = ids.indexOf(String($(_self).data('id')));
            if(!!~index)
            {
                ids.splice(index, 1);
            }
            var str = ids.join(',');
            select_uid.val(str);

            var count = ids.length;
            $('#selected_count').html('已选择 '+count+' 个用户');
        }
    </script>
@stop


