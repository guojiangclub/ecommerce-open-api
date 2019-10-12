    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="{{ Active::query('status','show') }}"><a no-pjax href="{{route('admin.comments.index',['status'=>'show'])}}">评论列表
                    <span class="badge">
                         {{$view=='show'?$comments_list_num:\GuoJiangClub\EC\Open\Backend\Store\Model\OrderComment::where('status','show')->count()}}
                    </span></a></li>
            <li class="{{ Active::query('status','hidden') }}"><a no-pjax href="{{route('admin.comments.index',['status'=>'hidden'])}}">待审核
                    <span class="badge">
                        {{$view=='hidden'?$comments_list_num:\GuoJiangClub\EC\Open\Backend\Store\Model\OrderComment::where('status','hidden')->count()}}
                    </span></a></li>

        </ul>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    {!! Form::open( [ 'route' => ['admin.comments.index'], 'method' => 'get', 'id' => 'commentsurch-form','class'=>'form-horizontal'] ) !!}

                    <div class="row">
                        <input type="hidden" id="audit"  name="status" value="{{!is_null(request('status'))?request('status'):'show'}}">
                        <div class="col-md-2">
                            <select class="form-control" name="point">
                                <option value="">评价分</option>
                                <option value="1" {{request('point')==1?'selected':''}} >1星</option>
                                <option value="2" {{request('point')==2?'selected':''}} >2星</option>
                                <option value="3" {{request('point')==3?'selected':''}} >3星</option>
                                <option value="4" {{request('point')==4?'selected':''}} >4星</option>
                                <option value="5" {{request('point')==5?'selected':''}} >5星</option>
                            </select>
                        </div>

                        <div class="col-md-2" style="display: none;">
                            <select class="form-control" name="field">
                                <option value="goods_name" {{request('field')=='goods_name'?'selected':''}} >商品名称</option>
                                {{--<option value="user_id" {{request('field')=='user_id'?'selected':''}}   >发布人</option>--}}
                            </select>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="value" value="{{request('value')}}"   placeholder="请输入商品名称"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                        </div>

                        <div class="col-md-4">
                            <a no-pjax href="{{route('admin.comments.create')}}" class="btn btn-primary">添加评论</a>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <div class="table-responsive">
                        <div id="orders">
                            @include('store-backend::comments.includes.comments_list')
                        </div>
                    </div><!-- /.box-body -->

                </div>
            </div>
        </div>
        </div>
    <div id="modal" class="modal inmodal fade"></div>
{{--@endsection--}}







