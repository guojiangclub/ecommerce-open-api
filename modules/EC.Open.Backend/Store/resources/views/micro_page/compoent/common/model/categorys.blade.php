@extends('store-backend::micro_page.bootstrap_modal')

@if(request('type')=='micro_page_componet_goods_group_img')
    <style>
        .a{
            margin-left: 200px;
        }
    </style>
@endif


@section('modal_class')

    @if(request('type')=='micro_page_componet_goods_group_img')
        modal-md
    @else
        modal-lg
    @endif
    a
@stop

@section('title')
    选择分类
    {{--{{request('index')}},{{request('category_id')}}--}}
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@stop

@section('body')

    <div class="row" style="height:350px">

        <form class="form-horizontal"  action="" method="get" id="">

            <div class="col-md-12" style="height:300px;">
                <div class="form-group" style="margin-top: 50px;">
                    <label for="exampleInputEmail1" class="col-sm-3 control-label"> 分类名称:</label>
                    <div class="col-sm-6">
                        <select class="form-control category" name="category">

                            @if(count($categorys))　

                                @foreach($categorys as $item)

                                    <option  value="{{$item->id}}" >

                                        {{str_replace('0','&nbsp;&nbsp;',$item->html)}}

                                        {{$item->name}}</option>

                                @endforeach

                            @endif

                        </select>
                    </div>

                </div>
            </div>

        </form>

        <div class="panel-body">
            <div class="col-sm-2">

            </div>
            <div class="table-responsive col-sm-9" id="categorysList" data-category_id="{{request('category_id')}}">

            </div>

            <div class="col-sm-1">

            </div><!-- /.box-body -->
            <div id="kkpager" style="margin-left: 160px;"></div>
        </div>
    </div>
@stop
{{--{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/spin.min.js') !!}--}}
{{--{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.min.js') !!}--}}
{{--{!! Html::script(env("APP_URL").'/assets/backend/libs/ladda/ladda.jquery.min.js') !!}--}}
{{--{!! Html::script(env("APP_URL").'/assets/backend/libs/loader/jquery.loader.min.js') !!}--}}
{{--{!! Html::script(env("APP_URL").'/assets/backend/libs/pager/js/kkpager.min.js') !!}--}}


@section('footer')
    {{--{!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.form.min.js') !!}--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            var  obj=$('.category').select2({
                placeholder:'请选择分类',
                dropdownParent:$('#goods_modal')
            });
            obj.val("{{request('category_id')}}").trigger("change");
        })
    </script>
    <button type="button" class="btn btn-link" data-dismiss="modal" onclick="cancel();">取消</button>
    <button type="button" onclick="sendIds();"  class="ladda-button btn btn-primary" > 确定
        <script>
            var index="{{request('index')}}"
            function cancel() {
                //var category_id=$('#app').attr('data-category_id');
                var category_id=$('.category').val();
                if(!category_id){
                    $('#categorys_modal').data('index');
                    $('.advert_li_'+index).find('.type-s').val(0);
                    var link_input_input= $('.advert_li_'+index).find('.inputLink-'+index);
                    link_input_input.attr('data-type','');
                    link_input_input.attr('data-page','');
                    link_input_input.attr('placeholder','');
                }
            }

            function sendIds() {
                //var category_id=$('#app').attr('data-category_id');
                var category_id=$('.category').val();

                if(category_id<=0){
                    swal({
                        title: '保存失败',
                        text: '请选择关联的分类',
                        type: "error"
                    });
                    return false;
                }

                var index="{{request('index')}}"
                var link_input_input= $('.advert_li_'+index).find('.inputLink-'+index);
                link_input_input.val(category_id);
                link_input_input.attr('placeholder','');
                $('.advert_li_'+index).find('.link-input').show();
                $('#goods_modal').modal('hide');
                //$('#categorys_modal').modal('hide');
                link_input_input.attr("disabled",true);
            }


        </script>

@endsection










