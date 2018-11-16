{!! Html::style(env("APP_URL").'/assets/backend/libs/dategrangepicker/daterangepicker.css') !!}
{!! Html::style(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.css') !!}


<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;">
        <div class="row">

            <div class="panel-body">
                @if($type=='show')
                    @include('store-backend::multi_groupon.includes.show_form')
                @else
                    @include('store-backend::multi_groupon.includes.edit_form')
                @endif

            </div>

        </div>
    </div>
</div>

<div id="modal" class="modal inmodal fade" data-keyboard=false data-backdrop="static"></div>


@include('store-backend::multi_groupon.scripts')
<script>
    $(function () {
        var status ={{$groupon->edit_status}};
        var type ={{$type}};

        if (status == 0 && type == 'edit') {
            swal({
                title: "提示！",
                text: "不可编辑",
                type: "warning"
            }, function () {
                location = '{{route('admin.promotion.multiGroupon.index')}}';
            });
        }
    });
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
                    location = '{{route('admin.promotion.multiGroupon.index')}}';
                });
            }

        }

    });
</script>
