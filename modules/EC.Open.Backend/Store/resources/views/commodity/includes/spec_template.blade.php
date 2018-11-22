<script type="text/html" id="template-spec">
    <div class="col-sm-4 col-md-3 col-lg-3">
        <label>
            <input type="checkbox" value="{#id#}" name="spec_id_{#sid#}[]"
                   data-uuid="{#sid#}-{#id#}" icheck-name="spec_{#sid#}_{#id#}"
                   {#checked#}>
            {#block#}
        </label>
        {#textInput#}
    </div>
</script>

<script type="text/html" id="template-spec-list">
    <div class="form-group">
        <label class="col-sm-2 control-label">{#label#}:</label>
        <div class="col-sm-10" id="spec-list-{#id#}">
            {#html#}
        </div>
    </div>
</script>

<script type="text/html" id="template-sku-tr">
    <td><input type="text" data-name="market_price" data-action="save"
               value="{#market_price#}" name="_spec[{#index#}][market_price]">
    </td>
    <td>
        <input type="hidden" data-name="id" data-action="save" value="{#id#}"
               name="_spec[{#index#}][id]">
        <input type="hidden" data-name="spec_ids" data-action="save"
               value="{#spec#}" name="_spec[{#index#}][spec_ids]">

        <input type="text" data-name="sell_price" data-action="save"
               value="{#sell_price#}" name="_spec[{#index#}][sell_price]"></td>
    <td><input type="text" data-name="store_nums" data-action="save"
               value="{#store_nums#}" name="_spec[{#index#}][store_nums]"></td>
    <td><input type="text" data-name="sku" data-action="save" value="{#sku#}"
               name="_spec[{#index#}][sku]"></td>
    <td>

        <a><i class="fa switch {#switch_class#}" title="切换状态">
                <input type="hidden" value="{#is_show#}"
                       name="_spec[{#index#}][is_show]">
            </i>
        </a>
    </td>
</script>

<script type="text/html" id="template-img-table">

</script>

<script type="text/html" id="template-img-tr">
    <tr data-id="{#id#}" data-sid="{#sid#}">
        <td>
            {#block#}
        </td>
        <td>
            <input type="text" data-id="{#id#}" data-sid="{#sid#}"
                   data-action="update" value="{#alias#}"
                   name="spec_alias[{#id#}]"/>
        </td>
        <td class="image">
            <div class="block{#class#}">{#imgTag#}</div>
            <label class="block img-plus">
                <span class="glyphicon glyphicon-plus"></span>
                <input type="file" name="upload_image" accept="image/*">
            </label>
        </td>
    </tr>
</script>