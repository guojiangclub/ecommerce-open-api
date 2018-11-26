{{--percentage action --}}
<script type="text/x-template" id="percentage_action_template">
    <div class="input-group m-b">
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
        <span class="input-group-addon">%</span>
    </div>
</script>

<script type="text/x-template" id="goods_percentage_action_template">
    <div class="input-group m-b">
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
        <span class="input-group-addon">%</span>
    </div>
    <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>
</script>

{{--discount action --}}
<script type="text/x-template" id="discount_action_template">
    <div class="input-group m-b">
        <span class="input-group-addon">$</span>
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
    </div>
</script>

<script type="text/x-template" id="goods_discount_action_template">
    <div class="input-group m-b">
        <span class="input-group-addon">$</span>
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
    </div>
    <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>
</script>

<script type="text/x-template" id="goods_percentage_action_template">
    <div class="input-group m-b">
        <input class="form-control" type="text" name="action[configuration]" value="{VALUE}">
        <span class="input-group-addon">%</span>
    </div>
    <p>此动作仅当规则存在<b> [指定产品] </b>或<b> [指定分类] </b>时生效</p>
</script>