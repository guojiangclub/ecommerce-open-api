{{--@extends('backend::dashboard')--}}

{{--@section ('title','数据统计')--}}

{{--@section('after-styles-end')--}}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}

{{--@stop--}}

{{--@section('breadcrumbs')--}}

    {{--<h2>数据统计</h2>--}}
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>--}}
        {{--<li class="active">数据统计</li>--}}
    {{--</ol>--}}

{{--@endsection--}}

{{--@section('content')--}}

    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a href="javascript:;">数据统计
                    <span class="badge"></span></a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row">


                        {!! Form::open( [ 'route' => ['admin.statistics.index'], 'method' => 'get', 'class'=>'form-horizontal'] ) !!}
                        <div class="col-md-6">
                            <div class="col-sm-5">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;时间</span>
                                    <input  type="text" class="form-control inline" name="stime" id="stime"  value="{{request('stime') ? request('stime'):''}}"   placeholder="开始 " readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="etime" id="etime"   value="{{request('etime') ? request('etime'):''}}"    placeholder="截止" readonly>
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">查找</button>
                        </div>
                        {!! Form::close() !!}
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <tbody>
                            <!--tr-th start-->
                            <tr>
                                <th>会员卡领取数</th>
                                <th>线下优惠券领取数</th>
                                <th>线上优惠券领取数</th>
                            </tr>
                            <!--tr-th end-->
                            <tr>
                                <td>{{$CardCount}}</td>
                                <td>{{$OffCouponCount}}</td>
                                <td>{{$OnCouponCount}}</td>
                            </tr>

                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                        <div class="hr-line-dashed"></div>
                        <h3>截止到 {{request('etime') ? request('etime') : date("Y-m-d",strtotime("-1 day"))}} 的总数据</h3>
                        <table class="table table-hover table-striped">
                            <tbody>
                            <!--tr-th start-->
                            <tr>
                                <th>会员卡领取数</th>
                                <th>线下优惠券领取数</th>
                                <th>线上优惠券领取数</th>
                            </tr>
                            <!--tr-th end-->
                            <tr>
                                <td>{{$TCardCount}}</td>
                                <td>{{$TOffCouponCount}}</td>
                                <td>{{$TOnCouponCount}}</td>
                            </tr>

                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
{{--@endsection--}}

{{--@section('before-scripts-end')--}}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    <script type="text/javascript">
        $('.form_datetime').datetimepicker({
            language:  'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            minuteStep : 1
        });

    </script>
    {{--@stop--}}
