<script>
    function sendIds(_self) {
        var id = $(_self).data('id');
        var img = $(_self).data('img');
        var name = $(_self).data('name');
        var price = $(_self).data('price');
        var nums = $(_self).data('nums');

        $('#img').attr('src', img);
        $('#name').html(name);
        $('#price').html('销售价：' + price);
        $('#nums').html('库存：' + nums);
        $('input[name="goods_id"]').val(id);

        $('#modal').modal('hide');

    }
</script>