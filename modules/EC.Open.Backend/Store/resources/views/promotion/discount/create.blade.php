    {!! Html::style(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/formvalidation/dist/css/formValidation.min.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}
    {!! Html::style(env("APP_URL").'/assets/backend/libs/pager/css/kkpager_orange.css') !!}

    {!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}

    <style type="text/css">
        table.category_table > tbody > tr > td{border: none}
        .sp-require{color: red; margin-right: 5px}
    </style>

    <div class="tabs-container">

        {!! Form::open( [ 'url' => [route('admin.promotion.discount.store')], 'method' => 'POST', 'id' => 'create-discount-form','class'=>'form-horizontal'] ) !!}

        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <fieldset class="form-horizontal">
                        @include('store-backend::promotion.discount.includes.create_base')
                    </fieldset>

                    <fieldset class="form-horizontal">
                        @include('store-backend::promotion.public.create_rule')
                    </fieldset>

                    <fieldset class="form-horizontal">
                        @include('store-backend::promotion.discount.includes.create_action')
                    </fieldset>

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
    <script>
        $(function () {
            $('.goodsSku').tagator({  });

            $.iCheckAll({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%',
                prefix: 'dep'
            });

            $('#create-discount-form').ajaxForm({
                success: function (result) {
                    if(result.status){
                        swal({
                            title: "保存成功！",
                            text: "",
                            type: "success"
                        }, function() {
                            window.location = '{{route('admin.promotion.discount.index')}}';
                        });
                    }else{
                        swal("保存失败!", result.message, "error")
                    }

                }
            });
        })
    </script>