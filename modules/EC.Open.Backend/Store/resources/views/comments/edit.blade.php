@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    @if($comment->status == 'show')
        查看用户评论
    @else
        审核用户评论
    @endif
@stop


@section('body')
    <div class="row">
        {!! Form::open( [ 'url' => [route('admin.comments.update',$comment->id)], 'method' => 'POST', 'id' => 'comments-update-form','class'=>'form-horizontal'] ) !!}
        <div class="form-group">
            {!! Form::label('','商品名称：', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-9">
                <input type="text" class="form-control" placeholder="" value="{{$comment->goods->name}}" disabled>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('','订单号：', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-9">
                <input type="text" class="form-control" placeholder="" value="{{$comment->orderItem->order->order_no}}"
                       disabled>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('','发布者：', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-9">
                <input type="text" class="form-control"
                       placeholder="{{$comment->edit_name}}"
                       value="" disabled>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('','内容：', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-9">
                <textarea class="form-control" disabled name="description" rows="3">{{$comment->contents}}</textarea>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('','评论图片：', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-9">
                {!! $comment->comment_pic !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">审核状态：</label>
            <div class="col-sm-10">
                <div class="i-checks">
                    <label id="yesaudit"> <input type="radio" {{$comment->status=='show'?'checked':''}}   value="show"
                                                 name="status"/> <i></i> 审核通过</label>
                    <label id="noaudit"> <input type="radio"
                                                {{$comment->status=='hidden'?'checked':''}}   value="hidden"
                                                name="status"/> <i></i> 审核未通过 </label>
                </div>
            </div>
        </div>


        <div class="form-group" id="recommendShow" style="display: {{$comment->status == 'hidden' ? 'none':''}}">
            <label class="col-sm-2 control-label">推荐状态：</label>
            <div class="col-sm-10">
                <div class="i-checks">
                    <label> <input type="radio" {{$comment->recommend==0?'checked':''}}   value="0" name="recommend"/>
                        <i></i>不推荐</label>
                    <label> <input type="radio" {{$comment->recommend==1?'checked':''}}   value="1" name="recommend"/>
                        <i></i>推荐</label>
                </div>
            </div>
        </div>


        {!! Form::close() !!}
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

    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>
    <button type="submit" id="send" class="btn btn-primary ladda-button" data-toggle="form-submit" data-style="zoom-in"
            data-target="#comments-update-form">保存
    </button>


    <script>

        $(document).ready(function () {

            $('#noaudit').on('click', function () {
                if (!$(this).hasClass('selected')) {
                    $(this).addClass('selected');
                }
                $('#yesaudit').removeClass('selected');

                if ($(this).hasClass('selected')) {
                    $('#recommendShow').css('display', 'none');
                }

            })

            $('#yesaudit').on('click', function () {
                if (!$(this).hasClass('selected')) {
                    $(this).addClass('selected');
                }
                $('#noaudit').removeClass('selected');
                if ($(this).hasClass('selected')) {
                    $('#recommendShow').css('display', 'block');
                }

            });


            $('#comments-update-form').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {}
            }).on('success.form.fv', function (e) {

                $('#send').ladda().ladda('start');

                // Prevent form submission
                e.preventDefault();

                // Get the form instance
                var $form = $(e.target);

                // Get the FormValidation instance
                var bv = $form.data('formValidation');

                // Use Ajax to submit form data
                $.post($form.attr('action'), $form.serialize(), function (result) {

                    $("#comments-update-form").parents('.modal').modal('hide');
                    location.reload();

                });

            });

        })
    </script>
@stop






