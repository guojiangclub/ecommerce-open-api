    <script type="text/html" id="page-temp">
        <tr>
            <td>
                {#name#}
            </td>
            @if($spec->id == 2)
                <td>
                    <span style="background-color: #{#rgb#};" class="color-span">{#rgb#}</span>
                </td>
            @endif
            <td>

                <a class="btn btn-xs btn-success" id="chapter-create-btn" data-toggle="modal"
                   data-target="#spu_modal" data-backdrop="static" data-keyboard="false"
                   data-url="{{route('admin.goods.spec.value.editSpecValue')}}?id={#id#}">
                    <i class="fa fa-pencil" data-toggle="tooltip"
                       data-placement="top" title="" data-original-title="编辑"></i></a>


                <a href="javascript:;"   data-url="{{route('admin.goods.spec.value.delete')}}"
                   class="btn btn-xs btn-danger operator" data-id="{#id#}">
                    <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" data-original-title="删除"></i>
                </a>
            </td>
        </tr>
    </script>
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/common.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/jquery.http.js') !!}
    {!! Html::script(env("APP_URL").'/assets/backend/libs/jquery.el/page/jquery.pages.js') !!}
    <script>
        function initButton() {
            //功能操作按钮
            $('.operator').on('click',function () {
                var obj = $(this);
                swal({
                    title: "确定删除该规格值吗?",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    closeOnConfirm: false
                }, function () {
                    var url = obj.data('url');
                    var data = {
                        id: obj.data('id'),
                        _token: _token
                    };

                    $.post(url, data, function (ret) {
                        if (ret.status) {
                            obj.parent().parent().remove();
                            swal("删除成功!", "", "success");
                        } else {
                            swal("改规格值下面存在商品，不能删除!", "", "warning");
                        }
                    });

                });
            });
        }

        function getList() {

            var postUrl = '{{route('admin.goods.spec.getSpeValueData')}}';

            $('.pages').pages({
                page: 1,
                url: postUrl,
                get: $.http.post.bind($.http),
                body: {
                    spec_id: $('input[name="spec_id"]').val(),
                    _token: _token
                },
                marks: {
                    total: 'data.last_page',
                    index: 'data.current_page',
                    data: 'data'
                }
            }, function (data) {
                var html = '';
                data.data.forEach(function (item) {
                    html += $.convertTemplate('#page-temp', item, '');
                });
                $('#spec_value_box').html(html);
                initButton();
            });
        }

        $(document).ready(function () {
            getList();

        });


    </script>