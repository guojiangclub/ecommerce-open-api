<script>
    function OnInput(event) {
        if (event.target.name == 'wechat_message_order_pay_remind[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_order_pay_remind[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_deliver_goods_remind[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_deliver_goods_remind[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_arrival_of_goods[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_arrival_of_goods[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_after_sales_service[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_after_sales_service[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_goods_refund_result[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_goods_refund_result[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_customer_paid[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_customer_paid[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_money_changed[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_money_changed[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_point_changed[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_point_changed[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_charge_success[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_charge_success[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_member_grade[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_member_grade[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_refund_result[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_refund_result[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_sales_notice[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_sales_notice[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_groupon_success[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_groupon_success[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_groupon_failed[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.target.name == 'wechat_message_groupon_failed[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }
    }
    // Internet Explorer
    function OnPropChanged(event) {
        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_order_pay_remind[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_order_pay_remind[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_deliver_goods_remind[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_deliver_goods_remind[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_arrival_of_goods[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_arrival_of_goods[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_after_sales_service[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_after_sales_service[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_goods_refund_result[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_goods_refund_result[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_customer_paid[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_customer_paid[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_money_changed[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_money_changed[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_point_changed[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_point_changed[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_charge_success[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_charge_success[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_member_grade[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_member_grade[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_refund_result[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_refund_result[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_sales_notice[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_sales_notice[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_groupon_success[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_groupon_success[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_groupon_failed[first]') {
            $('.wechat_message_first_text_target').html(event.target.value);
        }

        if (event.propertyName.toLowerCase() == "value" && event.target.name == 'wechat_message_groupon_failed[remark]') {
            $('.wechat_message_remark_text_target').html(event.target.value);
        }
    }
</script>