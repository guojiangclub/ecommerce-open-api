    {!! Html::style(env("APP_URL").'/assets/backend/admin/css/plugins/ladda/ladda-themeless.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.min.css') !!}

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="tabs-container">
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">

                {!! Form::open( [ 'route' => ['admin.promotion.discount.useRecord'], 'method' => 'get', 'id' => 'recordSearch-form','class'=>'form-horizontal'] ) !!}
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="col-sm-6">
                                <div class="input-group date form_datetime">
                                        <span class="input-group-addon" style="cursor: pointer">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;使用时间</span>
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
                                    <span class="add-on"><i class="icon-th"></i></span>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" name="order_no" value="{{request('order_no')}}" placeholder="订单号"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{$id}}">
                    {!! Form::close() !!}


                    <div class="row" style="margin-top: 15px">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="btn-group">
                                    <a class="btn btn-primary ladda-button dropdown-toggle batch" data-toggle="dropdown"
                                       href="javascript:;" data-style="zoom-in">导出 <span
                                                class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a  data-toggle="modal"
                                                data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                                                data-link="{{route('admin.promotion.discount.getUsedExportData',['type'=>'xls'])}}" id="all-xls"
                                                data-url="{{route('admin.export.index',['toggle'=>'all-xls'])}}"
                                                data-type="xls"
                                                href="javascript:;">导出所有数据</a></li>

                                        <li><a data-toggle="modal-filter"
                                               data-target="#download_modal" data-backdrop="static" data-keyboard="false"
                                               id="filter-xls"
                                               data-url="{{route('admin.export.index',['toggle'=>'filter-xls'])}}"
                                               data-type="xls"
                                               href="javascript:;">导出筛选数据</a></li>
                                    </ul>
                                </div>

                                <div class="btn-group">
                                    <button class="btn btn-primary" type="button" id="reset">重置搜索</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div id="coupons">
                            <div class="hr-line-dashed"></div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <tbody>
                                    <!--tr-th start-->
                                    <tr>
                                        <th>促销说明</th>
                                        <th>订单编号</th>
                                        <th>订单总金额</th>
                                        <th>订单状态</th>
                                        <th>会员名</th>
                                        <th>使用时间</th>
                                    </tr>
                                    @if($discounts->count()>0)
                                            <!--tr-th end-->
                                    @foreach ($discounts as $discount)
                                        <tr class="coupon{{$discount->id}}">
                                            <td>{{$discount->label}}</td>

                                            <td><a href="{{route('admin.orders.show',['id'=>$discount->order->id])}}"
                                                   target="_blank">{{$discount->order->order_no}}</a></td>

                                            <td>{{$discount->order->total}}</td>
                                            <td>{{$discount->order->status_text}}</td>
                                            <td>{{$discount->order->user->mobile}}</td>
                                            <td>{{$coupon->created_at}}</td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <div class="pull-left">
                                    &nbsp;&nbsp;共&nbsp;{!! $discounts->total() !!} 条记录
                                </div>

                                <div class="pull-right">
                                    {!! $discounts->appends(request()->except('page'))->render() !!}
                                </div>
                                <!-- /.box-body -->
                            </div>

                        </div>
                    </div><!-- /.box-body -->
                </div>

            </div>
        </div>
    </div>
    <div id="download_modal" class="modal inmodal fade"></div>

    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/datepicker/bootstrap-datetimepicker.zh-CN.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/el.common.js') !!}
    @include('store-backend::promotion.public.discount_used_script')