    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li  class="{{ Active::query('status','') }}"><a href="{{route('admin.promotion.discount.index')}}" no-pjax> 所有活动
                    <span class="badge"></span></a></li>
            <li  class="{{ Active::query('status','nstart') }}"><a href="{{route('admin.promotion.discount.index',['status'=>'nstart'])}}" no-pjax> 未开始
                    <span class="badge"></span></a></li>
            <li  class="{{ Active::query('status','ing') }}"><a href="{{route('admin.promotion.discount.index',['status'=>'ing'])}}" no-pjax> 进行中
                    <span class="badge"></span></a></li>
            <li  class="{{ Active::query('status','end') }}"><a href="{{route('admin.promotion.discount.index',['status'=>'end'])}}" no-pjax> 已结束
                    <span class="badge"></span></a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                {!! Form::open( [ 'route' => ['admin.promotion.discount.index'], 'method' => 'get', 'id' => 'discount-form','class'=>'form-horizontal'] ) !!}
                 <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn btn-primary " href="{{ route('admin.promotion.discount.create') }}" no-pjax>添加活动</a>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="title" value="{{request('title')}}"   placeholder="活动名称"
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
                                    <th>活动名称</th>
                                    <th>优惠</th>
                                    <th>有效期</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                <!--tr-th end-->
                                @foreach ($discount as $item)
                                    <tr>
                                        <td>{{$item->title}}</td>
                                        <td>
                                            @foreach($item->discountActions as $val)
                                                {{$val->action_text}}<br>
                                                @endforeach
                                        </td>
                                        <td>{{$item->starts_at}} 至<br> {{$item->ends_at}}</td>
                                        <td>{{$item->status_text}}</td>
                                        <td>
                                            <a
                                                    class="btn btn-xs btn-primary"
                                               href="{{route('admin.promotion.discount.edit',['id'=>$item->id])}}" no-pjax>
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-pencil-square-o"
                                                   title="编辑"></i></a>

                                            <a  href="{{route('admin.promotion.discount.useRecord' ,['id'=>$item->id])}}"
                                                    class="btn btn-xs btn-success" no-pjax>
                                                    <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="查看使用记录"></i></a>

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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal" class="modal inmodal fade"></div>