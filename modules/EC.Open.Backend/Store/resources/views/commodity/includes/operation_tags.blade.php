@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    批量添加商品标签
@stop

@section('body')
    <div class="row">

        <form method="POST" action="{{route('admin.goods.saveTags')}}" accept-charset="UTF-8"
              id="base-form" class="form-horizontal">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="ids" value="{{$ids}}">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label">请输入标签：</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="tags" name="tags" placeholder=""
                               value="" required>
                        <label>输入产品标签名称，按回车添加</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
{!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
{!! Html::script(env("APP_URL").'/vendor/libs/jquery.form.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.js') !!}
@section('footer')
    <button type="button" class="btn btn-link exit" data-dismiss="modal">取消</button>

    <button type="submit" class="btn btn-primary" data-toggle="form-submit" data-target="#base-form">添加
    </button>

    <script type="text/javascript">
        $(function () {
            $('#tags').tagator();
        });

        $('#base-form').ajaxForm({
            success: function (result) {
                if (!result.status) {
                    swal("保存失败!", result.error, "error")
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






