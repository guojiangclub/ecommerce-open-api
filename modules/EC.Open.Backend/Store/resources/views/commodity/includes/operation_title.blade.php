@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    批量修改商品标题
@stop

@section('after-styles-end')

@stop


@section('body')
    <div class="row">
        <div class="alert alert-warning">
            <p>正在编辑{{$num}}条数据。</p>
            <p>1、“全部修改为”即把所选商品名称修改为一致。</p>
            <p>2、“增加”可在商品名称前、后各增加一串字符。</p>
            <p>3、“查找并替换”可实现字符替换。若替换文字不填写，则被替换文字会被系统删除。</p>
        </div>

        <form method="POST" action="{{route('admin.goods.saveTitle')}}" accept-charset="UTF-8"
              id="base-form" class="form-horizontal">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="ids" value="{{$ids}}">

            <div class="form-group">
                <label class="col-sm-2 control-label" style="text-align: left"><input type="radio" name="type"
                                                                                      value="all">全部修改</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" placeholder="" required>
                </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label" style="text-align: left"><input type="radio" name="type"
                                                                                      value="add">增加</label>
                <div class="col-sm-5">
                    <label class="col-sm-4 control-label">标题前缀：</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="prefix" placeholder=""
                               value="" required>
                    </div>
                </div>
                <div class="col-sm-5">
                    <label class="col-sm-4 control-label">标题后缀：</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="suffix" placeholder=""
                               value="" required>
                    </div>
                </div>
            </div>

            <div class="hr-line-dashed"></div>

            <div class="form-group">
                <label class="col-sm-2 control-label" style="text-align: left"><input type="radio" name="type"
                                                                                      value="replace">查找替换</label>
                <div class="col-sm-5">
                    <label class="col-sm-4 control-label">查找：</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="find" placeholder=""
                               value="" required>
                    </div>
                </div>
                <div class="col-sm-5">
                    <label class="col-sm-4 control-label">替换：</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="replace" placeholder=""
                               value="" required>
                    </div>
                </div>
            </div>

        </form>
    </div>
@stop

{!! Html::script(env("APP_URL").'/vendor/libs/jquery.form.min.js') !!}
@section('footer')
    <button type="button" class="btn btn-link exit" data-dismiss="modal">取消</button>

    <button type="submit" class="btn btn-primary" data-toggle="form-submit" data-target="#base-form">修改
    </button>

    <script type="text/javascript">
        $('#base-form').ajaxForm({
            success: function (result) {
                if (!result.status) {
                    swal("保存失败!", result.message, "error")
                } else {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location.reload();
                    });
                }

            }

        });
    </script>

@stop






