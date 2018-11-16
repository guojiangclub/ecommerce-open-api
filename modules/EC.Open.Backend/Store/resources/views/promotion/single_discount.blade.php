{{--@extends('store-backend::dashboard')

@section('breadcrumbs')
    <h2>单品折扣列表</h2>
    <ol class="breadcrumb">
        <li><a href="{!!route('admin.store.index')!!}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li class="active">单品折扣</li>
    </ol>
@endsection

@section('content')--}}

    <div class="ibox float-e-margins">
        <div class="ibox-content" style="display: block;">
            <a href="{{ route('admin.promotion.singleDiscount.create')}}"
               class="btn btn-primary margin-bottom" no-pjax>新建单品折扣</a>
            <div class="box-body table-responsive">
                <table class="table table-hover table-bordered">
                    <tbody>
                    <!--tr-th start-->
                    <tr>
                        <th>名称</th>
                        <th>状态</th>
                        <th>启用/禁用</th>
                        <th>操作</th>
                    </tr>
                    <!--tr-th end-->
                    @foreach($discount as $val)
                        <tr>
                            <th>{{$val->title}}</th>
                            <th>{{$val->status_text}}</th>
                            <th>
                                <a><i data-id="{{$val->id}}"
                                      class="fa switch {{$val->status_flag ? 'fa-toggle-on' : 'fa-toggle-off'}}"
                                      title="切换状态">

                                    </i></a>

                            </th>
                            <th>

                                    <a  class="btn btn-xs btn-primary" data-toggle="modal"
                                        data-target="#sync_modal" data-backdrop="static" data-keyboard="false"
                                        data-url="{{route('admin.promotion.singleDiscount.syncGoodsPriceModal',['id'=>$val->id,'type'=>$val->status==1?'update':'restore'])}}"
                                       href="javascript:;">
                                        <i data-toggle="tooltip" data-placement="top"
                                           class="fa fa-random"
                                           title="同步价格区间"></i></a>


                                <a  class="btn btn-xs btn-primary"
                                   href="{{route('admin.promotion.singleDiscount.edit',['id' => $val->id])}}" no-pjax>
                                    <i data-toggle="tooltip" data-placement="top"
                                       class="fa fa-pencil-square-o"
                                       title="编辑"></i></a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4" class="footable-visible">
                            {!! $discount->render() !!}
                        </td>
                    </tr>
                    </tfoot>
                </table>

                <div class="alert alert-danger">

                </div>
            </div><!-- /.box-body -->
        </div>
    </div>

    <div id="sync_modal" class="modal inmodal fade"></div>
{{--@endsection

@section('after-scripts-end')--}}
    <script>
        $(function () {
            setTimeout(function () {
                $.get('{{route('admin.promotion.singleDiscount.getDiscountInfo')}}',function (result) {
                    $('.alert-danger').html(result.data.info);
                });
            },2000);
        });

        $('.switch').click(function () {
            var switchStatusUrl = '{{route('admin.promotion.singleDiscount.switchStatus')}}';

            var data = {
                _token: _token,
                id: $(this).data('id')
            };

            $.post(switchStatusUrl, data, function (result) {
                if (result.status) {
                    swal({
                        title: "修改成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        window.location.reload();
                    });
                } else {
                    swal("修改失败!", result.message, "error")
                }
            });
        });

    </script>
{{--@endsection--}}