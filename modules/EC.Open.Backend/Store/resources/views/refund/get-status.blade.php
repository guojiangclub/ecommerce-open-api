
@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    修改售后状态
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="row">

        <form method="POST" action="{{route('admin.refund.changeStatus')}}" accept-charset="UTF-8"
              id="delivers_from" class="form-horizontal">
            <input type="hidden" name="id" value="{{$refund->id}}">

            <div class="col-md-8">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label">当前状态：</label>
                    <div class="col-sm-9">

                            <select class="form-control" name="status" id="shopping">

                                <option value="0" {{$refund->status == 0 ?'selected':''}} >待审核</option>
                                <option value="1" {{$refund->status == 1 ?'selected':''}}>审核通过</option>
                                <option value="2" {{$refund->status == 2 ?'selected':''}}>拒绝申请</option>
                                <option value="3" {{$refund->status == 3 ?'selected':''}}>完成</option>
                                <option value="4" {{$refund->status == 4 ?'selected':''}}>取消</option>
                                <option value="5" {{$refund->status == 5 ?'selected':''}}>等待用户退货</option>
                                <option value="6" {{$refund->status == 6 ?'selected':''}}>用户已退货</option>
                                <option value="7" {{$refund->status == 7 ?'selected':''}}>等待商城发货</option>

                            </select>

                    </div>
                </div>

                <div class="form-group">
                    <label for="remark" class="col-sm-3 control-label"> 备注：</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="remark" placeholder=""></textarea>
                    </div>
                </div>


            </div>
        </form>
    </div>
@stop
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/formValidation.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/framework/bootstrap.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/formvalidation/dist/js/language/zh_CN.js') !!}

{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}

@section('footer')
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>

    <button type="submit"  id="send" class="ladda-button btn btn-primary" data-style="slide-right" data-toggle="form-submit" data-target="#delivers_from">保存
    </button>

    <script>

        $(document).ready(function () {

            $('#delivers_from').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                }
            }).on('success.form.fv', function (e) {

                $('#send').ladda().ladda('start');

                // Prevent form submission
                e.preventDefault();

                // Get the form instance
                var $form = $(e.target);

                // Get the FormValidation instance
                var bv = $form.data('formValidation');

                // Use Ajax to submit form data
                $.post($form.attr('action'), $form.serialize(), function(result) {

                    $("#delivers_from").parents('.modal').modal('hide');
                    location.reload();

                });

            });

        })
    </script>
@stop



