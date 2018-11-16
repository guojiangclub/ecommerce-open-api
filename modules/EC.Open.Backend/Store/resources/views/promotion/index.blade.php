@extends('store-backend::dashboard')

@section ('title','促销活动列表')

@section('breadcrumbs')
    <h2>促销活动列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">促销活动列表</li>
    </ol>
@endsection


@section('content')


    <div class="tabs-container">

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">

                {!! Form::open( [ 'route' => ['admin.promotion.index'], 'method' => 'get', 'id' => 'discount-form','class'=>'form-horizontal'] ) !!}
                 <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" name="status">
                                <option value="">状态</option>
                                <option value="1"     {{request('status')=='1'?'selected ':''}}    >有效</option>
                                <option value="0"     {{request('status')=='0'?'selected ':''}}    >禁用</option>

                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" name="coupon_based">
                                <option value="">促销类型</option>
                                <option value="1"     {{request('coupon_based')=='1'?'selected ':''}}    >优惠券类型</option>
                                <option value="0"     {{request('coupon_based')=='0'?'selected ':''}}    >普通类型</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="title" value="{{request('title')}}"   placeholder="优惠券名称"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                        </div>

                    </div>

                    {!! Form::close() !!}

                     <div class="hr-line-dashed"></div>

                    <div class="table-responsive">
                        @if(count($discount)>0)
                            <table class="table table-hover table-striped">
                                <tbody>
                                <!--tr-th start-->
                                <tr>
                                    <th>促销标题</th>
                                    <th>类型</th>
                                    <th>状态</th>
                                    <th>开始时间</th>
                                    <th>截止时间</th>
                                    <th>链接</th>
                                    <th>操作</th>
                                </tr>
                                <!--tr-th end-->
                                @foreach ($discount as $item)
                                    <tr>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->coupon_based == 1 ? '优惠券类型' : '普通类型'}}</td>
                                        <td>{{$item->status == 1 ? '有效' : '禁用'}}</td>
                                        <th>{{$item->starts_at}}</th>
                                        <th>{{$item->ends_at}}</th>
                                        <td style="position: relative;">
                                            <input type="text" value="{{env('MOBILE_DOMAIN')}}/#!/user/coupon/select/{{$item->id}}">
                                            <label class="label label-danger copyBtn">复制链接</label>
                                        </td>
                                        <td>

                                            <a
                                                    class="btn btn-xs btn-primary"
                                               href="{{route('admin.promotion.edit',['id'=>$item->id])}}">
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-pencil-square-o"
                                                   title="编辑"></i></a>
                                            @if($item->coupon_based==1)
                                                <a  href="{{route('admin.promotion.show',['id'=>$item->id])}}"
                                                    class="btn btn-xs btn-success">
                                                    <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="查看记录"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6" class="footable-visible">
                                        {!! $discount->render() !!}
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

@endsection

@section('after-scripts-end')
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.zclip/jquery.zclip.js') !!}
    <script>
        $('.copyBtn').zclip({
            path: "{{url('assets/backend/libs/jquery.zclip/ZeroClipboard.swf')}}",
            copy: function(){
                return $(this).prev().val();
            }
        });
    </script>
@endsection










