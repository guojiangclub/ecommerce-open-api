{{--{!! Html::script('assets/backend/libs/jquery.form.min.js') !!}--}}
{!! Html::script(env("APP_URL").'/assets/backend/libs/Tagator/fm.tagator.jquery.js') !!}

{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/moment.min.js') !!}
{!! Html::script(env("APP_URL").'/assets/backend/libs/dategrangepicker/jquery.daterangepicker.js') !!}
<script>
    $(function () {
        $('#inputTags').tagator({
            autocomplete: []
        });

        $('body').on('click', '.switch', function () {
            var show = parseInt($(this).children('input').attr('value'));
            var that = $(this);
            var showObj = $(this).children('input');

            if (show == 1) {
                that.removeClass('fa-toggle-on');
                that.addClass('fa-toggle-off');
                showObj.val(0);
            } else {
                that.removeClass('fa-toggle-off');
                that.addClass('fa-toggle-on');
                showObj.val(1);
            }
        });


//        $('.switch').on('click',function () {
//            var show = parseInt($(this).children('input').attr('value'));
//            var that = $(this);
//            var showObj = $(this).children('input');
//
//            if (show == 1) {
//                that.removeClass('fa-toggle-on');
//                that.addClass('fa-toggle-off');
//                showObj.val(0);
//            } else {
//                that.removeClass('fa-toggle-off');
//                that.addClass('fa-toggle-on');
//                showObj.val(1);
//            }
//        });

        /**
         * 上传广告图
         */
        var postImgUrl = '{{route('upload.image',['_token'=>csrf_token()])}}';
        (function () {
            var body = $('body');
            body.on('change', 'input[name=upload_image]', function () {
                var el = $(this);
                var id = el.parents('tr').data('key');
                var file = this.files[0];
                var form = new FormData();
                form.append('id', id);
                form.append('upload_image', file);

                $.ajax({
                    url: postImgUrl,
                    type: 'POST',
                    data: form,
                    dataType: 'JSON',
                    cache: false,
                    processData: false,
                    contentType: false
                }).done(function (ret) {
                    var url = ret.url;
                    el.parents('tr').find('.block_banner' + ':first').html('' +
                            '<img width="50" src="' + url + '">' +
                            '<input type="hidden" name="item[' + id + '][img]" value="' + url + '">' +
                            '<input type="file" name="upload_image" data-name="' + name + '" accept="image/*">');


                }).fail(function () {

                });
            });
        }());


    });

    $('#two-inputs').dateRangePicker(
            {
                separator: ' to ',
                time: {
                    enabled: true
                },
                showShortcuts: false,
                language: 'cn',
                format: 'YYYY-MM-DD HH:mm',
                inline: true,
                container: '#date-range12-container',
                startDate: '{{\Carbon\Carbon::now()}}',
                getValue: function () {
                    if ($('#date-range200').val() && $('#date-range201').val())
                        return $('#date-range200').val() + ' to ' + $('#date-range201').val();
                    else
                        return '';
                },
                setValue: function (s, s1, s2) {
                    $('#date-range200').val(s1);
                    $('#date-range201').val(s2);
                }
            });

    /**
     * 移除所选商品
     * @param _self
     */
    function deleteSelect(_self, action) {
        var dom = $('#selected_spu');
        var btnVal = $(_self).data('id');
        var string = dom.val();
        var ids = string.split(',');
        var index = ids.indexOf(String(btnVal));
        if (!!~index) {
            ids.splice(index, 1);
        }
        var str = ids.join(',');
        dom.val(str);

        if (action == 'update') {
            var delete_dom = $('input[name="delete_item"]');
            var delete_id = $(_self).data('key');
            var delete_str = delete_dom.val() + ',' + delete_id;

            if (delete_str.substr(0, 1) == ',') delete_str = delete_str.substr(1);

            delete_dom.val(delete_str);


        }

        $(_self).parent().parent().remove();

    }


</script>