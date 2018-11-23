    <style type="text/css">
        .coupon-tips {
            font-size: 12px;
            color: #a7b1c2;
        }
    </style>

    <div class="tabs-container">

        <ul class="nav nav-tabs">
            <li class="{{ Active::query('status','') }}"><a href="{{route('admin.promotion.coupon.index')}}" no-pjax> 所有优惠券
                    <span class="badge"></span></a></li>
            <li class="{{ Active::query('status','nstart') }}"><a
                        href="{{route('admin.promotion.coupon.index',['status'=>'nstart'])}}" no-pjax> 未开始
                    <span class="badge"></span></a></li>
            <li class="{{ Active::query('status','ing') }}"><a
                        href="{{route('admin.promotion.coupon.index',['status'=>'ing'])}}" no-pjax> 进行中
                    <span class="badge"></span></a></li>
            <li class="{{ Active::query('status','end') }}"><a
                        href="{{route('admin.promotion.coupon.index',['status'=>'end'])}}" no-pjax> 已结束
                    <span class="badge"></span></a></li>
        </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">

                {!! Form::open( [ 'route' => ['admin.promotion.coupon.index'], 'method' => 'get', 'id' => 'discount-form','class'=>'form-horizontal'] ) !!}
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn btn-primary " href="{{ route('admin.promotion.coupon.create') }}" no-pjax>添加优惠券</a>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="title" value="{{request('title')}}" placeholder="优惠券名称"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                        </div>

                    </div>

                    {!! Form::close() !!}

                    <div class="hr-line-dashed"></div>

                    <div class="table-responsive">
                        @if(count($coupons)>0)
                            <table class="table table-hover table-striped">
                                <tbody>
                                <!--tr-th start-->
                                <tr>
                                    <th>优惠券名称</th>
                                    <th>优惠</th>
                                    <th>领取限制</th>
                                    <th>有效期</th>
                                    <th>领取数</th>
                                    <th>已使用</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                <!--tr-th end-->
                                @foreach ($coupons as $item)
                                    <tr>
                                        <td>{{$item->title}}</td>
                                        <td>
                                            @foreach($item->discountActions as $val)
                                                {{$val->action_text}}<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            一人{{$item->per_usage_limit}}张
                                            <p class="coupon-tips">共发放:{{$item->count_num}}
                                                &nbsp;&nbsp;&nbsp;库存:{{$item->usage_limit}}</p>
                                        </td>
                                        <td>{{$item->starts_at}} 至 <br>
                                            {{$item->ends_at}}</td>
                                        <td>{{$item->used}}</td>
                                        <td>{{$item->used_coupon_count}}</td>

                                        <td>{{$item->status_text}}</td>
                                        <td>

                                            <a
                                                    class="btn btn-xs btn-primary"
                                                    href="{{route('admin.promotion.coupon.edit',['id'=>$item->id])}}" no-pjax>
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-pencil-square-o"
                                                   title="编辑"></i></a>

                                            <a href="{{route('admin.promotion.coupon.show',['id'=>$item->id])}}"
                                               class="btn btn-xs btn-primary" no-pjax>
                                                <i class="fa fa-asterisk" data-toggle="tooltip" data-placement="top"
                                                   title="查看领取记录"></i></a>


                                            <a href="{{route('admin.promotion.coupon.useRecord',['id'=>$item->id])}}"
                                               class="btn btn-xs btn-primary" no-pjax>
                                                <i class="fa fa-eye" data-toggle="tooltip" data-placement="top"
                                                   title="查看使用记录"></i></a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="10" class="footable-visible">
                                        {!! $coupons->render() !!}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        @else
                            <div>
                                &nbsp;&nbsp;&nbsp;当前无数据
                            </div>
                        @endif
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade"></div>
    <div id="download_modal" class="modal inmodal fade"></div>










