<script>
    $(function () {
        $('#start_at').on('changeDate', function (ev) {
            $('#coupon_t_s').html($('input[name="base[starts_at]"]').val());
        });

        $('#end_at').on('changeDate', function (ev) {
            $('#coupon_t_e').html($('input[name="base[ends_at]"]').val());
        });

        var content = $('#base_intro').val().replace(/\n/g,"<br>");
        $('.coupon_area').html(content);
    });


    function OnInput(event) {

        if (event.target.name == 'base[title]') {
            $('#coupon_title').html(event.target.value);
        }

        if (event.target.name == 'base[intro]') {
            var content = event.target.value.replace(/\n/g, "<br>");
            $('.coupon_area').html(content);
        }


    }
    // Internet Explorer
    function OnPropChanged(event) {
        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'base[title]') {
            $('#coupon_title').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'base[intro]') {
            var content = event.target.value.replace(/\n/g, "<br>");
            $('.coupon_area').html(content);
        }
    }
</script>