{{--@extends ('member-backend::layout')--}}

{{--@section ('title','储值记录')--}}


{{--@section ('breadcrumbs')--}}
    {{--<h2>储值记录</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
    {{--</ol>--}}
{{--@stop--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}
{{--@stop--}}

{{--@section('content')--}}

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            {!! Form::open( [ 'route' => ['admin.users.recharge.log.index'], 'method' => 'get', 'id' => 'recordSearch-form','class'=>'form-horizontal'] ) !!}
            @if(!empty($id))
            <input type="hidden" name="id" value="{{$id}}">
            @endif
            <div class="row">
                <div class="col-md-7">
                    <div class="col-sm-6">
                        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;充值时间</span>
                            <input type="text" class="form-control inline" name="stime"
                                   value="{{request('stime')}}" placeholder="开始" readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control" name="etime" value="{{request('etime')}}"
                                   placeholder="截止" readonly>
                            <span class="add-on"><i class="icon-th"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group col-md-12">
                        <input type="text" name="order_no" value="{{request('order_no')}}" placeholder="输入单号"
                               class=" form-control"> <span
                                class="input-group-btn">

                        </span></div>
                </div>


                <div class="col-md-2">
                    <div class="input-group col-md-12">
                        <input type="text" name="mobile" value="{{request('mobile')}}" placeholder="输入用户手机号码"
                               class=" form-control">
                        <span
                                class="input-group-btn">
                     </span></div>
                </div>

                <div class="col-md-1">
                    <div class="input-group col-md-8">
                             <button type="submit" class="btn btn-primary">搜索</button>
                              &nbsp;&nbsp;&nbsp
                             <a href="javascript:;" class="btn btn-primary" id="empty">清空</a>
                     </div>
                </div>




            </div>

            {!! Form::close() !!}

            <div class="hr-line-dashed"></div>

                @if(count($lists)>0)
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <!--tr-th start-->
                        <tr>
                            <th>充值时间</th>
                            <th>交易单号</th>
                            <th>会员</th>
                            <th>手机</th>
                            <th>储值规则</th>
                            <th>营销活动</th>
                            <th>实付金额</th>
                            <th>到账金额</th>
                            {{--<th>操作</th>--}}
                        </tr>
                        <!--tr-th end-->
                        @foreach($lists as $item)
                            <tr>
                                <td>{{$item->pay_time}}</td>
                                <td>{{$item->order_no}}</td>

                                <td>
                                    <a href="" target="_blank">{{$item->user->nick_name}}</a>
                                </td>
                                <td>
                                    <a href="" target="_blank"> {{$item->user->mobile}}</a>
                                </td>

                                <td>
                                    @if(isset($item->recharge->id))
                                    <a href="{{route('admin.users.recharge.edit',['id'=>$item->recharge->id])}}" target="_blank"> {{$item->recharge->name}}</a>
                                    @endif
                                </td>

                                <td>
                                    @if(isset($item->recharge->title))
                                        {{$item->recharge->title}}
                                    @endif
                                </td>
                                <td>
                                    {{number_format($item->pay_amount/100,2,".","")}}
                                </td>
                                <td>
                                    {{number_format($item->amount/100,2,".","")}}
                                </td>

                            </tr>
                        @endforeach



                        </tbody>
                    </table>
                </div><!-- /.box-body -->

                <div class="pull-left">
                    &nbsp;&nbsp;共&nbsp;{!! $lists->total() !!} 条记录
                </div>

                <div class="pull-right">
                    {!! $lists->appends(request()->except('page'))->render() !!}
                </div>
            @else
                &nbsp;&nbsp;&nbsp;当前无数据
            @endif

            <div class="box-footer clearfix">
            </div>
        </div>

    </div>
{{--@endsection--}}


{{--@section('before-scripts-end')--}}

    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/el.common.js') !!}
    <script>
        $('.form_datetime').datetimepicker({
            minView: 0,
            format: "yyyy-mm-dd hh:ii:ss",
            autoclose: 1,
            language: 'zh-CN',
            weekStart: 1,
            todayBtn: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: true,
            minuteStep: 1,
            maxView: 4
        });

        $(function () {
            $('#empty').click(function () {
                $("input[name=mobile]").val('');
                $("input[name=order_no]").val('');
                $("input[name=stime]").val('');
                $("input[name=etime]").val('');
            })
        })
        
    </script>
    
    
    
    
{{--@endsection--}}