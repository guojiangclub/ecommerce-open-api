{!! Html::style(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.css') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}
{!! Html::style('assets/backend/libs/webuploader-0.1.5/webuploader.css') !!}
<style type="text/css">
    table.category_table > tbody > tr > td {
        border: none
    }

    .sp-require {
        color: red;
        margin-right: 5px
    }
</style>
<div class="tabs-container">

    {!! Form::open( [ 'url' => [route('admin.promotion.coupon.store')], 'method' => 'POST', 'id' => 'create-discount-form','class'=>'form-horizontal'] ) !!}
    <input type="hidden" value="{{$discount->id}}" name="id">
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                <div class="col-sm-8">
                    <fieldset class="form-horizontal">
                        @include('store-backend::promotion.coupon.includes.edit_base')
                    </fieldset>

                    <fieldset class="form-horizontal">
                        @include('store-backend::promotion.public.edit_rule')
                    </fieldset>

                    <fieldset class="form-horizontal">
                        @include('store-backend::promotion.coupon.includes.edit_action')
                    </fieldset>
                </div>

                <div class="col-sm-4">
                    @include('store-backend::promotion.coupon.includes.coupon_area')
                </div>

            </div>
        </div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">保存设置</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<div id="spu_modal" class="modal inmodal fade"></div>
<div id="wechat_modal" class="modal inmodal fade"></div>
@include('store-backend::promotion.public.script')
@include('store-backend::promotion.coupon.includes.coupon_area_script')
<script>
    $(function () {
        $('.goodsSku').tagator({});

        $.iCheckAll({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
            increaseArea: '20%',
            prefix: 'dep'
        });

        //action Initialization
        var value = $('.action-select').children('option:selected').val();

        if (value == 'order_fixed_discount') {
            var action_html = $('#discount_action_template').html();
        }

        if (value == 'goods_fixed_discount') {
            var action_html = $('#goods_discount_action_template').html();
        }

        if (value == 'order_percentage_discount') {
            var action_html = $('#percentage_action_template').html();
        }

        if (value == 'goods_percentage_discount' || value == 'goods_percentage_by_market_price_discount') {
            var action_html = $('#goods_percentage_action_template').html();
        }

        $('#promotion-action').html(action_html.replace(/{VALUE}/g, '{{$discount->discountAction?$discount->discountAction->ActionValue:0}}'));


        // save return
        $('#create-discount-form').ajaxForm({
            success: function (result) {
                if (result.status) {
                    swal({
                        title: "保存成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        window.location = '{{route('admin.promotion.coupon.index')}}';
                    });
                } else {
                    swal("保存失败!", result.message, "error")
                }

            }
        });
    });

</script>
