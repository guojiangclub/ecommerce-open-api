@extends('store-backend::dashboard')

@section('breadcrumbs')
    <h2>套餐列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">套餐列表</li>
    </ol>
@endsection


@section('content')


    <div class="tabs-container">

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">

                {!! Form::open( [ 'route' => ['admin.promotion.bundle.index'], 'method' => 'get', 'id' => 'discount-form','class'=>'form-horizontal'] ) !!}
                 <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="value" value="{{request('value')}}"   placeholder="Search"
                                       class=" form-control"> <span
                                        class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                        </div>

                    </div>

                    {!! Form::close() !!}

                     <div class="hr-line-dashed"></div>

                    <div class="table-responsive">
                        @if(count($bundle)>0)
                            <table class="table table-hover table-striped">
                                <tbody>
                                <!--tr-th start-->
                                <tr>
                                    <th>套餐标题</th>
                                    <th>类型</th>
                                    <th>状态</th>
                                    <th>套餐价(元)</th>
                                    <th>原价(元)</th>
                                    <th>操作</th>
                                </tr>
                                <!--tr-th end-->
                                @foreach ($bundle as $item)
                                    <tr>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->type == 1 ? '普通类型' : '其他类型'}}</td>
                                        <td>{{$item->status == 1 ? '有效' : '禁用'}}</td>
                                        <th>{{$item->sell_price}}</th>
                                        <th>{{$item->original_price}}</th>
                                        <td>
                                            <a
                                                    class="btn btn-xs btn-primary"
                                               href="{{route('admin.promotion.bundle.edit',['id'=>$item->id])}}">
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-pencil-square-o"
                                                   title="编辑"></i></a>
                                            <a target="_blank" data-method="delete" class="btn btn-xs btn-danger"
                                               href="{{route('admin.promotion.bundle.delete',['id'=>$item->id])}}">
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-trash"
                                                   title="删除"></i></a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6" class="footable-visible">
                                        {!! $bundle->render() !!}
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











